<?php
require_once __DIR__ . "/../classes/Session.php";
require_once __DIR__ . "/../classes/Router.php";
$session = Session::getInstance();
$router = Router::getInstance();
$session->destroy();

$router->redirect(Router::view('giris'))
    ?>