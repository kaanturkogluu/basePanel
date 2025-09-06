<?php
require_once __DIR__ . "/Router.php";

class Session
{
    private static $instance = null;
    private $sessionLifetime = 600; // 15 dakika (saniye cinsinden)
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

        // Tüm sayfalarda session kontrolü yap
        $this->checkSession();
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
        // Session timeout kontrolü
        if (!$this->checkSessionTimeout()) {
            // Session süresi dolmuş, login sayfasına yönlendir
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                // AJAX isteği ise JSON response döndür
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Session expired', 'redirect' => 'login']);
                exit;
            } else {
                // Normal sayfa isteği ise login'e yönlendir
                header('Location: ' . Router::baseUrl() . 'pages/giris.php');
                exit;
            }
        }

        // Belirli aralıklarla ID yenile
        if (!isset($_SESSION['_created'])) {
            $_SESSION['_created'] = time();
        } elseif (time() - $_SESSION['_created'] > $this->regenerateTime) {
            $this->regenerate();
        }
        
        // Her sayfa yüklendiğinde session süresini uzat
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
        $_SESSION['_last_activity'] = time(); // Activity timestamp güncelle
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
        // Session aktif mi kontrol et
        if (session_status() === PHP_SESSION_ACTIVE) {
            $this->clear();
            session_destroy();
        }
        
        // Cookie'yi temizle
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        // Session array'ini temizle
        $_SESSION = [];
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
            // Session timeout - mesajı cookie'ye kaydet
            setcookie('session_timeout_message', 'Oturum süresi doldu. Lütfen tekrar giriş yapın.', time() + 60, '/', '', false, true);
            setcookie('session_timeout_type', 'warning', time() + 60, '/', '', false, true);
            
            // Session verilerini temizle
            $_SESSION = [];
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time() - 3600, '/');
            }
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