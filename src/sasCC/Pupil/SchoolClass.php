<?php
namespace sasCC\Pupil;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Class
 * @author drak3
 * @Entity
 * @Table(name="classes")
 */
class SchoolClass {
    
    /**
     * @Column(type="string")
     */
    protected $grade;
    
    /**
     * @Column(type="string")
     */
    protected $identifyer;
    
    /**
     * @OneToMany(targetEntity="Pupil", mappedBy="class", cascade={"persist"})
     */
    protected $pupils;
    
    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;
    
    public static function parse($class) {
        $class = strtolower($class);
        $grade = '';
        $id = '';
        $classObj = new self;
        if(substr($class, 0, 2) == 'k1') {
            $grade = 'k';
            $id = '2';
        }
        else if(substr($class, 0, 2) == 'k2') {
            $grade = 'k';
            $id = '2';
        }
        else if (substr($class, 0, 2) == '10') {
            $grade = '10';
            $id = $class[2];
        }
        else {
            $grade = $class[0];
            $id = $class[1];
        }
        
        $classObj->setGrade($grade);
        $classObj->setIdentifyer($id);
        return $classObj;
    }
    
    public function __construct() {
        $this->pupils = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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

    public function getFullClass() {
        return $this->grade . $this->identifyer;
    }

}
?>
