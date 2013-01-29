<?php
//use Silex\Application;
use \sasCC;

define('ROOT', '..');
$loader = require_once ROOT.'/vendor/autoload.php';
$loader->add('sasCC', ROOT.'/src/');

$app = new sasCC\App;
require_once 'services.php';
require_once 'controllers.php';

return $app;

?>
