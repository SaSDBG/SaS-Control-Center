<?php
use Symfony\Component\HttpFoundation\Request;
use sasCC\Company\Company;
use sasCC\CompanyManagment\Form\CompanyType;
use sasCC\Pupil\Pupil;
use sasCC\Pupil\SchoolClass;
use sasCC\Company\AssignmentConstraints;

$app->get('/', function(Request $r) use ($app) {
    return $app->render('home.html.twig', array("title" => "SaS CP"));
})->bind('home');

$app->match('/companies/add', function(Request $r) use ($app) {   
    $company = new Company();
    $form = $app['form.factory']->create(new CompanyType(), $company);
 
    if($r->isMethod('POST')) {
        $form->bindRequest($r);
        if ($form->isValid()) {
            $app['em']->persist($company);
            $app['em']->flush();
            return $app->redirect($app->path('add_company', array("success" => true)));
        }
    }
    
    return $app['twig']->render('company.add.html.twig', array('form' => $form->createView(), "title" => "Betrieb hinzufügen", "r" => $r));

})->bind('add_company');

$app->match('/companies/list', function(Request $r) use ($app) {
    $companies = $app['em']->getRepository('sasCC\Company\Company')
                           ->findAll();
    return $app['twig']->render('company.list.html.twig', array("title" => "Betriebsliste", "companies" => $companies));
    
})->bind('list_companies');

?>
