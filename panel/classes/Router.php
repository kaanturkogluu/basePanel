<?php
require_once __DIR__."/../core/config.php";
class Router
{
    public static $instance = null;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Router();
        }
        return self::$instance;
    }
    public function get($path, $callback)
    {
        if ($_SERVER['REQUEST_URI'] == $path) {
            $callback();
        } else {
            echo "404 Not Found";
        }
    }
    public static function controllers($controllerName)
    {
        return self::baseUrl() . "/controllers/" . htmlspecialchars(rtrim($controllerName, ".php")) . ".php";
    }
    public static function baseUrl()
    {
        return "http://" . $_SERVER['HTTP_HOST'] . "/" .$GLOBALS['app_config']['base_file_name'] ."/";
    }
    public static function getUrl()
    {
        return "http://" . $_SERVER['HTTP_HOST'] . "/" . $_SERVER["SCRIPT_NAME"];
    }

    public static function view($url, $params = [])
    {
        $link = explode("/", $url);
        $baseUrl = self::baseUrl();
        if ($link[0] == "panel") {
            unset($link[0]);

            $returnLink = $baseUrl . $GLOBALS['app_config']['base_panel_folder_name'].implode("/", $link) ;
            if (!empty($params)) {
                $returnLink .= "?" . http_build_query($params);
            }
            return $returnLink;
        }
        $returnLink = $baseUrl . "pages/" . $url;
        if (!empty($params)) {
            $returnLink .= "?" . http_build_query($params);
        }
        return $returnLink;
    }

    public static function buildPanelUrl($url, $params = [])
    {
        $baseUrl = self::baseUrl() . "panel/";
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        return $baseUrl . $url;
    }

    public function redirect($url)
    {
        echo '<meta http-equiv="refresh" content="0;url=' . $url . '">';
        exit;
    }
}
?>