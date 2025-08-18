<?php
require_once __DIR__ . "/Router.php";

class Session
{
    private static $instance = null;
    private $sessionLifetime = 900; // 15 dakika (saniye cinsinden)
    private $regenerateTime = 300; // 5 dakikada bir session ID yenileme
    private $lastActivityTime;




    /**
     * Singleton için private constructor
     */
    private function __construct()
    {
        // Session güvenlik ayarları
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
        ini_set('session.cookie_samesite', 'Strict');

        // Session başlat
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->lastActivityTime = time();

        // Sadece panel sayfalarında oturum kontrolü yap
        if (strpos($_SERVER['SCRIPT_NAME'], '/panel/') !== false) {
            $this->checkSession();
        }
    }
    public function isLoggedIn()
    {
        return $this->has('user_id');
    }
    /**
     * Singleton instance'ı al
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function checkSession()
    {
        if (!$this->checkSessionTimeout()) {

            exit;
        }

        // Belirli aralıklarla ID yenile
        if (!isset($_SESSION['_created'])) {
            $_SESSION['_created'] = time();
        } elseif (time() - $_SESSION['_created'] > $this->regenerateTime) {
            $this->regenerate();
        }
        $this->extendSession();
    }
    /**
     * Session ID'yi yenile
     */
    private function regenerate()
    {
        session_regenerate_id(true);
        $_SESSION['_created'] = time();
    }

    /**
     * Session değeri ata
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Session değeri al
     */
    public function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Session değeri var mı kontrol et
     */
    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Session değerini sil
     */
    public function remove($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
            return true;
        }
        return false;
    }

    /**
     * Tüm session'ı temizle
     */
    public function clear()
    {
        session_unset();
    }

    /**
     * Session'ı sonlandır
     */
    public function destroy()
    {
        $this->clear();
        session_destroy();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
    }

    /**
     * Flash mesaj ata
     */
    public function setFlash($type, $message)
    {
        if (!isset($_SESSION['flash']) || !is_array($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }

        $_SESSION['flash'][] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /**
     * Flash mesaj al ve sil
     */
    public function getFlash()
    {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return [];
    }



    /**
     * Session süresini uzat
     */
    public function extendSession()
    {
        $_SESSION['_last_activity'] = time();
    }

    /**
     * Session süresini kontrol et
     */
    public function checkSessionTimeout()
    {
        if (isset($_SESSION['_last_activity']) && (time() - $_SESSION['_last_activity'] > $this->sessionLifetime)) {
            $this->destroy();
            return false;
        }
        return true;
    }

    /**
     * Singleton için clone'lamayı engelle
     */
    private function __clone()
    {
    }

    /**
     * Singleton için unserialize'i engelle
     * @throws Exception
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }
}