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
        
        $builder->add('companyRaw', null, array(
            'label' => 'Betrieb',
            'required' => true,
            'constraints' => array(),
            'error_bubbling' => true,
        ));
    }

    public function getName() {
        return 'pupilFull';
    }

}

?>
