<?php

namespace sasCC\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Description of UserType
 *
 * @author drak3
 */
class UserType extends UserTypeNoPassword{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('plainPass', 'password', array(
            'label' => 'Passwort',
            'required' => true,
            'constraints' => array(
               new Assert\NotBlank(),
               new Assert\Length(array(
                   'min' => '6' 
               ))
            )
        ));
        
        $builder->add('plainPassSave', 'password', array(
            'label' => 'Passwort nochmals eingeben',
            'required' => true,
            'constraints' => array(
               new Assert\NotBlank(),
               new Assert\Length(array(
                   'min' => '6' 
               )),
            )
        ));
        
        $builder->addValidator(new \Symfony\Component\Form\CallbackValidator(function($form) {
            if($form['plainPass']->getData() !== $form['plainPassSave']->getData()) {
                $form['plainPassSave']->addError(new \Symfony\Component\Form\FormError('Passwörter müssen übereinstimmen'));
            }
        }));
        
        parent::buildForm($builder, $options);
    }
    
    public static function passwordsAreIdentical() {
        if(! $user->getPlainPass() === $user->getPlainPassSave()) {
            $context->addViolationAtSubPath('plainPass', 'Die beiden Passwörter müssen übereinstimmen', array(), null);
        }
    }
}

?>
