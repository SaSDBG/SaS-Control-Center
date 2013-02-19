<?php
use Symfony\Component\HttpFoundation\Request;
use sasCC\Company\Company;
use sasCC\CompanyManagment\Form\CompanyType;
use sasCC\App;

// Print company list
$app->match('/companies/list/print', function (Request $r, App $app){  
    $companies = $app['em']->getRepository('sasCC\Company\Company')
                           ->findAll();
    return $app['twig']->render('company/company.list.print.twig', array("title" => "Betriebsliste", "companies" => $companies));
})
->bind('company_list_print')
->secure('ROLE_WIRTSCHAFT_ADMIN');

// Print company details
$app->match('/companies/details/{id}/print', function (Request $r, App $app, $id){
    $company = $app['em']->find("sasCC\Company\Company", (int)$id);
    if($company === null) return 'Company not Found';
    
    return $app['twig']->render('company/company.print.twig', array("title" => "Betriebsdetails", "company" => $company));
    
})
->bind('company_detail_print')
->secure('ROLE_WIRTSCHAFT_ADMIN');
?>
