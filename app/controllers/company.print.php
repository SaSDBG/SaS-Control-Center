<?php
use Symfony\Component\HttpFoundation\Request;
use sasCC\Company\Company;
use sasCC\CompanyManagment\Form\CompanyType;
use sasCC\App;

// Print company list
$app->match('/companies/list/print', function (Request $r, App $app){  
    $companies = $app['em']->getRepository('sasCC\Company\Company')
                           ->findAll();
    return $app['twig']->render('company/company.print.twig', array("title" => "Betriebsliste", "companies" => $companies));
})
->bind('company_list_print')
->secure('ROLE_WIRTSCHAFT_PRIV');
?>
