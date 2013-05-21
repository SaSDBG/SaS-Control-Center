<?php
use Symfony\Component\HttpFoundation\Request;
use sasCC\Company\Company;
use sasCC\CompanyManagment\Form\CompanyType;
use sasCC\App;

// Print company list
$app->match('/pupils/list/print', function (Request $r, App $app){  
    $pupils = $app['em']->getRepository('sasCC\Pupil\Pupil')
                           ->findAll(); 
    $app['logger.actions']->addInfo(sprintf('User %s (%d) accessed /pupils/list/print', $app->user()->getUserName(), $app->user()->getId()));
    return $app['twig']->render('pupil/pupil.list.print.twig', array("title" => "Schülerliste", "pupils" => $pupils));
})
->bind('pupil_list_print')
->secure('ROLE_WIRTSCHAFT_ADMIN');

// Print company details
$app->match('/pupils/details/{id}/print', function (Request $r, App $app, $id){
    $pupil = $app['em']->find("sasCC\Pupil\Pupil", (int)$id);
    if($pupil === null) return 'Company not Found';
    
    $app['logger.actions']->addInfo(sprintf('User %s (%d) printed details of pupil with id %d', $app->user()->getUserName(), $app->user()->getId(), $pupil->getId()));
    return $app['twig']->render('pupil/pupil.print.twig', array("title" => "Schülerdetails", "pupil" => $pupil));
    
})
->bind('pupil_detail_print')
->secure('ROLE_WIRTSCHAFT_ADMIN');
?>
