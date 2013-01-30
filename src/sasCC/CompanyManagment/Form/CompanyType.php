<?php
namespace sasCC\CompanyManagment\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * Description of CompanyType
 *
 * @author drak3
 */
class CompanyType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, array(
            'label' => "Name des Betriebs",
            'required' => true,
            'constraints' => array(
                new Assert\NotBlank(array(
                    'message' => 'Es muss ein Name eingetragen werden'
                )),
            )
        ));
        //chiefs
        $builder->add('constraints', new ConstraintsType(), array(
            'constraints' => array(
                new Assert\NotNull(),
             ),
        ));
        
        $builder->add('chiefs', 'collection', array(
            'label' => "Betriebsleiter",
            'type' => new PupilType(),
            'allow_add' => true,
            'by_reference' => false,
            'required' => true,
            'constraints' => array(
                new Assert\Count(array(
                    'min' => 1,
                    'minMessage' => 'Es muss mindestens 1 Betriebsleiter eingetragen werden'
                ))
            )
        ));
        
        $builder->add('needs', 'textarea', array(
            'label' => "Benötigte Waren u.A.",
            'required' => false,
        ));
        
        $builder->add('description', 'textarea', array(
            'label' => "Beschreibung",
            'required' => true,
            'constraints' => array(
                new Assert\NotBlank(array(
                    'message' => 'Es muss eine Beschreibung eingetragen werden'
                ))
            )
        ));
    }

    public function getName()
    {
        return 'company';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'sasCC\Company\Company',
        ));
    }

}

?>
