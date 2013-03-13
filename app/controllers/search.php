<?php

use Symfony\Component\HttpFoundation\Request;

// Ultimate search for everything powered by Maxe
$app->match('/search', function(Request $r) use ($app) {
    
   return $app['twig']->render('search/search.twig', array("title" => "Magic search"));
})
->bind('search')
->secure('ROLE_ADMIN');
?>
