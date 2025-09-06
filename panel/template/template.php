<?php
require_once __DIR__ . '/../core/autoloader.php';


$router = Router::getInstance();
$session = Session::getInstance();




//auto loader 
// giriş kontrolleri

require_once 'header.php';
require_once "notifications.php";
require_once 'sidebar.php';
require_once 'navbar.php';



?>