<?php
namespace sasCC\Broadcast;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;



/**
 * Description of BroadcastType
 */
class BroadcastType extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', null, array(
            'label' => "Name des Broadcasts",
            'required' => true,
            'constraints' => array(
                new Assert\NotBlank(array(
                    'message' => 'Es muss ein Name eingetragen werden.'
                )),
            )
        ));
        
        $builder->add('type', 'choice', array(
            'label' => 'Broadcasttyp',
            'required' => true,
            'choices' => [
                '0' => 'Standard (Grau)',
                '1' => 'Attention (Gelb)',
                '2' => 'Alert (Rot)'
            ],
        ));
        
        $builder->add('content', 'textarea', array(
            'label' => "Inhalt",
            'required' => true,
        ));
        
        $builder->add('start', null, array(
            'label' => 'Start',
            'required' => true,
        ));
        
        $builder->add('end', null, array(
            'label' => 'Ende',
            'required' => true,
        ));
    }

    public function getName()
    {
        return 'broadcast';
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'sasCC\Broadcast\Broadcast',
        ));
    }

}

?>
