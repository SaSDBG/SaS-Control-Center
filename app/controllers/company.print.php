<?php
use Symfony\Component\HttpFoundation\Request;
use sasCC\Company\Company;
use sasCC\CompanyManagment\Form\CompanyType;
use sasCC\App;

// Print company list
$app->match('/companies/list/print', function (Request $r, App $app){  
    $companies = $app['em']->getRepository('sasCC\Company\Company')
                           ->findAll();
    $app['logger.actions']->addInfo(sprintf('User %s (%d) accessed /companies/list/print', $app->user()->getUserName(), $app->user()->getId()));
    return $app['twig']->render('company/company.list.print.twig', array("title" => "Betriebsliste", "companies" => $companies));
})
->bind('company_list_print')
->secure('ROLE_WIRTSCHAFT_PRIV');

// Print company details
$app->match('/companies/details/{id}/print', function (Request $r, App $app, $id){
    $company = $app['em']->find("sasCC\Company\Company", (int)$id);
    if($company === null) return 'Company not Found';
    
    $app['logger.actions']->addInfo(sprintf('User %s (%d) printed details of company with id %d', $app->user()->getUserName(), $app->user()->getId(), $company->getId()));
    return $app['twig']->render('company/company.print.twig', array("title" => "Betriebsdetails - {$company->getName()}", "company" => $company));
    
})
->bind('company_detail_print')
->secure('ROLE_WIRTSCHAFT_PRIV');
?>
