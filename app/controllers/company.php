<?php
use Symfony\Component\HttpFoundation\Request;
use sasCC\Company\Company;
use sasCC\CompanyManagment\Form\CompanyType;
use sasCC\App;

// List companies
$app->match('/companies/list', function(Request $r, App $app) {
    $companies = $app['em']->getRepository('sasCC\Company\Company')
                           ->findAll();
    $app['logger.actions']->addInfo(sprintf('User %s (%d) accessed /companies/list', $app->user()->getUserName(), $app->user()->getId()));
    return $app['twig']->render('company.list.html.twig', array("title" => "Betriebsliste", "companies" => $companies));
    
})
->bind('list_companies')
->secure('ROLE_WIRTSCHAFT_PRIV');

// Add company
$app->match('/companies/add', function(Request $r) use ($app) {   
    return handleCompanyEdit(
            "Betrieb hinzufügen",
            new Company(),
            array(),
            $app,
            sprintf('Betrieb mit ID %%d wurde von %s (%d) angelegt', $app->user()->getUserName(), $app->user()->getId()),
            'add_company'
     );

})
->bind('add_company')
->secure('ROLE_WIRTSCHAFT_CREATE');

// Get company details
$app->match('/companies/{id}/details', function(Request $r, App $app, $id){
    $company = $app['em']->find("sasCC\Company\Company", (int)$id);
    if($company === null) return 'Company not Found';
    $app['logger.actions']->addInfo(sprintf('User %s (%d) accessed /companies/details/%d', $app->user()->getUserName(), $app->user()->getId(), $id));
    return $app['twig']->render('company.details.html.twig', array("title" => "Betriebsdetails", "company" => $company));
})
->bind('company_detail')
->secure('ROLE_WIRTSCHAFT_PRIV');

// Edit company
$app->match('/companies/{id}/edit', function(Request $r, App $app,  $id) {   
    $company = $app['em']->find("sasCC\Company\Company", (int) $id);
    if($company === null) return 'Company not Found';
    return handleCompanyEdit(
            "Betrieb bearbeiten",
            $company,
            array('edited' => true, 'success' => true),
            $app,
            sprintf('Betrieb mit ID %%d wurde von %s (%d) bearbeitet', $app->user()->getUserName(), $app->user()->getId()),
            'list_companies'
     );
})
->bind('edit_company')
->secure('ROLE_WIRTSCHAFT_PRIV');

function handleCompanyEdit($title, Company $data, $pathArgs, App $app, $logMsg, $redirectRoute) {
    $form = $app['form.factory']->create(new CompanyType(), $data);
    $pathArgs = array_merge(array("success" => true), $pathArgs);
 
    if($app['request']->getMethod() == 'POST') {
        $form->bindRequest($app['request']);
        if ($form->isValid()) {
            $app['em']->persist($data);
            $app['em']->flush();
            $app['logger.actions']->addInfo(sprintf($logMsg, $data->getId()));
            return $app->redirect($app->path($redirectRoute, $pathArgs));
        }
    }
    
    return $app['twig']->render('company.add.html.twig', array('form' => $form->createView(), "title" => $title));
}

// Delete company
$app->match('/companies/{id}/delete', function(Request $r, App $app, $id) {  
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
            $app['logger.actions']->addInfo(sprintf('Betrieb mit ID %d wurde von %s (%d) gelöscht.', $company->getId(), $app->user()->getUserName(), $app->user()->getId()));
            return $app->redirect($app->path('list_companies', array('deleted' => 'true', 'success' => 'true')));
        }
    }
    
    return $app['twig']->render('company.delete.html.twig', array('form' => $form->createView(), "title" => 'Betrieb löschen'));
})
->bind('delete_company')
->secure('ROLE_WIRTSCHAFT_ADMIN');

// Mark company to delete
$app->match('/companies/{id}/delete/mark', function(Request $r, App $app, $id) {
    $company = $app['em']->find("sasCC\Company\Company", (int) $id);
    if($company === null) return 'Invalid company';
    
    $val = 0;
    
    if($company->getIsMarkedToDelete())
    {
        $company->setIsMarkedToDelete(0);
        $val = 0;
    }
    else 
    {
        $company->setIsMarkedToDelete(1);
        $val = 1;
    }
    
    $app['em']->flush();
    return $val;
})
->bind('company_delete_mark')
->secure('ROLE_WIRTSCHAFT_PRIV');
?>
