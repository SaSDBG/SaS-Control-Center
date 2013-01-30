<?php
namespace sasCC\CompanyManagment\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;



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
        ));
        //chiefs
        $builder->add('constraints', new ConstraintsType());
        $builder->add('chiefs', 'collection', array(
            'type' => new PupilType(),
            'allow_add' => true,
            'by_reference' => false,
            'required' => true,
        ));
        $builder->add('needs', 'textarea', array(
            'label' => "Benötigte Waren u.A.",
            'required' => false,
        ));
        $builder->add('description', 'textarea', array(
            'label' => "Beschreibung",
            'required' => true,
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
