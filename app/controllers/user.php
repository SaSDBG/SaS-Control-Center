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
     $data = new User();
     $form = $app['form.factory']->create(new UserType(), $data);
     
     if($app['request']->getMethod() == 'POST') {
         $form->bindRequest($app['request']);
         if ($form->isValid()) {
             $data->setSalt(createSalt());
             $data->setPassword($app->encodePassword($data, $data->getSalt()));
             $app['em']->persist($data);
             $app['em']->flush();
             return $app->redirect($app->path('create_user', array('success' => true)));
         }
     }
    
     return $app['twig']->render('user.create.html.twig', array('form' => $form->createView(), "title" => 'User anlegen'));
    /*
     $user = new sasCC\User\User();
     $user->setUserName('maxr');
     $salt = base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM));
     var_dump($salt);
     $user->setSalt($salt);
     $user->setPassword($app->encodePassword($user, 'foobar'));
     $user->setArea('ALLE');
     $user->setPrivileges('ADMIN');
     
     $app['em']->persist($user);
     $app['em']->flush();
    */
})->bind('create_user');
  //->secure('ROLE_ADMIN');

function createSalt() {
    return base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM));
}
?>
