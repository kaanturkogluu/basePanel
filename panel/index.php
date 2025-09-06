<?php 

require_once __DIR__ . "/core/autoloader.php";
$router = Router::getInstance();   

 
$router->redirect(Router::view('panel/anasayfa'));

?>