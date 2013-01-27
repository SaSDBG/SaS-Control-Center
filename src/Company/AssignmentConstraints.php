<?php

namespace sasCC\Companies\Company;

/**
 * Description of Constraints
 *
 * @author drak3
 */
class AssignmentConstraints {
    protected $maxWorkplaces;
    protected $minWorkplaces;
    protected $minClass;
    protected $maxClass;
    protected $specialRules;
    
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

    
    public function getClassRestriction() {
        return $this->classRestriction;
    }

    public function setClassRestriction($classRestriction) {
        $this->classRestriction = $classRestriction;
    }

    public function getSpecialRules() {
        return $this->specialRules;
    }

    public function setSpecialRules($specialRules) {
        $this->specialRules = $specialRules;
    }


}

?>
