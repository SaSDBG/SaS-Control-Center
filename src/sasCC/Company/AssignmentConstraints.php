<?php

namespace sasCC\Company;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * Description of Constraints
 *
 * @author drak3
 * @Entity
 * @Table(name="constraints")
 */
class AssignmentConstraints {
        
    /**
     * @Column(type="integer")
     */
    protected $maxWorkplaces = 30;
    
    /**
     * @Column(type="integer")
     */
    protected $minWorkplaces = 3;
    
    /**
     * @Column
     */
    protected $minClass = '5';
    
    /**
     * @Column
     */
    protected $maxClass = 'K2';
    
    /**
     * @Column(type="text", nullable=true)
     */
    protected $specialRules = '';
    
    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $gradeRegexOps = array(
            'pattern' => '/k1|k2|10|[5-9]/i'
        );
        
        $metadata->addPropertyConstraint('maxWorkplaces', new Assert\Type('numeric'));
        $metadata->addPropertyConstraint('maxWorkplaces', new Assert\NotNull);
        $metadata->addPropertyConstraint('maxWorkplaces', new Assert\Min(1));
        
        $metadata->addPropertyConstraint('minWorkplaces', new Assert\Type('numeric'));
        $metadata->addPropertyConstraint('minWorkplaces', new Assert\Min(0));
        
        $metadata->addPropertyConstraint('minClass', new Assert\Regex($gradeRegexOps));
        $metadata->addPropertyConstraint('maxClass', new Assert\Regex($gradeRegexOps));
        
    }
    
    /**
     * @Id @Column(type="integer") @GeneratedValue 
     */
    protected $id;
    
    public function getId() {
        return $this->id;
    }
        
    public function requiresManualHandling() {
        return $this->specialRules !== NULL;
    }
    
    public function getMaximalNumberOfWorkplaces() {
        return $this->maxWorkplaces;
    }

    public function setMaximalNumberOfWorkplaces($workplaces) {
        $this->maxWorkplaces = (int) $workplaces;
    }
    
    public function getMinimalNumberOfWorkplaces() {
        return $this->minWorkplaces;
    }

    public function setMinimalNumberOfWorkplaces($minWorkplaces) {
        $this->minWorkplaces = (int) $minWorkplaces;
    }

    public function getMinimalGrade() {
        return $this->minClass;
    }

    public function setMinimalGrade($minClass) {
        $this->minClass = $minClass;
    }

    public function getMaximalGrade() {
        return $this->maxClass;
    }

    public function setMaximalGrade($maxClass) {
        $this->maxClass = $maxClass;
    }

    

    public function getSpecialRules() {
        return $this->specialRules;
    }

    public function setSpecialRules($specialRules) {
        $this->specialRules = $specialRules;
    }


}

?>
