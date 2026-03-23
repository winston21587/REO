<script>
    // Notification Toggle Script
    document.addEventListener('DOMContentLoaded', function () {
        // --- Smart Mobile Bottom Nav Scroll Logic ---
        const bottomNav = document.getElementById('bottom-nav');

        if (bottomNav) {
            let lastScrollTop = 0;

            // Function to handle scroll logic
            const handleScroll = () => {
                // Try to get scroll position from multiple sources
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;

                if (scrollTop <= 0) {
                    bottomNav.style.transform = 'translateY(0)';
                    lastScrollTop = 0;
                    return;
                }

                if (scrollTop > lastScrollTop) {
                    // Scrolling DOWN -> HIDE
                    bottomNav.style.transform = 'translateY(100%)';
                } else {
                    // Scrolling UP -> SHOW
                    bottomNav.style.transform = 'translateY(0)';
                }
                lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
            };

            // Attach to likely scroll containers
            window.addEventListener('scroll', handleScroll, { passive: true });
            document.body.addEventListener('scroll', handleScroll, { passive: true });
            document.documentElement.addEventListener('scroll', handleScroll, { passive: true });
        }

        // --- Notification Logic ---
        // Select ALL notification triggers (Mobile & Desktop)
        const btns = document.querySelectorAll('.notification-trigger');
        const panel = document.getElementById('notifications-panel');

        if (btns.length > 0 && panel) {
            let isOpen = false;

            function toggleNotifications(e) {
                e.stopPropagation();
                isOpen = !isOpen;

                if (isOpen) {
                    // Opening panel - hide red dots
                    btns.forEach(btn => {
                        const dot = btn.querySelector('span.animate-pulse');
                        if (dot) {
                            dot.classList.add('hidden');
                        }
                    });
                    
                    panel.style.display = 'block';
                    setTimeout(() => {
                        panel.classList.remove('opacity-0', 'scale-95');
                        panel.classList.add('opacity-100', 'scale-100');
                    }, 10);
                } else {
                    // Closing panel - always hide red dots (they stay hidden)
                    // They only reappear when NEW notifications actually arrive
                    btns.forEach(btn => {
                        const dot = btn.querySelector('span.animate-pulse');
                        if (dot) {
                            dot.classList.add('hidden');
                        }
                    });
                    
                    panel.classList.remove('opacity-100', 'scale-100');
                    panel.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => {
                        panel.style.display = 'none';
                    }, 200);
                }
            }

            // Attach event to all buttons
            btns.forEach(btn => btn.addEventListener('click', toggleNotifications));

            document.addEventListener('click', function (e) {
                // Check if click is outside panel AND outside ANY trigger button
                let clickedInsideButton = false;
                btns.forEach(btn => {
                    if (btn.contains(e.target)) clickedInsideButton = true;
                });

                if (isOpen && !panel.contains(e.target) && !clickedInsideButton) {
                    // Close it
                    isOpen = true; // wait, logic was: toggle(e) toggles layout. 
                    // If we are open, we want to close.
                    toggleNotifications(e);
                }
            });

            // --- Notification Management System ---
            let lastNotificationCount = 0;
            let initialNotificationCount = null; // Track initial count
            let hasShownLoginToast = sessionStorage.getItem('notificationLoginToastShown') === 'true';
            
            // Show toast only on first login (appears once)
            function showLoginNotificationToast(initialCount) {
                if (!hasShownLoginToast && initialCount > 0) {
                    const toast = document.getElementById('notification-toast');
                    if (toast) {
                        toast.classList.remove('hidden', 'opacity-0', 'scale-95');
                        toast.classList.add('opacity-100', 'scale-100');
                        
                        // Auto-hide toast after 6 seconds
                        setTimeout(() => {
                            toast.classList.add('opacity-0', 'scale-95');
                            setTimeout(() => {
                                toast.classList.add('hidden');
                            }, 300);
                        }, 6000);
                        
                        // Mark that we've shown the login toast (only once per session)
                        sessionStorage.setItem('notificationLoginToastShown', 'true');
                        hasShownLoginToast = true;
                    }
                }
            }

            // Check for new notifications via AJAX
            function checkForNewNotifications() {
                fetch('{{ route("notifications.api.unread") }}')
                    .then(response => response.json())
                    .then(data => {
                        const currentCount = data.unread_count;
                        
                        // Update red dot visibility - hide if panel is open or if no unread
                        const notificationTriggers = document.querySelectorAll('.notification-trigger');
                        notificationTriggers.forEach(trigger => {
                            const dot = trigger.querySelector('span.animate-pulse');
                            if (dot) {
                                // Hide dot if no unread OR if panel is open
                                if (currentCount > 0 && !isOpen) {
                                    // Show dot if there are unread and panel is closed
                                    dot.classList.remove('hidden');
                                } else {
                                    // Hide dot otherwise
                                    dot.classList.add('hidden');
                                }
                            }
                        });
                        
                        // Update notification panel count if it exists
                        const notifPanel = document.getElementById('notifications-panel');
                        if (notifPanel) {
                            const unreadSpans = notifPanel.querySelectorAll('span');
                            unreadSpans.forEach(span => {
                                if (span.textContent.includes('unread')) {
                                    if (currentCount > 0) {
                                        span.textContent = currentCount + ' unread';
                                    }
                                }
                            });
                        }
                        
                        // Show toast only if new notifications arrived (currentCount > lastNotificationCount)
                        if (currentCount > lastNotificationCount && hasShownLoginToast) {
                            const toast = document.getElementById('notification-toast');
                            if (toast) {
                                toast.classList.remove('hidden', 'opacity-0', 'scale-95');
                                toast.classList.add('opacity-100', 'scale-100');
                                
                                setTimeout(() => {
                                    toast.classList.add('opacity-0', 'scale-95');
                                    setTimeout(() => {
                                        toast.classList.add('hidden');
                                    }, 300);
                                }, 5000);
                            }
                        }
                        
                        lastNotificationCount = currentCount;
                    })
                    .catch(error => console.error('Error checking notifications:', error));
            }

            // Initialize notifications on page load
            function initializeNotifications() {
                fetch('{{ route("notifications.api.unread") }}')
                    .then(response => response.json())
                    .then(data => {
                        initialNotificationCount = data.unread_count;
                        lastNotificationCount = data.unread_count;
                        
                        // Show login toast if there are unread on first load
                        showLoginNotificationToast(initialNotificationCount);
                        
                        // Update red dots
                        const notificationTriggers = document.querySelectorAll('.notification-trigger');
                        notificationTriggers.forEach(trigger => {
                            const dot = trigger.querySelector('span.animate-pulse');
                            if (dot && initialNotificationCount > 0 && !isOpen) {
                                dot.classList.remove('hidden');
                            }
                        });
                    })
                    .catch(error => console.error('Error initializing notifications:', error));
            }

            // Initialize on page load
            initializeNotifications();
            
            // Check for notifications every 3 seconds (faster updates)
            setInterval(checkForNewNotifications, 3000);
        }
    });
</script>
