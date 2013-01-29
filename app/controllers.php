<?php
use Symfony\Component\HttpFoundation\Request;
$app->get('/', function(Request $r){
    return 'hi';
})->bind('homepage');

?>
