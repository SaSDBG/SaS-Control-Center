<?php
use Symfony\Component\HttpFoundation\Request;
use sasCC\Company\Company;
use sasCC\CompanyManagment\Form\CompanyType;
use sasCC\App;

$app->get('/', function(Request $r) use ($app) {
    return $app->redirect($app->path('home'));
});

$app->get('/cc', function(Request $r) use ($app) {
    return $app->render('home.html.twig', array("title" => "SaS CP"));
})->bind('home');

$app->match('/companies/add', function(Request $r) use ($app) {   
    return handleCompanyEdit(
            "Betrieb hinzufügen",
            new Company(),
            array(),
            $app
     );

})->bind('add_company');

$app->match('/cc/companies/list', function(Request $r, App $app) {
    $companies = $app['em']->getRepository('sasCC\Company\Company')
                           ->findAll();
    return $app['twig']->render('company.list.html.twig', array("title" => "Betriebsliste", "companies" => $companies));
    
})->bind('list_companies');

$app->match('/cc/companies/edit/{id}', function(Request $r, App $app,  $id) {
    $company = $app['em']->find("sasCC\Company\Company", (int) $id);
    if($company === null) return 'Company not Found';
    return handleCompanyEdit(
            "Betrieb bearbeiten",
            $company,
            array('edited' => true),
            $app
     );
})->bind('edit_company');

function handleCompanyEdit($title, Company $data, $pathArgs, App $app) {
    $form = $app['form.factory']->create(new CompanyType(), $data);
    $pathArgs = array_merge(array("success" => true), $pathArgs);
 
    if($app['request']->getMethod() == 'POST') {
        $form->bindRequest($app['request']);
        if ($form->isValid()) {
            $app['em']->persist($data);
            $app['em']->flush();
            return $app->redirect($app->path('add_company', $pathArgs));
        }
    }
    
    return $app['twig']->render('company.add.html.twig', array('form' => $form->createView(), "title" => $title));
}

$app->match('/cc/companies/delete/{id}', function(Request $r, App $app, $id) {
    return $app->redirect($app->path('home'));
    
    $company = $app['em']->find("sasCC\Company\Company", (int) $id);
    if($company === null) return 'Invalid company';

    
    $form = $app['form.factory']->createBuilder('form',['sure' => false])
                ->add('sure', 'checkbox', array(
                   'label' => 'Ich bin mir sicher.',
                   'required' => false,
                ))->getForm();
 
    if($app['request']->getMethod() == 'POST') {
        $form->bindRequest($app['request']);
        if ($form->isValid() && $form->getData()['sure'] === true) {
            $app['em']->remove($company);
            $app['em']->flush();
            return $app->redirect($app->path('home', array('deleted_company' => 'true')));
        }
    }
    
    return $app['twig']->render('company.delete.html.twig', array('form' => $form->createView(), "title" => 'Betrieb löschen'));
})->bind('delete_company');


$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})->bind('login');

?>
