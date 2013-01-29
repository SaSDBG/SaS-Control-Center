<?php
namespace sasCC\Pupil;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Class
 * @author drak3
 */
class SchoolClass {
    
    protected $grade;
    
    protected $identifyer;
    
    protected $id;
    
    public function getId() {
        return $this->id;
    }
    
    public function getGrade() {
        return $this->grade;
    }

    public function setGrade($grade) {
        $this->grade = $grade;
    }

    public function getIdentifyer() {
        return $this->identifyer;
    }

    public function setIdentifyer($identifyer) {
        $this->identifyer = $identifyer;
    }


}
?>
