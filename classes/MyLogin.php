<?php
require_once __DIR__ . '/../core/autoloader.php';


class MyLogin
{
    private $session;
    private $router;

    private $user;

    public function __construct()
    {
        $this->session = Session::getInstance();
        $this->router = Router::getInstance();
        $this->user = new Users();
    }

    public function checkUser($username, $password)
    {
        // Kullanıcıyı mail ile ara
        $userData = $this->user->get(['*'], ['user_name' => $username]);

        // Kullanıcı yoksa
        if (!$userData || count($userData) === 0) {
            $this->session->setFlash('error', 'Kullanıcı bulunamadı');
            return false;
        }

        // Veritabanından ilk kullanıcı kaydı
        $user = $userData[0];

        // Şifre doğrulama
        if (password_verify($password, $user['password'])) {
            // Başarılı giriş
            return $userData[0];
          
        } else {
            // Şifre yanlış
            $this->session->setFlash('error', 'Şifre hatalı');
            return false;
        }
    }


    public function logout()
    {
        $this->session->destroy();
        $this->router->redirect(Router::view('panel/giris'));
    }
}




?>