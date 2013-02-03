<?php
use Symfony\Component\HttpFoundation\Request;
use sasCC\User\UserType;
use sasCC\User\User;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Security\Core\Validator\Constraint as SecurityAssert;

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

$app->match('/user/changepass', function(Request $r) use ($app) {
    $user = $app->user();
    $data = [
      'oldpass' => '',
      'newpass' => '',
      'newpassConfirm' => '',
    ];
    $form = $app->form($data)
        ->add('oldpass', 'password', [
            'label' => 'Jetziges Passwort',
        ])
        ->addValidator(new CallbackValidator(function($form) use ($user, $app) {
            $pass = $form['oldpass']->getData();
            $encoder =  $app['security.encoder_factory']->getEncoder($user);
            if(!$encoder->isPasswordValid($user->getPassword(), $pass, $user->getSalt())) {
                $form['oldpass']->addError(new \Symfony\Component\Form\FormError('Falsches Passwort'));
            }
        }))
        ->add('newpass', 'password', [
            'label' => 'Neues Passwort'
        ])
        ->add('newpassConfirm', 'password', [
            'label' => 'Passwort wiederholen'
        ])
        ->addValidator(new CallbackValidator(function($form) {
            if($form['newpass']->getData() !== $form['newpassConfirm']->getData()) {
                $form['newpassConfirm']->addError(new \Symfony\Component\Form\FormError('Passwörter müssen übereinstimmen'));
            }
        }))
        ->getForm();
        
        if($r->getMethod() === 'POST') {
            $form->bindRequest($r);
            if ($form->isValid()) {
                $user->setSalt(createSalt());
                $user->setPassword($app->encodePassword($user, $form->getData()['newpass']));
                $app['em']->persist($user);
                $app['em']->flush();
                return $app->redirect($app->path('change_pass', array('success' => true)));
            }
        }
        
        return $app['twig']->render('user.changepass.html.twig', array('form' => $form->createView(), "title" => 'Passwort ändern.'));
})->bind('change_pass');


function createSalt() {
    return base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM));
}
?>
