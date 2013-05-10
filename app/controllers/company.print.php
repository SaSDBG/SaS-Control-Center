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
->secure('ROLE_WIRTSCHAFT_PRIV');

// Print company details
$app->match('/pupils/details/{id}/print', function (Request $r, App $app, $id){
    $company = $app['em']->find("sasCC\Company\Company", (int)$id);
    if($company === null) return 'Company not Found';
    
    $app['logger.actions']->addInfo(sprintf('User %s (%d) printed details of company with id %d', $app->user()->getUserName(), $app->user()->getId(), $company->getId()));
    return $app['twig']->render('company/company.print.twig', array("title" => "Betriebsdetails", "company" => $company));
    
})
->bind('company_detail_print')
->secure('ROLE_WIRTSCHAFT_PRIV');
?>
