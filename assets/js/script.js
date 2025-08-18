// Admin Panel JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // Sidebar Toggle Functionality
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    const closeSidebar = document.getElementById('closeSidebar');
    
    // Toggle sidebar on mobile
    menuToggle.addEventListener('click', function() {
        sidebar.classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent background scroll
    });
    
    // Close sidebar
    closeSidebar.addEventListener('click', function() {
        sidebar.classList.remove('active');
        document.body.style.overflow = ''; // Restore scroll
    });
    
    // Close sidebar when clicking outside
    document.addEventListener('click', function(e) {
        if (sidebar.classList.contains('active') && 
            !sidebar.contains(e.target) && 
            !menuToggle.contains(e.target)) {
            sidebar.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
    
    // Navigation functionality
    const navLinks = document.querySelectorAll('.sidebar-nav a');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all links
            navLinks.forEach(l => l.parentElement.classList.remove('active'));
            
            // Add active class to clicked link
            this.parentElement.classList.add('active');
            
            // Update page title
            const pageTitle = this.querySelector('span').textContent;
            document.querySelector('.header-left h1').textContent = pageTitle;
            
            // Close sidebar on mobile after navigation
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });
    
    // Search functionality
    const searchInput = document.querySelector('.search-box input');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        // You can implement search functionality here
        console.log('Searching for:', searchTerm);
    });
    
    // Dropdown açma/kapama ve etkileşimler
    const notifications = document.querySelector('.notifications');
    const notificationsDropdown = document.querySelector('.notifications-dropdown');
    const userProfile = document.querySelector('.user-profile');
    const userDropdown = document.querySelector('.user-dropdown');

    if (notifications && notificationsDropdown) {
        notifications.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationsDropdown.classList.toggle('active');
            // Diğer dropdown kapansın
            if (userDropdown) userDropdown.classList.remove('active');
            if (userProfile) userProfile.classList.remove('active');
        });
    }
    if (userProfile && userDropdown) {
        userProfile.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
            userProfile.classList.toggle('active');
            // Diğer dropdown kapansın
            if (notificationsDropdown) notificationsDropdown.classList.remove('active');
        });
    }
    document.addEventListener('click', function(e) {
        if (notificationsDropdown) notificationsDropdown.classList.remove('active');
        if (userDropdown) userDropdown.classList.remove('active');
        if (userProfile) userProfile.classList.remove('active');
    });
    
    // Mark notification as read
    const markReadButtons = document.querySelectorAll('.mark-read');
    markReadButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const notificationItem = this.closest('.notification-item');
            notificationItem.classList.remove('unread');
            
            // Update badge count
            updateNotificationBadge();
        });
    });
    
    // Mark all notifications as read
    const markAllReadButton = document.querySelector('.mark-all-read');
    markAllReadButton.addEventListener('click', function(e) {
        e.stopPropagation();
        const unreadNotifications = document.querySelectorAll('.notification-item.unread');
        unreadNotifications.forEach(item => {
            item.classList.remove('unread');
        });
        
        // Update badge count
        updateNotificationBadge();
    });
    
    // Update notification badge count
    function updateNotificationBadge() {
        const unreadCount = document.querySelectorAll('.notification-item.unread').length;
        const badge = document.querySelector('.notifications .badge');
        
        if (unreadCount > 0) {
            badge.textContent = unreadCount;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }
    }
    
   
    
    
    // Table row click handlers
    const tableRows = document.querySelectorAll('.data-table tbody tr');
    
    tableRows.forEach(row => {
        row.addEventListener('click', function() {
            // Add hover effect
            tableRows.forEach(r => r.style.backgroundColor = '');
            this.style.backgroundColor = '#f8f9fa';
            
            // You can implement row detail view here
            const orderId = this.querySelector('td:first-child').textContent;
            console.log('Order clicked:', orderId);
        });
    });
    
    // Status badge click handlers
    const statusBadges = document.querySelectorAll('.status');
    
    statusBadges.forEach(badge => {
        badge.addEventListener('click', function() {
            const status = this.textContent;
            const orderId = this.closest('tr').querySelector('td:first-child').textContent;
            
            // You can implement status change functionality here
            console.log('Status change for order:', orderId, 'to:', status);
        });
    });
    
    // Activity feed item click handlers
    const activityItems = document.querySelectorAll('.activity-item');
    
    activityItems.forEach(item => {
        item.addEventListener('click', function() {
            // You can implement activity detail view here
            const activityText = this.querySelector('p').textContent;
            console.log('Activity clicked:', activityText);
        });
    });
    
    // Responsive table functionality
    function handleTableResponsive() {
        const table = document.querySelector('.data-table');
        const container = table.closest('.table-container');
        
        if (window.innerWidth <= 768) {
            // Add horizontal scroll indicator
            if (!container.querySelector('.scroll-indicator')) {
                const indicator = document.createElement('div');
                indicator.className = 'scroll-indicator';
                indicator.innerHTML = '<i class="fas fa-arrows-alt-h"></i> Yatay kaydırın';
                indicator.style.cssText = `
                    text-align: center;
                    padding: 10px;
                    color: #666;
                    font-size: 0.8rem;
                    background: #f8f9fa;
                    border-top: 1px solid #eee;
                `;
                container.appendChild(indicator);
            }
        }
    }
    
    // Call on load and resize
    handleTableResponsive();
    window.addEventListener('resize', handleTableResponsive);
    
    // Add loading states
    function addLoadingState(element) {
        element.classList.add('loading');
        element.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Yükleniyor...';
    }
    
    function removeLoadingState(element, originalContent) {
        element.classList.remove('loading');
        element.innerHTML = originalContent;
    }
    
    // Simulate data loading
    function simulateDataLoading() {
        const statCards = document.querySelectorAll('.stat-card');
        
        statCards.forEach((card, index) => {
            const originalContent = card.innerHTML;
            
            setTimeout(() => {
                addLoadingState(card);
                
                setTimeout(() => {
                    removeLoadingState(card, originalContent);
                }, 1000 + (index * 200));
            }, index * 100);
        });
    }
    
    // Initialize with loading animation
    setTimeout(simulateDataLoading, 500);
    
    // Add smooth scrolling for sidebar
    const sidebarNav = document.querySelector('.sidebar-nav');
    
    sidebarNav.addEventListener('scroll', function() {
        // Add shadow effect when scrolling
        if (this.scrollTop > 0) {
            this.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
        } else {
            this.style.boxShadow = 'none';
        }
    });
    
    // Add keyboard navigation
    document.addEventListener('keydown', function(e) {
        // ESC key to close sidebar
        if (e.key === 'Escape' && sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
            document.body.style.overflow = '';
        }
        
        // ESC key to close dropdowns
        if (e.key === 'Escape') {
            notificationsDropdown.classList.remove('active');
            userDropdown.classList.remove('active');
            userProfile.classList.remove('active');
        }
        
        // Ctrl/Cmd + K for search focus
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
        }
    });
    
    // Add touch gestures for mobile
    let touchStartX = 0;
    let touchEndX = 0;
    
    document.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    });
    
    document.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });
    
    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        // Swipe right to open sidebar
        if (diff < -swipeThreshold && window.innerWidth <= 768) {
            sidebar.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        
        // Swipe left to close sidebar
        if (diff > swipeThreshold && sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
            document.body.style.overflow = '';
        }
    }
    
    // Add auto-refresh functionality (optional)
    function autoRefresh() {
        // You can implement auto-refresh for real-time data here
        console.log('Auto-refreshing data...');
    }
    
    // Set up auto-refresh every 30 seconds
    setInterval(autoRefresh, 30000);
    
    // Add theme toggle functionality (for future use)
    function toggleTheme() {
        document.body.classList.toggle('dark-theme');
        localStorage.setItem('theme', document.body.classList.contains('dark-theme') ? 'dark' : 'light');
    }
    
    // Load saved theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
    }
    
    // Export functions for external use
    window.AdminPanel = {
        toggleSidebar: () => {
            sidebar.classList.toggle('active');
            document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
        },
        closeSidebar: () => {
            sidebar.classList.remove('active');
            document.body.style.overflow = '';
        },
        toggleTheme: toggleTheme,
        
        // Notification System Functions
        showNotification: (title, message, type = 'info', duration = 5000) => {
            showNotification(title, message, type, duration);
        },
        showInfo: (title, message, duration) => {
            showNotification(title, message, 'info', duration);
        },
        showWarning: (title, message, duration) => {
            showNotification(title, message, 'warning', duration);
        },
        showSuccess: (title, message, duration) => {
            showNotification(title, message, 'success', duration);
        },
        showError: (title, message, duration) => {
            showNotification(title, message, 'error', duration);
        },
        clearAllNotifications: () => {
            clearAllNotifications();
        }
    };
    
    // Global Notification System
    const notificationSystem = document.getElementById('notificationSystem');
    
    function showNotification(title, message, type = 'info', duration = 5000) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        // Add progress bar for auto-dismiss
        if (duration > 0) {
            notification.classList.add('progress');
        }
        
        notification.innerHTML = `
            <div class="notification-icon"></div>
            <div class="notification-content">
                <h4 class="notification-title">${title}</h4>
                <p class="notification-message">${message}</p>
            </div>
            <button class="notification-close" onclick="removeNotification(this.parentElement)">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        notificationSystem.appendChild(notification);
        
        // Auto-dismiss after duration
        if (duration > 0) {
            setTimeout(() => {
                removeNotification(notification);
            }, duration);
        }
        
        // Add click to dismiss (except on close button)
        notification.addEventListener('click', function(e) {
            if (!e.target.closest('.notification-close')) {
                removeNotification(this);
            }
        });
        
        return notification;
    }
    
    function removeNotification(notification) {
        if (!notification) return;
        
        notification.classList.add('removing');
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }
    
    function clearAllNotifications() {
        const notifications = notificationSystem.querySelectorAll('.notification');
        notifications.forEach(notification => {
            removeNotification(notification);
        });
    }
    
    // Global function for external use
    window.removeNotification = removeNotification;
    
    
    
    // Notification test buttons
    const notificationTestButtons = document.querySelectorAll('.notification-test');
    notificationTestButtons.forEach(button => {
        button.addEventListener('click', function() {
            const type = this.getAttribute('data-type');
            const messages = {
                info: {
                    title: 'Bilgi',
                    message: 'Bu bir bilgi mesajıdır. Sistem durumu normal.'
                },
                warning: {
                    title: 'Uyarı',
                    message: 'Bu bir uyarı mesajıdır. Dikkatli olun.'
                },
                success: {
                    title: 'Başarılı',
                    message: 'İşlem başarıyla tamamlandı.'
                },
                error: {
                    title: 'Hata',
                    message: 'Bir hata oluştu. Lütfen tekrar deneyin.'
                }
            };
            
            const message = messages[type];
            showNotification(message.title, message.message, type, 5000);
        });
    });
    
    console.log('Admin Panel initialized successfully!');
}); 