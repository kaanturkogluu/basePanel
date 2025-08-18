<?php 
require_once __DIR__ . '/../core/autoloader.php';

$router = Router::getInstance();
echo "Base URL :  "; 
echo Router::baseUrl();
echo "<br>";

echo "Get URL : ";
echo Router::getUrl();
echo "<br>";
echo " Controolers : "; 
echo Router::controllers("controllers"); 
echo "<br>";
echo " View : ";

echo Router::view('panel/giris');


?>