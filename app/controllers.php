<?php
use Symfony\Component\HttpFoundation\Request;

$app->get('/', function(Request $r) use ($app) {
    return $app->render('home.html.twig', array("title" => "SaS CP"));
})->bind('home');

require_once 'controllers/company.php';
require_once 'controllers/user.php';


?>
