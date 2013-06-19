<?php
use Symfony\Component\HttpFoundation\Request;
use sasCC\App;
use sasCC\Broadcast\Broadcast;

// Returns all broadcasts as JSON
$app->match('/pub/broadcasts/json', function(Request $r) use ($app) {
    $broadcasts = $app['em']->getRepository('sasCC\Broadcast\Broadcast')
                           ->findAll();
    $broadcastsRaw = array();
    
    $date = new \DateTime;
    
    $index = 0;
    foreach($broadcasts as $b)
    {
        $unlink = false;
        
        // Löschen wenn deaktiviert
        if(!$b->getIsActive())
        {
            $unlink = true;
        }
        
        // Ende früher als aktuelles Datum oder Start später als aktuelles Datum
        if(($b->getEnd() < $date || $b->getStart() > $b) && !$b->getIsManual())
        {
            $unlink = true;
        }
        
        // Manuell und nicht sichtbar
        if($b->getIsManual() && !$b->getIsVisible())
        {
            $unlink = true;
        }
        
        if($unlink)
            unset($broadcasts[$index]);

        $index++;
    }
    
    foreach($broadcasts as $b)
    {
        $broadcastsRaw[] = array(
            "id" => $b->getId(),
            "name" => $b->getName(),
            "type" => $b->getType(),
            "content" => $b->getContent(),
            "html" => $app['twig']->render('broadcast/broadcast.public.raw.twig', array("broadcast" => $b)),
        );
    }
    
    $content = json_encode($broadcastsRaw);
    
    return new \Symfony\Component\HttpFoundation\Response($content, 200, ['Content-type' => 'text/json; charset:UTF-8', 'Content-Encoding' => 'UTF-8']);
})
->bind('pub_broadcast_json');

$app->match('/pub/broadcasts', function(Request $r) use ($app) {
    
   return $app['twig']->render('broadcast/broadcast.public.twig', array("title" => "Broadcasts"));
})
->bind('pub_broadcast');
        
?>
