<?php
namespace sasCC\Companies\Company;

/**
 * Company-model class
 *
 * @author drak3
 */
class Company {
    /**
     * @var String
     */
    protected $name;
    
    /**
     * @var Pupil[]
     */
    protected $managers;
    
    /**
     *
     * @var AssignmentConstraints
     */
    protected $constraints;
    
    /**
     *
     * @var String
     */
    protected $needed;
    
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getManagers() {
        return $this->managers;
    }

    public function setManagers($managers) {
        $this->managers = $managers;
    }

    /**
     * 
     * @return AssignmentConstraints
     */
    public function getConstraints() {
        return $this->constraints;
    }

    public function setConstraints($constraints) {
        $this->constraints = $constraints;
    }
    
    public function getNeeds() {
        return $this->needed;
    }

    public function setNeeds($needed) {
        $this->needed = $needed;
    }




}

?>
