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
?>
