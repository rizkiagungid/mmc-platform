<?php

if (!function_exists('pwa_meta_tags')) {
    function pwa_meta_tags(): string
    {
        $config = config('Pwa');
        $baseUrl = base_url();

        $html  = '<meta name="theme-color" content="' . esc($config->themeColor) . '">' . "\n";
        $html .= '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
        $html .= '<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">' . "\n";
        $html .= '<meta name="apple-mobile-web-app-title" content="' . esc($config->shortName) . '">' . "\n";
        $html .= '<link rel="apple-touch-icon" href="' . $baseUrl . 'assets/icons/apple-touch-icon.png">' . "\n";
        $html .= '<link rel="manifest" href="' . $baseUrl . 'manifest.json">' . "\n";
        $html .= '<link rel="shortcut icon" href="' . $baseUrl . 'assets/icons/favicon.png" type="image/png">' . "\n";
        $html .= '<meta name="msapplication-config" content="' . $baseUrl . 'browserconfig.xml">' . "\n";
        $html .= '<meta name="msapplication-TileColor" content="' . esc($config->themeColor) . '">' . "\n";
        $html .= '<meta name="msapplication-TileImage" content="' . $baseUrl . 'assets/icons/icon-192.png">' . "\n";
        
        // Performance Preconnect & DNS Prefetch
        $html .= '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
        $html .= '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";

        return $html;
    }
}

if (!function_exists('pwa_sw_script')) {
    function pwa_sw_script(): string
    {
        $config = config('Pwa');
        if (!$config->enableSw) {
            return '';
        }

        $baseUrl = base_url();
        $dynamicVersion = esc($config->getDynamicVersion());
        $swUrl = $baseUrl . 'sw.js?v=' . $dynamicVersion;
        $postponeMs = $config->postponeDays * 86400 * 1000;

        return "
        <script>
            let refreshing = false;

            if ('serviceWorker' in navigator && (location.protocol === 'https:' || location.hostname === 'localhost' || location.hostname === '127.0.0.1')) {
                // Ensure loop-free reload on controllerchange
                navigator.serviceWorker.addEventListener('controllerchange', function() {
                    if (!refreshing) {
                        refreshing = true;
                        window.location.reload();
                    }
                });

                window.addEventListener('load', function() {
                    navigator.serviceWorker.register('{$swUrl}').then(function(reg) {
                        reg.onupdatefound = function() {
                            const installingWorker = reg.installing;
                            installingWorker.onstatechange = function() {
                                if (installingWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                    showPwaUpdateToast();
                                }
                            };
                        };
                    }).catch(function(err) {
                        console.warn('PWA ServiceWorker registration failed: ', err);
                    });
                });
            }

            // Live Network Status Indicator (Online/Offline Badge)
            window.addEventListener('online', function() {
                showNetworkStatusBadge(true);
            });
            window.addEventListener('offline', function() {
                showNetworkStatusBadge(false);
            });

            function showNetworkStatusBadge(isOnline) {
                let badge = document.getElementById('pwaNetworkStatusBadge');
                if (!badge) {
                    badge = document.createElement('div');
                    badge.id = 'pwaNetworkStatusBadge';
                    badge.className = 'position-fixed bottom-0 start-0 m-3 p-2 px-3 rounded-pill bg-dark border shadow-lg text-white style-tiny z-3 font-monospace';
                    document.body.appendChild(badge);
                }
                if (isOnline) {
                    badge.style.borderColor = '#22c55e';
                    badge.innerHTML = '<span class=\"text-success me-1\">🟢</span> Internet Terhubung Kembali';
                    badge.style.display = 'block';
                    setTimeout(() => { badge.style.display = 'none'; }, 4000);
                } else {
                    badge.style.borderColor = '#ef4444';
                    badge.innerHTML = '<span class=\"text-danger me-1\">🔴</span> Mode Offline (Koneksi Terputus)';
                    badge.style.display = 'block';
                }
            }

            // Local PWA Analytics Tracker
            function logPwaAnalytics(event) {
                try {
                    let logs = JSON.parse(localStorage.getItem('mmc_pwa_analytics') || '[]');
                    logs.push({ event: event, timestamp: new Date().toISOString() });
                    if (logs.length > 50) logs = logs.slice(-50);
                    localStorage.setItem('mmc_pwa_analytics', JSON.stringify(logs));
                } catch(e) {}
            }

            // PWA Install Prompt Banner Logic (Android & iOS Safari Compatible)
            let deferredPrompt;
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                logPwaAnalytics('install_prompt_eligible');
                checkAndShowInstallBanner();
            });

            document.addEventListener('DOMContentLoaded', function() {
                // Check initial network state
                if (!navigator.onLine) {
                    showNetworkStatusBadge(false);
                }

                // Handle iOS Safari Installation Notice
                const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
                const isStandalone = window.matchMedia('(display-mode: standalone)').matches || navigator.standalone;
                const lastPostponed = localStorage.getItem('mmc_pwa_install_postponed');
                const now = Date.now();

                if (isIOS && !isStandalone && (!lastPostponed || (now - parseInt(lastPostponed, 10)) > {$postponeMs})) {
                    const iosBanner = document.getElementById('pwaIosBanner');
                    if (iosBanner) {
                        iosBanner.style.display = 'block';
                        logPwaAnalytics('ios_banner_shown');
                    }
                }
            });

            function checkAndShowInstallBanner() {
                const isStandalone = window.matchMedia('(display-mode: standalone)').matches || navigator.standalone;
                const lastPostponed = localStorage.getItem('mmc_pwa_install_postponed');
                const now = Date.now();

                if (!isStandalone && (!lastPostponed || (now - parseInt(lastPostponed, 10)) > {$postponeMs})) {
                    const banner = document.getElementById('pwaInstallBanner');
                    if (banner) {
                        banner.style.display = 'block';
                        logPwaAnalytics('install_banner_shown');
                    }
                }
            }

            function dismissPwaInstallBanner() {
                const banner = document.getElementById('pwaInstallBanner');
                const iosBanner = document.getElementById('pwaIosBanner');
                if (banner) banner.style.display = 'none';
                if (iosBanner) iosBanner.style.display = 'none';
                localStorage.setItem('mmc_pwa_install_postponed', Date.now().toString());
                logPwaAnalytics('install_dismissed');
            }

            function triggerPwaInstall() {
                dismissPwaInstallBanner();
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            logPwaAnalytics('install_accepted');
                        } else {
                            logPwaAnalytics('install_declined');
                        }
                        deferredPrompt = null;
                    });
                }
            }

            function showPwaUpdateToast() {
                const toast = document.getElementById('pwaUpdateToast');
                if (toast) toast.style.display = 'block';
            }

            function reloadPwaForUpdate() {
                logPwaAnalytics('update_reloaded');
                if (navigator.serviceWorker.controller) {
                    navigator.serviceWorker.controller.postMessage({ type: 'SKIP_WAITING' });
                } else {
                    window.location.reload();
                }
            }
        </script>
        ";
    }
}
