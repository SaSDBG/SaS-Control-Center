<?php

use Symfony\Component\HttpFoundation\Request;

$app->get('/', function(Request $r) use ($app) {
            return $app->render('home.html.twig', array("title" => "SaS CP"));
        })->bind('home');

// Login form
$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
        'title' => "Einloggen"
    ));
})
->bind('login');
        
require_once 'controllers/company.php';
require_once 'controllers/company.print.php';
require_once 'controllers/user.php';
require_once 'controllers/search.php';
require_once 'controllers/api.php';
require_once 'controllers/pupil.php';
require_once 'controllers/pupil.print.php';
require_once 'controllers/room.php';

$app->error(function (\Exception $e, $code) use ($app) {
            if ($app['debug'])
            {
                return;
            }

            $page = "000";
          
            switch ($code)
            {
                case 404:
                    $page = "404";
                    break;
                case 403:
                    $page = "403";
                    break;
                default:
                    $page = "000";
                    break;
            }

            $page .= '.html.twig';

            return new \Symfony\Component\HttpFoundation\Response($app['twig']->render($page), $code);
        });


?>
