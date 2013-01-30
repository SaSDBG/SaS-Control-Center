<?php
namespace sasCC\CompanyManagment\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Description of PupilType
 *
 * @author drak3
 */
class PupilType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, array(
            'label' => 'Name (Vorname Nachname)',
            'required' => true,
        ));
        $builder->add('rawClass', null, array(
            'label' => 'Klasse',
            'required' => true,
        ));
    }

    public function getName()
    {
        return 'pupil';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'sasCC\Pupil\Pupil',
        ));
    }
}

?>
