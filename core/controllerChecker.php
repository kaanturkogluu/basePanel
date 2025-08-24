<?php


$router = Router::getInstance();
$session = Session::getInstance();
$request = Request::getInstance();
$csrf = CSRF::getInstance();

// Action kontrolü
$action = $_GET['action'] ?? $_POST['action'] ?? null;


if ($action === null) {
    $session->setFlash('error', "Geçersiz istek atıldı!");
    $router->redirect(Router::view('panel/404'));
    exit;
}
// CSRF: yalnızca POST isteklerinde doğrula (state-changing)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$csrf->validateFormRequest()) {
        $session->setFlash('error', 'Güvenlik hatası: Lütfen sayfayı yenileyip tekrar deneyiniz.');
        $referer = $_SERVER['HTTP_REFERER'] ?? Router::view('anasayfa');
        $router->redirect($referer);
        exit;
    }
}


