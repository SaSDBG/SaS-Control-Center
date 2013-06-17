<?php
use Symfony\Component\HttpFoundation\Request;
use sasCC\Broadcast\Broadcast;
use sasCC\Broadcast\BroadcastType;
use sasCC\App;

// List broadcasts
$app->match('/broadcasts/list', function(Request $r, App $app) {
    $broadcasts = $app['em']->getRepository('sasCC\Broadcast\Broadcast')
                           ->findAll();
    $app['logger.actions']->addInfo(sprintf('User %s (%d) accessed /broadcasts/list', $app->user()->getUserName(), $app->user()->getId()));
    return $app['twig']->render('broadcast/broadcast.list.twig', array("title" => "Broadcastliste", "broadcasts" => $broadcasts)); 
})
->bind('broadcast_list')
->secure('ROLE_PRIV');

// Add broadcast
$app->match('/broadcasts/add', function(Request $r) use ($app) {   
    return handleBroadcastEdit(
            "Broadcast hinzufügen",
            new Broadcast(),
            array(),
            $app,
            sprintf('Broadcast mit ID %%d wurde von %s (%d) angelegt', $app->user()->getUserName(), $app->user()->getId()),
            'broadcast_list'
     );

})
->bind('broadcast_add')
->secure('ROLE_PRIV');

// Edit Broadacst
$app->match('/broadcasts/{id}/edit', function(Request $r, App $app,  $id) {   
    $broadcast = $app['em']->find("sasCC\Broadcast\Broadcast", (int) $id);
    if($broadcast === null) return 'Broadcast not Found';
    return handleBroadcastEdit(
            "Broadcast bearbeiten",
            $broadcast,
            array('edited' => true, 'success' => true),
            $app,
            sprintf('Broadcast mit ID %%d wurde von %s (%d) bearbeitet', $app->user()->getUserName(), $app->user()->getId()),
            'broadcast_list'
     );
})
->bind('broadcast_edit')
->secure('ROLE_PRIV');

function handleBroadcastEdit($title, Broadcast $data, $pathArgs, App $app, $logMsg, $redirectRoute) {
    $form = $app['form.factory']->create(new BroadcastType(), $data);
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
    
    return $app['twig']->render('broadcast/broadcast.add.twig', array('form' => $form->createView(), "title" => $title));
}

// Delete Broadcast
$app->match('/broadcasts/{id}/delete', function(Request $r, App $app, $id) {  
    $broadcast = $app['em']->find("sasCC\Broadcast\Broadcast", (int) $id);
    if($broadcast === null) return 'Invalid Broadcast';

    
    $form = $app['form.factory']->createBuilder('form',['sure' => false])
                ->add('sure', 'checkbox', array(
                   'label' => 'Ich bin mir sicher',
                   'required' => true,
                ))->getForm();
 
    if($app['request']->getMethod() == 'POST') {
        $form->bindRequest($app['request']);
        if ($form->isValid() && $form->getData()['sure'] === true) {
            $app['em']->persist($broadcast);
            $app['em']->flush();
            $app['em']->remove($broadcast);
            $app['em']->flush();
            $app['logger.actions']->addInfo(sprintf('Broadcast mit ID %d wurde von %s (%d) gelöscht.', $broadcast->getId(), $app->user()->getUserName(), $app->user()->getId()));
            return $app->redirect($app->path('broadcast_list', array('deleted' => 'true', 'success' => 'true')));
        }
    }
    
    return $app['twig']->render('broadcast/broadcast.delete.twig', array('form' => $form->createView(), "title" => 'Broadcast löschen'));
})
->bind('broadcast_delete')
->secure('ROLE_PRIV');

// Broadcast details
$app->match('/broadcasts/{id}/details', function(Request $r, App $app, $id){
    $broadcast = $app['em']->find("sasCC\Broadcast\Broadcast", (int)$id);
    if($broadcast === null) return 'Broadcast not Found';

    
    $app['logger.actions']->addInfo(sprintf('User %s (%d) accessed /broadcasts/%d/details', $app->user()->getUserName(), $app->user()->getId(), $id));
    return $app['twig']->render('broadcast/broadcast.details.twig', array("title" => "Betriebsdetails", "broadcast" => $broadcast));
})
->bind('broadcast_detail')
->secure('ROLE_PRIV');

// Broadcast details raw
$app->match('/broadcasts/{id}/details/raw', function(Request $r, App $app, $id){
    $company = $app['em']->find("sasCC\Broadcast\Broadcast", (int)$id);
    if($company === null) return 'Broadcast not Found';
    
    $app['logger.actions']->addInfo(sprintf('User %s (%d) accessed /companies/details/%d/raw', $app->user()->getUserName(), $app->user()->getId(), $id));
    return $app['twig']->render('broadcast/broadcast.details.raw.twig', array("broadcast" => $broadcast));
})
->bind('broadcast_detail_raw')
->secure('ROLE_PRIV');

// Toggle visibility
$app->match('/broadcasts/{id}/toggle/visible', function(Request $r, App $app, $id) {
    $broadcast = $app['em']->find("sasCC\Broadcast\Broadcast", (int) $id);
    if($broadcast === null) return 'Invalid broadcast';
    
    $val = 0;
    //return $broadcast->getIsVisible()."s";
    
    if($broadcast->getIsVisible() == 1)
    {
        $broadcast->setIsVisible(0);
        $val = 0;
    }
    else 
    {
        $broadcast->setIsVisible(1);
        $val = 1;
    }
    
    $app['em']->flush();
    return $val;
})
->bind('broadcast_toggle_visible')
->secure('ROLE_PRIV');

// Toggle active state
$app->match('/broadcasts/{id}/toggle/active', function(Request $r, App $app, $id) {
    $broadcast = $app['em']->find("sasCC\Broadcast\Broadcast", (int) $id);
    if($broadcast === null) return 'Invalid broadcast';
    
    $val = 0;
    
    if($broadcast->getIsActive())
    {
        $broadcast->setIsActive(0);
        $val = 0;
    }
    else 
    {
        $broadcast->setIsActive(1);
        $val = 1;
    }
    
    $app['em']->flush();
    return $val;
})
->bind('broadcast_toggle_active')
->secure('ROLE_PRIV');

?>
