<?php

namespace sasCC\Company;

/**
 * Description of Constraints
 *
 * @author drak3
 * @Entity
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
     * @Column(type="text")
     */
    protected $specialRules = '';
    
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
        $this->maxWorkplaces = $workplaces;
    }
    
    public function getMinimalNumberOfWorkplaces() {
        return $this->minWorkplaces;
    }

    public function setMinimalNumberOfWorkplaces($minWorkplaces) {
        $this->minWorkplaces = $minWorkplaces;
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
