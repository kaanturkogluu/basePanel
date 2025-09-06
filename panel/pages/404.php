<?php
// 404 durum kodunu gönder
 

require_once __DIR__ . '/../template/template.php';
?>

<div class="dashboard-content">
    <div class="content-card" style="text-align:center; padding: 60px 30px;">
        <div style="font-size: 72px; font-weight: 800; color: #667eea; line-height: 1;">404</div>
        <h2 style="margin: 10px 0 8px;">Sayfa bulunamadı</h2>
        <p style="color:#666; margin-bottom: 24px;">Aradığınız sayfa taşınmış, silinmiş ya da hiç var olmamış olabilir.</p>

        <div style="display:flex; gap: 12px; justify-content:center; flex-wrap: wrap;">
            <a href="<?= Router::view('panel/anasayfa') ?>" class="action-btn" style="text-decoration:none; min-width: 220px;">
                <i class="fas fa-home"></i>
                <span>Ana sayfaya dön</span>
            </a>
            <a href="javascript:history.back()" class="action-btn" style="text-decoration:none; min-width: 220px;">
                <i class="fas fa-arrow-left"></i>
                <span>Önceki sayfaya dön</span>
            </a>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../template/footer.php';
?>