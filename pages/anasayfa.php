<?php
require_once __DIR__ . '/../template/template.php';




?>
<div class="dashboard-content">
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>1,234</h3>
                <p>Toplam Kullanıcı</p>
                <span class="stat-change positive">+12%</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-info">
                <h3>567</h3>
                <p>Toplam Sipariş</p>
                <span class="stat-change positive">+8%</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-info">
                <h3>₺45,678</h3>
                <p>Toplam Gelir</p>
                <span class="stat-change positive">+15%</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-info">
                <h3>89</h3>
                <p>Toplam Ürün</p>
                <span class="stat-change negative">-3%</span>
            </div>
        </div>
    </div>

    <!-- Charts and Tables -->
    <div class="content-grid">
        <!-- Recent Orders -->
        <div class="content-card">
            <div class="card-header">
                <h3>Son Siparişler</h3>
                <a href="#" class="view-all">Tümünü Gör</a>
            </div>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Sipariş ID</th>
                            <th>Müşteri</th>
                            <th>Ürün</th>
                            <th>Tutar</th>
                            <th>Durum</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#12345</td>
                            <td>Ahmet Yılmaz</td>
                            <td>iPhone 15 Pro</td>
                            <td>₺45,999</td>
                            <td><span class="status completed">Tamamlandı</span></td>
                        </tr>
                        <tr>
                            <td>#12346</td>
                            <td>Ayşe Demir</td>
                            <td>MacBook Air</td>
                            <td>₺32,999</td>
                            <td><span class="status pending">Beklemede</span></td>
                        </tr>
                        <tr>
                            <td>#12347</td>
                            <td>Mehmet Kaya</td>
                            <td>AirPods Pro</td>
                            <td>₺7,999</td>
                            <td><span class="status processing">İşleniyor</span></td>
                        </tr>
                        <tr>
                            <td>#12348</td>
                            <td>Fatma Öz</td>
                            <td>iPad Air</td>
                            <td>₺18,999</td>
                            <td><span class="status completed">Tamamlandı</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="content-card">
            <div class="card-header">
                <h3>Hızlı İşlemler</h3>
            </div>
            <div class="quick-actions">
                <button class="action-btn">
                    <i class="fas fa-plus"></i>
                    <span>Yeni Kullanıcı</span>
                </button>
                <button class="action-btn">
                    <i class="fas fa-box"></i>
                    <span>Ürün Ekle</span>
                </button>
                <button class="action-btn">
                    <i class="fas fa-chart-line"></i>
                    <span>Rapor Oluştur</span>
                </button>
                <button class="action-btn">
                    <i class="fas fa-cog"></i>
                    <span>Ayarlar</span>
                </button>
            </div>
        </div>

        <!-- Notification Test Section -->
        <div class="content-card">
            <div class="card-header">
                <h3>Bildirim Testi</h3>
            </div>
            <div class="quick-actions">
                <button class="action-btn notification-test" data-type="info">
                    <i class="fas fa-info-circle"></i>
                    <span>Bilgi Bildirimi</span>
                </button>
                <button class="action-btn notification-test" data-type="warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Uyarı Bildirimi</span>
                </button>
                <button class="action-btn notification-test" data-type="success">
                    <i class="fas fa-check-circle"></i>
                    <span>Başarı Bildirimi</span>
                </button>
                <button class="action-btn notification-test" data-type="error">
                    <i class="fas fa-times-circle"></i>
                    <span>Hata Bildirimi</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Activity Feed -->
    <div class="content-card">
        <div class="card-header">
            <h3>Son Aktiviteler</h3>
        </div>
        <div class="activity-feed">
            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="activity-content">
                    <p><strong>Yeni kullanıcı kaydoldu:</strong> Ahmet Yılmaz</p>
                    <span class="activity-time">2 dakika önce</span>
                </div>
            </div>

            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="activity-content">
                    <p><strong>Yeni sipariş alındı:</strong> #12348</p>
                    <span class="activity-time">15 dakika önce</span>
                </div>
            </div>

            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="activity-content">
                    <p><strong>Yeni ürün eklendi:</strong> iPhone 15 Pro</p>
                    <span class="activity-time">1 saat önce</span>
                </div>
            </div>

            <div class="activity-item">
                <div class="activity-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="activity-content">
                    <p><strong>Aylık rapor oluşturuldu</strong></p>
                    <span class="activity-time">2 saat önce</span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once __DIR__ . '/../template/footer.php';
?>