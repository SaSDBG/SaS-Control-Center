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
class UserTypeNoPassword extends AbstractType{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('userName', null, array(
            'label' => 'Username',
            'required' => true,
            'constraints' => array(
               new Assert\NotBlank()
            )
        ));
               
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
