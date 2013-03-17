<?php

namespace sasCC\Pupil;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;
use sasCC\CompanyManagment\Form\PupilType;

class PupilTypeFull extends PupilType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);
        
        $builder->add('firstWishRaw', null, array(
            'label' => 'Erstwunsch',
            'required' => true,
            'constraints' => array(
               new Assert\NotBlank()
            ),
            'error_bubbling' => true,
        ));
       
        $builder->add('secondWishRaw', null, array(
            'label' => 'Zweitwunsch',
            'required' => false,
            'error_bubbling' => true,
        ));
        
        $builder->add('thirdWishRaw', null, array(
            'label' => 'Drittwunsch',
            'required' => false,
            'error_bubbling' => true,
        ));
        
        $builder->add('pupilLinkRaw', null, array(
            'label' => 'Will in Projekt mit',
            'required' => false,
            'error_bubbling' => true,
        ));
    }

    public function getName() {
        return 'pupilFull';
    }

}

?>
