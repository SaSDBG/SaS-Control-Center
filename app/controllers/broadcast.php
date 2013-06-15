<?php
use Symfony\Component\HttpFoundation\Request;
use sasCC\Broadcast\Broadcast;
use sasCC\Broadcast\BroadcastType;
use sasCC\App;

// Add broadcast
$app->match('/broadcasts/add', function(Request $r) use ($app) {   
    return handleBroadcastEdit(
            "Broadcast hinzufügen",
            new Broadcast(),
            array(),
            $app,
            sprintf('Broadcast mit ID %%d wurde von %s (%d) angelegt', $app->user()->getUserName(), $app->user()->getId()),
            'broadcast_add'
     );

})
->bind('broadcast_add')
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
?>
