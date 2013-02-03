<?php
use Symfony\Component\HttpFoundation\Request;
use sasCC\Company\Company;
use sasCC\CompanyManagment\Form\CompanyType;
use sasCC\App;

// Company list
$app->match('/companies/list', function(Request $r, App $app) {
    if(!$app['security']->isGranted('ROLE_WIRTSCHAFT_PRIV')) return $app['twig']->render("403.html.twig");
    
    $companies = $app['em']->getRepository('sasCC\Company\Company')
                           ->findAll();
    return $app['twig']->render('company.list.html.twig', array("title" => "Betriebsliste", "companies" => $companies));
    
})->bind('list_companies');


// Add company
$app->match('/companies/add', function(Request $r) use ($app) {   
    if(!$app['security']->isGranted('ROLE_WIRTSCHAFT_CREATE')) return $app['twig']->render("403.html.twig");
    return handleCompanyEdit(
            "Betrieb hinzufügen",
            new Company(),
            array(),
            $app
     );

})->bind('add_company');

// Edit company
$app->match('/companies/edit/{id}', function(Request $r, App $app,  $id) {
    if(!$app['security']->isGranted('ROLE_WIRTSCHAFT_PRIV')) return $app['twig']->render("403.html.twig");
    
    $company = $app['em']->find("sasCC\Company\Company", (int) $id);
    if($company === null) return 'Company not Found';
    return handleCompanyEdit(
            "Betrieb bearbeiten",
            $company,
            array('edited' => true, "success" => true),
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
            return $app->redirect($app->path('list_companies', $pathArgs));
        }
    }
    
    return $app['twig']->render('company.add.html.twig', array('form' => $form->createView(), "title" => $title));
}

// Delete company
$app->match('/companies/delete/{id}', function(Request $r, App $app, $id) {
    if(!$app['security']->isGranted('ROLE_WIRTSCHAFT_ADMIN')) return $app['twig']->render("403.html.twig");
    
    
    $company = $app['em']->find("sasCC\Company\Company", (int) $id);
    if($company === null) return 'Invalid company';

    
    $form = $app['form.factory']->createBuilder('form',['sure' => false])
                ->add('sure', 'checkbox', array(
                   'label' => 'Ich bin mir sicher',
                   'required' => true,
                ))->getForm();
 
    if($app['request']->getMethod() == 'POST') {
        $form->bindRequest($app['request']);
        if ($form->isValid() && $form->getData()['sure'] === true) {
            $app['em']->remove($company);
            $app['em']->flush();
            return $app->redirect($app->path('list_companies', array('deleted' => 'true', "success" => true)));
        }
    }
    
    return $app['twig']->render('company.delete.html.twig', array('form' => $form->createView(), "title" => 'Betrieb löschen'));
})->bind('delete_company');


$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
        'title' => "Einloggen"
    ));
})->bind('login');
?>
