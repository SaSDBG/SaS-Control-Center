<?php
//use Silex\Application;
use \sasCC;

define('ROOT', __DIR__.'/..');
$loader = require_once ROOT.'/vendor/autoload.php';
$loader->add('sasCC', ROOT.'/src/');

$app = new sasCC\App;
require_once 'services.php';
require_once 'controllers.php';
$app->boot();

header('Content-Type: text/html; charset=utf-8');
return $app;

?>
