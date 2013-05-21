<?php
use Symfony\Component\HttpFoundation\Request;
use sasCC\Pupil\Pupil;
use sasCC\Pupil\PupilTypeFull;
use sasCC\App;


// List pupils
$app->match('/pupils/list', function(Request $r, App $app) {
    $pupils = $app['em']->getRepository('sasCC\Pupil\Pupil')
                           ->findAll();
    $app['logger.actions']->addInfo(sprintf('User %s (%d) accessed /pupils/list', $app->user()->getUserName(), $app->user()->getId()));
    return $app['twig']->render('pupil/pupil.list.twig', array("title" => "Schülerliste", "pupils" => $pupils));
    
})
->bind('pupil_list')
->secure('ROLE_WIRTSCHAFT_PRIV'); 

// Export pupils to CSV
$app->get('/pupils/list/export/csv', function(Request $r, App $app) {
    $pupils = $app['em']->getRepository('sasCC\Pupil\Pupil')
                           ->findAll();
    $escape = function ($string) {
        if(strstr($string, ';') === false) {
            return $string;
        } else {
            return '"'.html_entity_decode(str_replace('"', '""', $string)).'"';
        }
    };
    $csvFilter = new Twig_SimpleFilter('ecsv', $escape);
    $app['twig']->addFilter($csvFilter);
    
    $content = "\xEF\xBB\xBF";
    $content .= $app['twig']->render('pupil/pupil.export.csv.twig', array('pupils' => $pupils));
    
    return new \Symfony\Component\HttpFoundation\Response($content, 200, ['Content-type' => 'text/csv; charset:UTF-8', 'Content-Disposition' => 'attachment; filename="SaS - Schülerlisteliste.csv"', 'Content-Encoding' => 'UTF-8']);
})->bind('pupil_export_csv')
  ->secure('ROLE_WIRTSCHAFT_PRIV');

// Get pupil details
$app->match('/pupils/{id}/details', function(Request $r, App $app, $id){
    $pupil = $app['em']->find("sasCC\Pupil\Pupil", (int)$id);
    if($pupil=== null) return 'Pupil not Found';
    $app['logger.actions']->addInfo(sprintf('User %s (%d) accessed /pupils/%d/details', $app->user()->getUserName(), $app->user()->getId(), $id));
    return $app['twig']->render('pupil/pupil.details.twig', array("title" => "Schülerdetails", "pupil" => $pupil));
})
->bind('pupil_detail')
->secure('ROLE_WIRTSCHAFT_PRIV');


// Get pupil information for modal window
$app->match('/pupils/{id}/details/modal', function(Request $r, App $app, $id){
    $pupil = $app['em']->find("sasCC\Pupil\Pupil", (int)$id);
    if($pupil === null) return 'Pupil not Found';
    $app['logger.actions']->addInfo(sprintf('User %s (%d) accessed /pupils/%d/details/modal', $app->user()->getUserName(), $app->user()->getId(), $id));
    return $app['twig']->render('pupil/pupil.details.modal.twig', array("title" => "Schülerdetails", "pupil" => $pupil));
})
->bind('pupil_detail_modal')
->secure('ROLE_WIRTSCHAFT_PRIV');

// Edit pupils
$app->match('/pupils/{id}/edit', function(Request $r, App $app, $id) {
    return handlePupilEdit(
            "Schüler bearbeiten",
            $app['em']->getRepository('sasCC\Pupil\Pupil')->find((int)$id),
            array("edited" => true, "success" => true),
            $app,
            sprintf('Schüler mit ID %%d wurde von %s (%d) editiert', $app->user()->getUserName(), $app->user()->getId()),
            'pupil_list'
     );

    
})
->bind('pupil_edit')
->secure('ROLE_WIRTSCHAFT_PRIV');

// Add pupil
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

// Delete company
$app->match('/pupils/{id}/delete', function(Request $r, App $app, $id) {  
    $pupil = $app['em']->find("sasCC\Pupil\Pupil", (int) $id);
    if($pupil === null) return 'Invalid company';

    $form = $app['form.factory']->createBuilder('form',['sure' => false])
                ->add('sure', 'checkbox', array(
                   'label' => 'Ich bin mir sicher',
                   'required' => true,
                ))->getForm();
 
    if($app['request']->getMethod() == 'POST') {
        $form->bindRequest($app['request']);
        if ($form->isValid() && $form->getData()['sure'] === true) {
            $app['em']->remove($pupil);
            $app['em']->flush();
            $app['logger.actions']->addInfo(sprintf('Schüler mit ID %d wurde von %s (%d) gelöscht.', $pupil->getId(), $app->user()->getUserName(), $app->user()->getId()));
            return $app->redirect($app->path('pupil_list', array('deleted' => 'true', 'success' => 'true')));
        }
    }
    
    return $app['twig']->render('pupil/pupil.delete.twig', array('form' => $form->createView(), "title" => 'Schüler löschen', "pupilname" => $pupil->getFullName()));
})
->bind('pupil_delete')
->secure('ROLE_WIRTSCHAFT_ADMIN');

function handlePupilEdit($title, Pupil $data, $pathArgs, App $app, $logMsg, $redirectRoute) {
    $form = $app['form.factory']->create(new PupilTypeFull(), $data);
    $pathArgs = array_merge(array("success" => true), $pathArgs);
 
    if($app['request']->getMethod() == 'POST') {
        $form->bindRequest($app['request']);
        
        $c = $app['em']->find("sasCC\Company\Company", (int)$data->getCompany());
        $data->setCompany($c);
        
        
        if ($form->isValid()) {
            $c->removeChief($data); //make sure he isnt chief already
            if($data->isChief()) {
                $c->addChief($data);
            }
            $app['em']->persist($data);
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
        $tokens[] = (string)$company->getId();

        $data[] = array(
            "id" => (string)$company->getId(),
            "name" => $company->getName(),
            
            "value" => (string)$company->getId(),
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
