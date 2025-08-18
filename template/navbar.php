<main class="main-content">
    <!-- Top Header -->
    <header class="top-header">
        <div class="header-left">
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>
            <h1>Dashboard</h1>
        </div>

        <div class="header-right">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Ara...">
            </div>

            <div class="user-menu">
                <div class="notifications">
                    <i class="fas fa-bell"></i>
                    <span class="badge">3</span>
                    <!-- Notifications Dropdown -->
                    <div class="dropdown-menu notifications-dropdown">
                        <div class="dropdown-header">
                            <h4>Bildirimler</h4>
                            <button class="mark-all-read">Tümünü Okundu İşaretle</button>
                        </div>
                        <div class="dropdown-content">
                            <div class="notification-item unread">
                                <div class="notification-icon">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="notification-content">
                                    <p><strong>Yeni kullanıcı kaydoldu</strong></p>
                                    <p>Ahmet Yılmaz sisteme kayıt oldu</p>
                                    <span class="notification-time">2 dakika önce</span>
                                </div>
                                <button class="mark-read">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                            <div class="notification-item unread">
                                <div class="notification-icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="notification-content">
                                    <p><strong>Yeni sipariş alındı</strong></p>
                                    <p>#12348 siparişi onaylandı</p>
                                    <span class="notification-time">15 dakika önce</span>
                                </div>
                                <button class="mark-read">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                            <div class="notification-item unread">
                                <div class="notification-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="notification-content">
                                    <p><strong>Sistem uyarısı</strong></p>
                                    <p>Sunucu yükü %85'e ulaştı</p>
                                    <span class="notification-time">1 saat önce</span>
                                </div>
                                <button class="mark-read">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                            <div class="notification-item">
                                <div class="notification-icon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="notification-content">
                                    <p><strong>Yeni ürün eklendi</strong></p>
                                    <p>iPhone 15 Pro kataloğa eklendi</p>
                                    <span class="notification-time">2 saat önce</span>
                                </div>
                                <button class="mark-read">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                        </div>
                        <div class="dropdown-footer">
                            <a href="#notifications">Tüm Bildirimleri Gör</a>
                        </div>
                    </div>
                </div>
                <div class="user-profile">
                    <!-- <img src="https://via.placeholder.com/40x40" alt="User"> -->
                    <span>Admin</span>
                    <i class="fas fa-chevron-down"></i>
                    <!-- User Profile Dropdown -->
                    <div class="dropdown-menu user-dropdown">
                        <div class="dropdown-header">
                            <div class="user-info">
                                <!-- <img src="https://via.placeholder.com/50x50" alt="User"> -->
                                <div>
                                    <h4>Admin User</h4>
                                    <p>admin@example.com</p>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-content">
                            <a href="#profile" class="dropdown-item">
                                <i class="fas fa-user"></i>
                                <span>Profil</span>
                            </a>
                            <a href="#settings" class="dropdown-item">
                                <i class="fas fa-cog"></i>
                                <span>Ayarlar</span>
                            </a>
                            <a href="#help" class="dropdown-item">
                                <i class="fas fa-question-circle"></i>
                                <span>Yardım</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="<?=ROuter::controllers('logoutController')?>" class="dropdown-item logout">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Çıkış Yap</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <script>
      
    </script>
  