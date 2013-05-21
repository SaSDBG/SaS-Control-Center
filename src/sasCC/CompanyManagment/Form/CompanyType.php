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
                    'message' => 'Es muss ein Name eingetragen werden.'
                )),
            )
        ));
        
        $builder->add('category', 'choice', array(
            'label' => 'Kategorie',
            'required' => true,
            'choices' => [
                'Essen&Trinken' => 'Essen&Trinken',
                'Kunst&Kultur' => 'Kunst&Kultur',
                'Sport' => 'Sport',
                'Staatlich' => 'Staatlich',
                'Sonstiges' => 'Sonstiges',
            ],
        ));
        
        $builder->add('chiefs', 'collection', array(
            'label' => "Betriebsleiter",
            'type' => new PupilType(),
            'options' => [
                'error_bubbling' => true,
            ],
            'allow_add' => true,
            'by_reference' => false,
            'required' => true,
            'constraints' => array(
                new Assert\Count(array(
                    'min' => 1,
                    'minMessage' => 'Es muss mindestens ein Betriebsleiter eingetragen werden.'
                ))
            ),
            'error_bubbling' => false,
        ));
        
        $builder->add('needs', 'textarea', array(
            'label' => "Benötigte Waren u.A.",
            'required' => false,
        ));
        
        $builder->add('room', null, array(
            'label' => 'Raum',
            'required' => false,
        ));
        
        $builder->add('description', 'textarea', array(
            'label' => "Beschreibung",
            'required' => false,
            'constraints' => array(
                new Assert\NotBlank(array(
                    'message' => 'Es muss eine Beschreibung eingetragen werden.'
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
