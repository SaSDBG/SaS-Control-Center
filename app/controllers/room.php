<?php
use Symfony\Component\HttpFoundation\Request;
use sasCC\Company\Company;
use sasCC\CompanyManagment\Form\CompanyType;
use sasCC\App;

// List rooms
$app->match('/rooms/list/print', function(Request $r, App $app) {
    $companies = $app['em']->getRepository('sasCC\Company\Company')
                           ->findAll();
    $app['logger.actions']->addInfo(sprintf('User %s (%d) accessed /rooms/list', $app->user()->getUserName(), $app->user()->getId()));
    return $app['twig']->render('room/room.list.print.twig', array("title" => "Raumliste", "companies" => $companies)); 
})
->bind('room_list_print')
->secure('ROLE_WIRTSCHAFT_PRIV');
?>
