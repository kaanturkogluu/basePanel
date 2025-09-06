<?php

$user = $session->get('_login_type');
if ($user != 'admin') {

    $session->setFlash('error','Yetkisiz Erişim Denemesi');
    $router->redirect(Router::view('giris'));

}
?>