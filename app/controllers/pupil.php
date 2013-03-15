<?php
use Symfony\Component\HttpFoundation\Request;
use sasCC\Pupil\Pupil;
use sasCC\Pupil\PupilTypeFull;
use sasCC\App;

// Add Pupil
$app->match('/pupils/add', function(Request $r) use ($app) {   
    return handlePupilEdit(
            "Schüler hinzufügen",
            new Pupil(),
            array(),
            $app,
            sprintf('Schüler mit ID %%d wurde von %s (%d) angelegt', $app->user()->getUserName(), $app->user()->getId()),
            'pupil_add'
     );

})
->bind('pupil_add')
->secure('ROLE_WIRTSCHAFT_PRIV');


function handlePupilEdit($title, Pupil $data, $pathArgs, App $app, $logMsg, $redirectRoute) {
    $form = $app['form.factory']->create(new PupilTypeFull(), $data);
    $pathArgs = array_merge(array("success" => true), $pathArgs);
 
    if($app['request']->getMethod() == 'POST') {
        $form->bindRequest($app['request']);
        
        $data->setFirstWish($app['em']->find("sasCC\Company\Company", (int)$data->getFirstWish()));
        $data->setSecondWish($app['em']->find("sasCC\Company\Company", (int)$data->getSecondWish()));
        $data->setThirdWish($app['em']->find("sasCC\Company\Company", (int)$data->getThirdWish()));
        
        if ($form->isValid()) {
            $app['em']->persist($data);
            $app['em']->flush();
            $app['logger.actions']->addInfo(sprintf($logMsg, $data->getId()));
            return $app->redirect($app->path($redirectRoute, $pathArgs));
        }
    }
    
    return $app['twig']->render('pupil/pupil.add.twig', array('form' => $form->createView(), "title" => $title));
}


// Return company suggestions
// Add Pupil
$app->match('/pupils/add/companysuggestions', function(Request $r) use ($app) {   
    
    $searchQuery = $r->get("q");
         
    // If no query is passed
    if($searchQuery == NULL)
    {
        $companies = $app['em']->getRepository('sasCC\Company\Company')
                               ->findAll();
    }
    // If query is passed
    else
    {
        $query = $app['em']->createQuery("SELECT u FROM sasCC\Company\Company u WHERE ( 
            u.name LIKE :query OR
            u.id LIKE :query           
            )");
        $query->setParameter("query", "%$searchQuery%");
        
        $companies = $query->getResult();
    }
    
    $data = array();
    
    foreach($companies as $company)
    {
        $tokens = array();
        $tokens[] = $company->getName();
        $tokens[] = $company->getId();

        $data[] = array(
            "id" => $company->getId(),
            "name" => $company->getName(),
            
            "value" => $company->getId(),
            "tokens" => $tokens
            
        );
    }
    
    return json_encode($data);

})
->bind('pupil_add_companysuggestions')
->secure('ROLE_WIRTSCHAFT_PRIV');
?>
