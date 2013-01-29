<?php
namespace sasCC\Entities\Pupil;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Class
 * @Entity @Table(name="classes")
 * @author drak3
 */
class SchoolClass {
    
    /**
     * @Collumn(type="string")
     * @var string
     */
    protected $grade;
    
    /**
     * @Collumn(type="string")
     * @var string
     */
    protected $identifyer;
    
    /**
     * @Id @Collumn(type="integer") @GeneratedValue
     */
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
