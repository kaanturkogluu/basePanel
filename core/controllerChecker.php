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
// CSRF 
switch ($_SERVER['REQUEST_METHOD']) {

    case 'POST':

        if (!$csrf->validateFormRequest($request->post('_csrf_token'))) {
            
            $session->setFlash('error','Sayfayı Yenileyip Tekrar Deneyiniz');
        }
        break;

    case 'GET':
        if (!$csrf->validateFormRequest($request->get('_csrf_token'))) {
            $session->setFlash('error','Sayfayı Yenileyip Tekrar Deneyiniz');
        }
        break;

}
 

