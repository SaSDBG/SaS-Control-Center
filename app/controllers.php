<?php
use Symfony\Component\HttpFoundation\Request;

$app->get('/', function(Request $r) use ($app) {
    return $app->render('home.html.twig', array("title" => "SaS CP"));
})->bind('homepage');

$app->get('/test', function(Request $r) use ($app) {
    $app['companies.schemaManager']->createTables();
    return 'success!!';
});

?>
