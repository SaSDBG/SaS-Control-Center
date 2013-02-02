<?php
use Symfony\Component\HttpFoundation\Request;
use sasCC\User\UserType;
use sasCC\User\User;

$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
        'title' => "Einloggen"
    ));
})->bind('login');

$app->match('/users/create', function(Request $r) use ($app) {
     $user = new User();
     $form = $app['form.factory']->create(new UserType(), $user);
     
     if($app['request']->getMethod() == 'POST') {
         $form->bindRequest($app['request']);
         if ($form->isValid()) {
             $user->setSalt(createSalt());
             $user->setPassword($app->encodePassword($user, $user->getPlainPass()));
             $app['em']->persist($user);
             $app['em']->flush();
             return $app->redirect($app->path('create_user', array('success' => true)));
         }
     }
    
     return $app['twig']->render('user.create.html.twig', array('form' => $form->createView(), "title" => 'User anlegen'));
})->bind('create_user')
  ->secure('ROLE_ADMIN');


function createSalt() {
    return base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM));
}
?>
