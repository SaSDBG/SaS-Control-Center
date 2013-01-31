<?php

namespace sasCC\Pupil;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pupil
 * @Entity
 * @Table(name="pupils")
 * @author drak3
 */
class Pupil {
    
    /**
     * @Column
     */
    protected $firstName;
    
    /**
     * @Column
     */
    protected $lastName;
    
    /**
     * @ManyToOne(targetEntity="SchoolClass", inversedBy="pupils", cascade={"persist"})
     */
    protected $class;
    
    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;
    
    /**
     * @ManyToOne(targetEntity="sasCC\Company\Company", inversedBy="members", cascade={"persist"})
     */
    protected $company;
    
    public function __construct() {
        $this->class = new SchoolClass();
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getFirstName() {
        return $this->firstName;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    
    public function getClass() {
        return $this->class;
    }

    public function setClass($class) {
        $this->class = $class;
    }
    
    public function setRawClass($class) {
        $this->setClass(SchoolClass::parse($class));
    }
    
    public function getRawClass() {
        return $this->getClass()->getGrade().$this->class->getIdentifyer();
    }
    
    public function getCompany() {
        return $this->company;
    }

    public function setCompany($company) {
        $this->company = $company;
    }

 }

?>
