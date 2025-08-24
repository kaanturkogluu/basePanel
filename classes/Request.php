<?php
require_once __DIR__ . "/Session.php";
class Request
{
    private static $instance = null;
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

    // POST isteği al
    public function post(string $key, $default = null, string $type = 'string')
    {

        $data = $_POST[$key] ?? $default;
        return $this->sanitize($data, $type);
    }

    // GET isteği al
    public function get(string $key, $default = null, string $type = 'string')
    {
        $data = $_GET[$key] ?? $default;
        return $this->sanitize($data, $type);
    }

    // Input sanitize
    private function sanitize($data, string $type)
    {

        if(empty($data)){
            return $data;
        }

        if (is_array($data)) {
            return array_map(fn($v) => $this->sanitize($v, $type), $data);
        }


        $value = $data;


        // XSS önleme (input düzeyi)
        $data = $this->antiXss($data);

        // Komut enjeksiyon
        $data = $this->antiCommandInjection($data);

        // Tip kontrolü
        switch ($type) {
            case 'int':
                return (int) $data;
            case 'float':
                return (float) $data;
            case 'email':
                return filter_var($data, FILTER_VALIDATE_EMAIL) ?: null;
            case 'string':
            default:
                return $data;
        }
    }

    // XSS input temizliği (script ve event kaldır)
    private function antiXss($data)
    {
        if (!empty($data)) {
            $data = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $data);
            $data = preg_replace('/on\w+=".*?"/i', '', $data);
        }

        return $data;
    }

    // Komut enjeksiyon engelleme
    private function antiCommandInjection($data)
    {
        return escapeshellcmd($data);
    }


}
