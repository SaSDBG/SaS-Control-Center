<?php

namespace sasCC\CompanyManagment\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of Constraintstype
 *
 * @author drak3
 */
class ConstraintsType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('minimalNumberOfWorkplaces', 'integer', array(
            'label' => 'Minimale Anzahl an Arbeitsplätzen',
            'required' => true,
            'constraints' => array(
                new Assert\NotBlank,
                new Assert\Type(array(
                    'type' => 'numeric'
                )),
                new Assert\Min(array(
                    'limit' => 1,
                    'message' => 'Es muss mindestens ein Mitarbeiter beschäftigt werden können.',
                ))
            )
        ));
        
        $builder->add('maximalNumberOfWorkplaces', 'integer', array(
            'label' => 'Maximale Anzahl an Arbeitsplätze',
            'required' => true,
            'constraints' => array(
                new Assert\NotBlank,
                new Assert\Type(array(
                    'type' => 'numeric',
                    'message' => 'Bitte Zahlenwert eintragen.'
                )),
                new Assert\Min(array(
                    'limit' => 1,
                    'message' => 'Es muss mindestens ein Mitarbeiter beschäftigt werden können.'
                ))
            )
        ));
        
        $builder->add('minimalGrade', null, array(
            'label' => 'Mindestklassenstufe',
            'required' => true,
            'constraints' => array(
                new Assert\NotBlank,
                new Assert\Regex(array(
                    'pattern' => '/^(K1|K2|10|[0-9])$/i',
                    'message' => 'Bitte Klassenstufe eintragen (z.B. 5 oder K1).'
                ))
            )
        ));
        $builder->add('maximalGrade', null, array(
            'label' => 'Höchstklassenstufe',
            'required' => true,
            'constraints' => array(
                new Assert\NotBlank,
                new Assert\Regex(array(
                    'pattern' => '/^(K1|K2|10|[0-9])$/i',
                    'message' => 'Bitte Klassenstufe eintragen (z.B. 5 oder K1).',
                ))
            )
        ));
        $builder->add('specialRules', 'textarea', array(
            'label' => 'Spezielle Zuteilungsregelungen',
            'required' => false,
        ));
    }

    public function getName()
    {
        return 'constraints';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'sasCC\Company\AssignmentConstraints',
        ));
    }
}

?>
