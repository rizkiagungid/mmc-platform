/**
 * MMC Platform - Device Push & Web Notification Engine
 * Compatible with Android (Chrome/PWA), iOS (Safari PWA), Windows, Mac, Linux
 */

(function () {
    'use strict';

    const NOTIF_POLL_INTERVAL_ACTIVE = 12000;   // 12 seconds when tab is active
    const NOTIF_POLL_INTERVAL_IDLE   = 30000;   // 30 seconds when tab is idle/background
    let pollTimer = null;
    let isPolling = false;

    // Pleasant Web Audio API Synthesized Chime (No external audio file needed)
    function playNotificationSound() {
        try {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (!AudioCtx) return;
            const ctx = new AudioCtx();
            if (ctx.state === 'suspended') {
                ctx.resume();
            }

            const now = ctx.currentTime;
            const osc1 = ctx.createOscillator();
            const osc2 = ctx.createOscillator();
            const gainNode = ctx.createGain();

            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(587.33, now); // D5
            osc1.frequency.exponentialRampToValueAtTime(880, now + 0.12); // A5

            osc2.type = 'triangle';
            osc2.frequency.setValueAtTime(880, now + 0.12);
            osc2.frequency.exponentialRampToValueAtTime(1174.66, now + 0.28); // D6

            gainNode.gain.setValueAtTime(0, now);
            gainNode.gain.linearRampToValueAtTime(0.25, now + 0.04);
            gainNode.gain.exponentialRampToValueAtTime(0.001, now + 0.45);

            osc1.connect(gainNode);
            osc2.connect(gainNode);
            gainNode.connect(ctx.destination);

            osc1.start(now);
            osc1.stop(now + 0.15);
            osc2.start(now + 0.12);
            osc2.stop(now + 0.45);
        } catch (e) {
            // Audio context not allowed without prior user gesture
        }
    }

    // Check Current Permission Status
    function getNotificationPermission() {
        if (!('Notification' in window)) return 'unsupported';
        return Notification.permission; // 'granted', 'denied', 'default'
    }

    // Request Permission from User
    function requestNotificationPermission(onGranted, onDenied) {
        if (!('Notification' in window)) {
            if (typeof onDenied === 'function') onDenied('unsupported');
            return;
        }

        Notification.requestPermission().then(function (permission) {
            updateNotificationUIStatus(permission);
            if (permission === 'granted') {
                playNotificationSound();
                if (typeof onGranted === 'function') onGranted();
            } else {
                if (typeof onDenied === 'function') onDenied(permission);
            }
        }).catch(function (err) {
            console.warn('[MMC Notification] Permission request error:', err);
        });
    }

    // Show Native Device Notification (Desktop / Mobile)
    function showDeviceNotification(options) {
        const title = options.title || 'Multimedia Club System';
        const body  = options.body || options.message || 'Pemberitahuan baru di platform MMC';
        const url   = options.url || options.target_url || (window.location.origin + '/notifications');
        const icon  = options.icon || (window.location.origin + '/assets/icons/icon-192.png');
        const badge = options.badge || (window.location.origin + '/assets/icons/favicon.png');
        const tag   = options.tag || ('mmc-notif-' + (options.id || Date.now()));

        playNotificationSound();

        // 1. Try Service Worker Registration showNotification (Best for Mobile Android/iOS PWA & Desktop)
        if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
            navigator.serviceWorker.ready.then(function (registration) {
                if (registration && typeof registration.showNotification === 'function') {
                    registration.showNotification(title, {
                        body: body,
                        icon: icon,
                        badge: badge,
                        tag: tag,
                        renotify: true,
                        vibrate: [200, 100, 200],
                        data: { url: url }
                    });
                    return;
                }
                fallbackNativeNotification(title, body, icon, url, tag);
            }).catch(function () {
                fallbackNativeNotification(title, body, icon, url, tag);
            });
        } else {
            fallbackNativeNotification(title, body, icon, url, tag);
        }
    }

    function fallbackNativeNotification(title, body, icon, url, tag) {
        if (!('Notification' in window) || Notification.permission !== 'granted') return;
        try {
            const notif = new Notification(title, {
                body: body,
                icon: icon,
                tag: tag,
                renotify: true
            });
            notif.onclick = function (e) {
                e.preventDefault();
                window.focus();
                if (url) window.location.href = url;
                notif.close();
            };
        } catch (err) {
            console.warn('[MMC Notification] Native notification error:', err);
        }
    }

    // Update UI Elements (Badges, Counters, Permission Status Buttons)
    function updateNotificationUIStatus(permission) {
        const statusBadges = document.querySelectorAll('.mmc-notif-permission-status');
        const enableBtns   = document.querySelectorAll('.mmc-btn-enable-notif');

        statusBadges.forEach(function (el) {
            if (permission === 'granted') {
                el.className = 'badge bg-success font-monospace style-tiny py-1 px-2.5 rounded-pill';
                el.innerHTML = '<i class="fa-solid fa-circle-check me-1"></i> Notifikasi HP/Laptop Aktif';
            } else if (permission === 'denied') {
                el.className = 'badge bg-danger font-monospace style-tiny py-1 px-2.5 rounded-pill';
                el.innerHTML = '<i class="fa-solid fa-circle-xmark me-1"></i> Izin Diblokir di Browser';
            } else if (permission === 'unsupported') {
                el.className = 'badge bg-secondary font-monospace style-tiny py-1 px-2.5 rounded-pill';
                el.innerHTML = '<i class="fa-solid fa-triangle-exclamation me-1"></i> Browser Tidak Mendukung';
            } else {
                el.className = 'badge bg-warning text-dark font-monospace style-tiny py-1 px-2.5 rounded-pill';
                el.innerHTML = '<i class="fa-solid fa-bell me-1"></i> Belum Diizinkan';
            }
        });

        enableBtns.forEach(function (btn) {
            if (permission === 'granted') {
                btn.style.display = 'none';
            } else {
                btn.style.display = 'inline-flex';
            }
        });
    }

    function updateBadgeCounters(unreadNotif, unreadChat) {
        // Notification badges in sidebar/topbar
        const notifBadges = document.querySelectorAll('a[href*="/notifications"] .badge, #notif-badge-counter, .sidebar-link[href*="notifications"] .badge');
        notifBadges.forEach(function (badge) {
            if (unreadNotif > 0) {
                badge.textContent = unreadNotif > 99 ? '99+' : unreadNotif;
                badge.style.display = '';
            } else {
                badge.style.display = 'none';
            }
        });

        // Chat inbox badges
        if (typeof unreadChat === 'number') {
            const chatBadges = document.querySelectorAll('a[href*="/inbox"] .badge, .sidebar-link[href*="inbox"] .badge');
            chatBadges.forEach(function (badge) {
                if (unreadChat > 0) {
                    badge.textContent = unreadChat > 99 ? '99+' : unreadChat;
                    badge.style.display = '';
                } else {
                    badge.style.display = 'none';
                }
            });
        }
    }

    // Polling Engine
    function pollNotifications() {
        if (isPolling) return;
        isPolling = true;

        const lastId = parseInt(localStorage.getItem('mmc_last_notif_id') || '0', 10);
        const checkUrl = (window.location.origin || '') + '/notifications/check-new?last_id=' + lastId;

        fetch(checkUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            isPolling = false;
            if (data && data.status === 'success') {
                updateBadgeCounters(data.unread_count, data.unread_chat);

                if (Array.isArray(data.notifications) && data.notifications.length > 0) {
                    // If lastId was 0, initialize lastId without spamming previous notifications
                    if (lastId === 0) {
                        localStorage.setItem('mmc_last_notif_id', data.latest_id || '0');
                        return;
                    }

                    data.notifications.forEach(function (n) {
                        if (getNotificationPermission() === 'granted') {
                            showDeviceNotification({
                                id: n.id,
                                title: n.title,
                                body: n.message,
                                url: n.target_url
                            });
                        }
                    });

                    localStorage.setItem('mmc_last_notif_id', data.latest_id || lastId);
                } else if (data.latest_id) {
                    localStorage.setItem('mmc_last_notif_id', data.latest_id);
                }
            }
        })
        .catch(function () {
            isPolling = false;
        });
    }

    function startNotificationPolling() {
        if (pollTimer) clearInterval(pollTimer);
        pollNotifications();
        const interval = document.hidden ? NOTIF_POLL_INTERVAL_IDLE : NOTIF_POLL_INTERVAL_ACTIVE;
        pollTimer = setInterval(pollNotifications, interval);
    }

    // Handle Page Visibility Changes (Optimize battery & performance)
    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) {
            pollNotifications();
            startNotificationPolling();
        }
    });

    // Test Notification Function (Triggerable globally from UI)
    window.testDeviceNotification = function () {
        const perm = getNotificationPermission();
        if (perm !== 'granted') {
            requestNotificationPermission(function () {
                sendTestPushRequest();
            }, function (deniedReason) {
                alert('Izin notifikasi belum diberikan di browser Anda. Silakan klik "Izinkan" saat browser meminta izin notifikasi.');
            });
            return;
        }
        sendTestPushRequest();
    };

    function sendTestPushRequest() {
        fetch((window.location.origin || '') + '/notifications/test-push', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function (res) { return res.json(); })
        .then(function (data) {
            if (data && data.status === 'success') {
                showDeviceNotification({
                    id: data.notif_id || Date.now(),
                    title: data.title,
                    body: data.body,
                    url: data.url
                });
            }
        })
        .catch(function () {
            showDeviceNotification({
                title: 'Tes Notifikasi MMC Berhasil! 🎉',
                body: 'Notifikasi sistem Multimedia Club kini aktif dan siap muncul di layar HP atau Laptop Anda.',
                url: window.location.origin + '/notifications'
            });
        });
    }

    window.requestDeviceNotificationPermission = function () {
        requestNotificationPermission(function () {
            showDeviceNotification({
                title: 'Notifikasi MMC Berhasil Diaktifkan! 🔔',
                body: 'Anda akan menerima pemberitahuan tugas, presensi, dan chat langsung di HP / Laptop.',
                url: window.location.origin + '/notifications'
            });
        });
    };

    // Auto Init on Page Load
    document.addEventListener('DOMContentLoaded', function () {
        updateNotificationUIStatus(getNotificationPermission());
        startNotificationPolling();
    });

    // Expose global methods
    window.MMCPushNotifications = {
        getPermission: getNotificationPermission,
        requestPermission: requestNotificationPermission,
        showNotification: showDeviceNotification,
        playSound: playNotificationSound,
        test: window.testDeviceNotification
    };

})();
