<?php 
//Müşteri için panel kullanılacaksa eğer , auth içerisinde user_type ayarlanmalı
require_once __DIR__."/../core/auth.php";
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="<?=Router::baseUrl(). 'assets/css/'?>style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- Global Notification System -->
    <div class="notification-system" id="notificationSystem">
        <!-- Notifications will be dynamically added here -->
    </div>
    <div class="admin-container">