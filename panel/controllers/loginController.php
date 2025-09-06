<?php
/**
 * Login Controller
 * Güvenli giriş işlemleri için controller
 */

require_once __DIR__ . "/../core/autoloader.php";
require_once __DIR__ . "/../core/controllerChecker.php";

if (!RateLimiter::checkLimit('login_attempts', $request->post('username'))) {
    $session->setFlash('error', 'Çok fazla giriş denemesi. Lütfen bekleyin.');
    $router->redirect(Router::view('giris'));
    exit;
}
// Action switch
switch ($action) {
    case 'login':
        $myLogin = new MyLogin();
        $login = $myLogin->checkUser($request->post('username', '', 'string'), $request->post('password', '', 'string'));
        if ($login == false) {
            //Yönlendirme kullanıcı yok
            $router->redirect(Router::view('giris'));
        }

        switch ($login['role']) {
            case 'admin':
                //sessiona veriler alinir , yönlendirme yapılır 
                $session->set('_login', true);
                $session->set('_login_type', 'admin');
                $session->set('user_id', $login['id']);
                $session->set('full_name', $login['fullname']);
                $session->set('role', $login['role']);
                $session->set('ip', $_SERVER['REMOTE_ADDR']);
                $session->set('ua', $_SERVER['HTTP_USER_AGENT']);
                $session->extendSession();
                $router->redirect(Router::view('anasayfa'));
                break;

            case 'personel':
                $session->set('_login', true);
                $session->set('_login_type', 'personel');
                $session->set('user_id', $login['id']);
                $session->set('full_name', $login['fullname']);
                $session->set('role', $login['role']);
                $session->set('ip', $_SERVER['REMOTE_ADDR']);
                $session->set('ua', $_SERVER['HTTP_USER_AGENT']);
                $session->extendSession();
                $router->redirect(Router::view('anasayfa'));
                break;

            default:
                $session->setFlash('error', 'Tanımsız Kullanıcı');
                $router->redirect(Router::view('giris'));
                break;
        }
        break;

   

    default:
        $session->setFlash('error', "Geçersiz işlem!");
        $router->redirect(Router::view('panel/giris'));
        exit;
}
?>