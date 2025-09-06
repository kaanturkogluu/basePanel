<?php
class CSRF
{
    private static $instance = null;
    private $tokenName = '_csrf_token';
    private $tokenExpire = 3600; // 1 saat
    private $session;

    private function __construct()
    {
        $this->session = Session::getInstance();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Token üret
    public function generateToken()
    {
        $token = bin2hex(random_bytes(32));
        $this->session->set($this->tokenName, [
            'value' => $token,
            'time'  => time()
        ]);
        return $token;
    }

    // HTML input
    public function getTokenInputField()
    {
        // Eğer session'da geçerli token varsa onu kullan, yoksa yeni üret
        $existingToken = $this->session->get($this->tokenName);
        
        if ($existingToken && (time() - $existingToken['time']) < $this->tokenExpire) {
            $token = $existingToken['value'];
        } else {
            $token = $this->generateToken();
        }
        
        return '<input type="hidden" name="'.$this->tokenName.'" value="'.$token.'">';
    }

    // Header token (AJAX için)
    public function getTokenHeader()
    {
        return $this->generateToken();
    }

    // Token doğrula (form veya header)
    public function validateToken($token)
    {
        $sessionToken = $this->session->get($this->tokenName);

        if (!$sessionToken) {
            return false;
        }

        if (time() - $sessionToken['time'] > $this->tokenExpire) {
            $this->session->remove($this->tokenName);
            return false;
        }

        if (hash_equals($sessionToken['value'], $token)) {
            // Token'ı hemen silme, request tamamlandıktan sonra sil
            return true;
        }

        return false;
    }

    // AJAX isteğini doğrula
    public function validateAjaxRequest()
    {
        $headers = $this->getRequestHeaders();
        $token = $headers['X-CSRF-Token'] ?? null;
        return $this->validateToken($token);
    }

    // Doğrudan form doğrulama (POST üzerinden)
    public function validateFormRequest()
    {
        $token = $_POST[$this->tokenName] ?? null;
        return $this->validateToken($token);
    }

    // HTTP header’larını al (case insensitive)
    private function getRequestHeaders()
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $headerName = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
                $headers[$headerName] = $value;
            }
        }
        return $headers;
    }
}
