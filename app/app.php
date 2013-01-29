<?php
use Silex\Application;
define('ROOT', '..');
$loader = require_once ROOT.'/vendor/autoload.php';
$loader->add('SaSCC', ROOT.'/src/');

$app = new Application;
require_once 'services.php';
require_once 'controllers.php';

return $app;

?>
