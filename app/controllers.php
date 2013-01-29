<?php
use Symfony\Component\HttpFoundation\Request;

$app->get('/', function(Request $r) use ($app) {
    return $app->render('home.html.twig');
})->bind('homepage');

?>
