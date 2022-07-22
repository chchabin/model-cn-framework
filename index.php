<?php
// constantes
define('BASE_URL', dirname(__FILE__));
define("ROOT", dirname(__DIR__));
const DS = DIRECTORY_SEPARATOR;
require 'App.php';

$app=new App();
$app->run();