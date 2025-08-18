<?php

// Veritabanı yapılandırması
$GLOBALS['db_config'] = [
    'default' => [
        'host' => 'localhost',
        'dbname' => 'panel',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4'
    ],
    'second_db' => [
        'host' => 'localhost',
        'dbname' => 'ikinci_veritabani',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4'
    ]
];

$GLOBALS['mail_config'] = [
    'host' => 'smtp.hostinger.com',
    'username' => 'raquun@raquun.net',
    'password' => 'e^3|oI#8',
    'port' => 465,
    'secure' => 'ssl',
    'from_name' => 'Mumdekor',
    'admin_mail' => 'kaantrrkoglu@gmail.com'
];

// Uygulama yapılandırması
$GLOBALS['app_config'] = [
    'debug' => true, //Geliştirici Modu 
    'timezone' => 'Europe/Istanbul',
    'session_lifetime' => 120,
    'websocket_port' => 8080, // WebSocket sunucusu için port numarası
    'livechat' => false,// chat aktif olacak mı ?
    'base_file_name' => 'motor', 
    'base_panel_folder_name'=>''// panelin olduğu dosya adi
];



// Hata raporlama
if ($GLOBALS['app_config']['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Zaman dilimi ayarı
date_default_timezone_set($GLOBALS['app_config']['timezone']);

?>