/**
 * Progressier-Inspired PWA Install Banner & UX Controller
 * Multimedia Club Platform SMAN 1 Tamansari
 */
(function() {
    'use strict';

    const CONFIG = {
        postponeDays: 7,
        delayMs: 3000,
        scrollPercentTrigger: 30,
        excludedRoutes: ['/login', '/register', '/forgot-password', '/auth']
    };

    let deferredPrompt = null;
    let bannerShown = false;
    let timerId = null;

    // Check if current page is an authentication / login route
    function isAuthPage() {
        const path = window.location.pathname.toLowerCase();
        return CONFIG.excludedRoutes.some(route => path.includes(route));
    }

    // Check if running in installed standalone mode
    function isStandaloneMode() {
        return window.matchMedia('(display-mode: standalone)').matches ||
               navigator.standalone ||
               localStorage.getItem('mmc_pwa_installed') === 'true';
    }

    // Local Analytics Logger
    function logAnalytics(eventName) {
        try {
            let logs = JSON.parse(localStorage.getItem('mmc_pwa_analytics') || '[]');
            logs.push({ event: eventName, timestamp: new Date().toISOString() });
            if (logs.length > 60) logs = logs.slice(-60);
            localStorage.setItem('mmc_pwa_analytics', JSON.stringify(logs));
        } catch (e) {}
    }

    // Check eligibility: postponed 7-day check & session close check
    function isEligibleToShow() {
        if (isAuthPage() || isStandaloneMode()) return false;
        
        const lastPostponed = localStorage.getItem('mmc_pwa_install_postponed');
        const sessionClosed = sessionStorage.getItem('mmc_pwa_banner_closed');
        const now = Date.now();
        const postponeMs = CONFIG.postponeDays * 86400 * 1000;

        if (sessionClosed === 'true') return false;
        if (lastPostponed && (now - parseInt(lastPostponed, 10)) < postponeMs) return false;

        return true;
    }

    // Trigger Banner Entrance (Slide Down)
    function showProgressierBanner() {
        if (bannerShown || !isEligibleToShow()) return;

        const banner = document.getElementById('pwaProgressierBanner');
        if (banner) {
            banner.style.display = 'block';
            setTimeout(() => {
                banner.classList.add('show-banner');
                bannerShown = true;
                logAnalytics('banner_shown');
            }, 50);
        }
    }

    // Hide Banner (Slide Up)
    function hideProgressierBanner() {
        const banner = document.getElementById('pwaProgressierBanner');
        const iosBanner = document.getElementById('pwaIosBanner');

        if (banner) {
            banner.classList.remove('show-banner');
            banner.classList.add('hide-banner');
            setTimeout(() => {
                banner.style.display = 'none';
                banner.classList.remove('hide-banner');
            }, 350);
        }

        if (iosBanner) {
            iosBanner.style.display = 'none';
        }
        bannerShown = false;
    }

    // Delayed Trigger: 3 Seconds Timer OR 30% Scroll Threshold
    function setupDelayedEntrance() {
        if (!isEligibleToShow()) return;

        // 1. 3-Second Timer Trigger
        timerId = setTimeout(() => {
            showProgressierBanner();
        }, CONFIG.delayMs);

        // 2. 30% Scroll Threshold Trigger
        const handleScroll = () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            if (height > 0) {
                const scrolled = (winScroll / height) * 100;
                if (scrolled >= CONFIG.scrollPercentTrigger) {
                    if (timerId) clearTimeout(timerId);
                    showProgressierBanner();
                    window.removeEventListener('scroll', handleScroll);
                }
            }
        };
        window.addEventListener('scroll', handleScroll, { passive: true });
    }

    // Initialize Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
        if (isAuthPage() || isStandaloneMode()) {
            if (isStandaloneMode()) logAnalytics('already_installed');
            return;
        }

        // Handle iOS Safari Guidance Banner
        const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
        if (isIOS && isEligibleToShow()) {
            setTimeout(() => {
                const iosBanner = document.getElementById('pwaIosBanner');
                if (iosBanner) {
                    iosBanner.style.display = 'block';
                    logAnalytics('ios_banner_shown');
                }
            }, CONFIG.delayMs);
        }

        // ESC Key Listener for Accessibility
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && bannerShown) {
                window.closePwaBanner();
            }
        });
    });

    // Handle Native PWA beforeinstallprompt Event
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        logAnalytics('install_prompt_eligible');
        setupDelayedEntrance();
    });

    // Handle Successful App Installation
    window.addEventListener('appinstalled', () => {
        deferredPrompt = null;
        localStorage.setItem('mmc_pwa_installed', 'true');
        hideProgressierBanner();
        logAnalytics('install_accepted');

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'MMC Platform Berhasil Diinstall!',
                text: 'Aplikasi MMC siap diakses dari layar utama perangkat Anda.',
                showConfirmButton: false,
                timer: 4500,
                timerProgressBar: true
            });
        }
    });

    // Export Global Actions for HTML Buttons
    window.triggerPwaInstall = function() {
        logAnalytics('install_clicked');
        if (deferredPrompt) {
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                    localStorage.setItem('mmc_pwa_installed', 'true');
                    hideProgressierBanner();
                } else {
                    logAnalytics('install_declined');
                }
                deferredPrompt = null;
            });
        } else {
            window.showPwaLearnMore();
        }
    };

    window.postponePwaInstall = function() {
        localStorage.setItem('mmc_pwa_install_postponed', Date.now().toString());
        logAnalytics('install_postponed');
        hideProgressierBanner();
    };

    window.closePwaBanner = function() {
        sessionStorage.setItem('mmc_pwa_banner_closed', 'true');
        logAnalytics('install_dismissed');
        hideProgressierBanner();
    };

    window.showPwaLearnMore = function() {
        logAnalytics('learn_more_clicked');
        const modalEl = document.getElementById('pwaLearnMoreModal');
        if (modalEl && typeof bootstrap !== 'undefined') {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    };

})();
