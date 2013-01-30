<?php

namespace sasCC\CompanyManagment\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of Constraintstype
 *
 * @author drak3
 */
class ConstraintsType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('maximalNumberOfWorkplaces', null, array(
            'label' => 'Maximale Anzahl an Arbeitsplätze',
            'required' => false,
        ));
        $builder->add('minimalNumberOfWorkplaces', null, array(
            'label' => 'Minimale Anzahl an Arbeitsplätzen',
            'required' => false,
        ));
        $builder->add('minimalGrade', null, array(
            'label' => 'Mindestklassenstufe',
            'required' => false,
        ));
        $builder->add('maximalGrade', null, array(
            'label' => 'Höchstklassenstufe',
            'required' => false,
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
