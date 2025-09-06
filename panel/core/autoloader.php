<?php
class Autoloader {
    public static function register() {
        spl_autoload_register(function ($class) {
            // Define possible directories where classes might be located
            $directories = [
                __DIR__ . '/',           // core directory
                __DIR__ . '/../classes/', // classes directory
                __DIR__ . '/../models/' 
                       // root directory
            ];
            
            // Try to find the class file in each directory
            foreach ($directories as $directory) {
                $file = $directory . $class . '.php';
                if (file_exists($file)) {
                    require_once $file;
                    return true;
                }
            }
                
            return false;
        });
        
        // Helper fonksiyonları yükle
        $helperFile = __DIR__ . '/helpers.php';
        if (file_exists($helperFile)) {
            require_once $helperFile;
        }
    }
}

// Autoloader'ı başlat
Autoloader::register();
 