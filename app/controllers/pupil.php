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
        $data->setPupilLink($app['em']->find("sasCC\Pupil\Pupil", (int)$data->getPupilLink()));
        
        if ($form->isValid()) {
            $app['em']->persist($data);
            $app['em']->flush();
            
            $id = $data->getId();
            $link = $data->getPupilLink();
            
            $userToBacklink = $app['em']->find("sasCC\Pupil\Pupil", (int)$link->getId());
            $userToBacklink->setPupilLink($app['em']->find("sasCC\Pupil\Pupil", (int)$id));
            
            $app['em']->persist($userToBacklink);
            $app['em']->flush();
            
            $app['logger.actions']->addInfo(sprintf($logMsg, $data->getId()));
            return $app->redirect($app->path($redirectRoute, $pathArgs));
        }
    }
    
    return $app['twig']->render('pupil/pupil.add.twig', array('form' => $form->createView(), "title" => $title));
}

// Return company suggestions
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

// Return pupil suggestions
$app->match('/pupils/add/pupilsuggestions', function(Request $r) use ($app) {   
    
    $searchQuery = $r->get("q");
         
    // If no query is passed
    if($searchQuery == NULL)
    {
        $pupils = $app['em']->getRepository('sasCC\Pupil\Pupil')
                               ->findAll();
    }
    // If query is passed
    else
    {
        $query = $app['em']->createQuery("SELECT u FROM sasCC\Pupil\Pupil u WHERE ( 
            u.firstName LIKE :query OR
            u.lastName LIKE :query OR
            u.id LIKE :query           
            )");
        $query->setParameter("query", "%$searchQuery%");
        
        $pupils = $query->getResult();
    }
    
    $data = array();
    
    foreach($pupils as $pupil)
    {
        $tokens = array();
        $tokens[] = $pupil->getName();
        $tokens[] = $pupil->getId();

        $data[] = array(
            "id" => $pupil->getId(),
            "name" => $pupil->getName(),
            
            "value" => $pupil->getId(),
            "tokens" => $tokens
            
        );
    }
    
    return json_encode($data);

})
->bind('pupil_add_pupilsuggestions')
->secure('ROLE_WIRTSCHAFT_PRIV');
?>
