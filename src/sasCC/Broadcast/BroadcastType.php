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
            'required' => true
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
            'required' => false,
        ));
        
        $builder->add('start', 'datetime', array(
            'label' => 'Start',
            'required' => true,
        ));
        
        $builder->add('end', 'datetime', array(
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
