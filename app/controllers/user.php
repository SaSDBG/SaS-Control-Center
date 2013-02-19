<?php
use Symfony\Component\HttpFoundation\Request;
use sasCC\User\UserType;
use sasCC\User\UserTypeNoPassword;
use sasCC\User\User;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Security\Core\Validator\Constraint as SecurityAssert;

$app->before(function (Request $request) use ($app) {
    if($app->user() instanceof User && $app->user()->isFirstPass() && $request->getBasePath().$request->getPathInfo() !== $app->path('change_pass')) {
        return $app->redirect($app->path('change_pass', array('forced' => true)));
    }
});

// User list
$app->match('users/list', function(Request $r) use ($app) {
    
    $users = $app['em']->getRepository('sasCC\User\User')
                       ->findAll();
    
    return $app['twig']->render('user.list.html.twig', array("users" => $users, "title" => "Userliste"));
})
->bind('user_list')
->secure('ROLE_ADMIN');

// User creation
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
             return $app->redirect($app->path('user_create', array('success' => true)));
         }
     }
    
     return $app['twig']->render('user.create.html.twig', array('form' => $form->createView(), "title" => 'Benutzer anlegen'));
})
->bind('user_create')
->secure('ROLE_ADMIN');

// User edit
$app->match('/users/edit/{id}', function(Request $r, $id) use ($app) {
    $user = $app['em']->find('sasCC\User\User', (int)$id);
    
    if($user === null) return 'User not found';  
    
    $form = $app['form.factory']->create(new UserTypeNoPassword(), $user);
     
     if($app['request']->getMethod() == 'POST') {
         $form->bindRequest($app['request']);
         if ($form->isValid()) {
             $user->setSalt(createSalt());
             $user->setPassword($app->encodePassword($user, $user->getPlainPass()));
             $app['em']->persist($user);
             $app['em']->flush();
             return $app->redirect($app->path('user_list', array('success' => true, 'edited' => true)));
         }
     }
    
     return $app['twig']->render('user.edit.html.twig', array('form' => $form->createView(), "title" => 'Benutzer editieren'));
})
->bind('user_edit')
->secure('ROLE_ADMIN');

// User deletion
$app->match('/users/delete/{id}', function(Request $r, $id) use ($app) {
    $user = $app['em']->find("sasCC\User\User", (int) $id);
    if($user === Null) return "User not found.";

    
    $form = $app['form.factory']->createBuilder('form',['sure' => false])
                ->add('sure', 'checkbox', array(
                   'label' => 'Ich bin mir sicher',
                   'required' => true,
                ))->getForm();
 
    if($app['request']->getMethod() == 'POST') {
        $form->bindRequest($app['request']);
        if ($form->isValid() && $form->getData()['sure'] === true) {
            $app['em']->remove($user);
            $app['em']->flush();
            $app['logger.actions']->addInfo(sprintf('Benutzer mit ID %d wurde von %s (%d) gelöscht.', $user->getId(), $app->user()->getUserName(), $app->user()->getId()));
            return $app->redirect($app->path('user_list', array('deleted' => 'true', 'success' => 'true')));
        }
    }
    
    return $app['twig']->render('user.delete.html.twig', array('form' => $form->createView(), "title" => 'Benutzer löschen', "username" => $user->getUserName()));   
})
->bind('user_delete')
->secure('ROLE_ADMIN');

// Change password
$app->match('/user/password/change', function(Request $r) use ($app) {
    $user = $app->user();
    $data = [
      'oldpass' => '',
      'newpass' => '',
      'newpassConfirm' => '',
    ];
    $form = $app->form($data)
        ->add('oldpass', 'password', [
            'label' => 'Aktuelles Passwort',
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
            'label' => 'Neues Passwort wiederholen'
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
                $user->setFirstPass(false);
                $app['em']->persist($user);
                $app['em']->flush();
                return $app->redirect($app->path('home', array('changed_pass' => true)));
            }
        }
        
        return $app['twig']->render('user.changepass.html.twig', array('form' => $form->createView(), "title" => 'Passwort ändern.'));
})
->bind('change_pass');


function createSalt() {
    return base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM));
}
?>
