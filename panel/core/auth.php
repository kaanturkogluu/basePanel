<?php
/**
 * Kimlik doğrulama ve yetkilendirme işlemleri
 */

require_once __DIR__ . "/../core/autoloader.php";

$session = Session::getInstance();
$router = Router::getInstance();
$csrf = CSRF::getInstance();




if (!$session->isLoggedIn()) {
    // Kullanıcı giriş yapmamış → login sayfasına yönlendir
    $session->setFlash('warning', 'Lütfen Oturum Açın');
    $router->redirect(Router::view('giris'));
    exit;
}
if (
    $session->get('ip') !== $_SERVER['REMOTE_ADDR'] ||
    $session->get('ua') !== $_SERVER['HTTP_USER_AGENT']
) {

    $session->destroy();
    $session->setFlash('error', 'Günvilmeyen Tarayıcı Erişimi');
    $router->redirect(Router::view('giris'));
    exit;
}
?>