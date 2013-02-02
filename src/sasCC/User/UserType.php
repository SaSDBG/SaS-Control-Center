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
class UserType extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('userName', null, array(
            'label' => 'Username',
            'required' => true,
            'constraints' => array(
               new Assert\NotBlank()
            )
        ));
        
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
        
        
        $builder->add('area', 'choice', array(
            'label' => 'Bereich',
            'required' => true,
            'choices' => [
              'POLITIK' => 'Politik',
              'SONSTIGES' => 'Sonstiges',
              'WIRTSCHAFT' => 'Wirtschaft',
              'FINANZEN' => 'Finanzen',
              'ALLE' => 'Alle Bereiche'
            ],
        ));
        
        $builder->add('privileges', 'choice', array(
           'label' => 'Privilegien',
            'required' => true,
            'choices' => [
                'ADMIN' => 'Administrator',
                'PRIV' => 'Auflisten und Editieren',
                'CREATE' => 'Anlegen',
            ]
        ));
    }
    
    public static function passwordsAreIdentical() {
        var_dump(func_get_args());
        if(! $user->getPlainPass() === $user->getPlainPassSave()) {
            $context->addViolationAtSubPath('plainPass', 'Die beiden Passwörter müssen übereinstimmen', array(), null);
        }
    }

    public function getName()
    {
        return 'user';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'sasCC\User\User',
        ));
    }
}

?>
