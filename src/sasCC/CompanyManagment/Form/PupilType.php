<?php
namespace sasCC\CompanyManagment\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;

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
            'constraints' => array(
               new Assert\NotBlank()
            )
        ));
        $builder->add('rawClass', null, array(
            'label' => 'Klasse',
            'required' => true,
            'constraints' => array(
                new Assert\Regex(array(
                    'pattern' => '/K1|K2|10[a-z]|[5-9][a-z]/i',
                    'message' => 'Bitte gültige Klasse eintragen (z.B. K1 oder 6c)'
                ))
            )
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
