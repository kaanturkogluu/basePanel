<?php
// Session kontrolü
if (!isset($session) || !($session instanceof Session)) {
    $session = Session::getInstance();
}


// Flash mesajlarını al ve temizle (eski Session sınıfı yapısına göre)
$flashMessages = method_exists($session, 'getFlash') ? $session->getFlash() : [];

// Flash mesajları varsa JavaScript ile göster

if (!empty($flashMessages)) {
    echo '<script>';
    echo 'window.addEventListener("DOMContentLoaded", function() {';
    
    foreach ($flashMessages as $flash) {
        // Mesaj tipini belirle (eski yapıda type doğrudan geliyor)
        $type = isset($flash['type']) ? strtolower($flash['type']) : 'info';
        
        // Türkçe başlık eşleştirmesi
        $titleMap = [
            'success' => 'Başarılı',
            'warning' => 'Uyarı',
            'error' => 'Hata',
            'danger' => 'Hata',
            'info' => 'Bilgi',
        ];
        
        $title = isset($titleMap[$type]) ? $titleMap[$type] : 'Bilgi';
        $message = isset($flash['message']) ? $flash['message'] : '';
        $jsType = $type === 'danger' ? 'error' : $type;
        
        // AdminPanel notification sistemini kullan
        echo 'if (window.AdminPanel && typeof window.AdminPanel.showNotification === "function") {';
        echo 'window.AdminPanel.showNotification(' . json_encode($title) . ', ' . json_encode($message) . ', ' . json_encode($jsType) . ', 5000);';
        echo '} else {';
        echo 'console.warn("AdminPanel notification system not available");';
        echo '}';
    }
    
    echo '});';
    echo '</script>';
}
?>

