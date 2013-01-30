<?php

namespace sasCC\Pupil;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pupil
 * @Entity
 * @author drak3
 */
class Pupil {
    
    /**
     * @Column
     */
    protected $name;
    
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
    
    public function getId() {
        return $this->id;
    }
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getClass() {
        return $this->class;
    }

    public function setClass($class) {
        $this->class = $class;
    }
    
    public function setRawClass($class) {
        $this->class = SchoolClass::parse($class);
    }
    
    public function getRawClass() {
        return $this->class->getGrade().$this->class->getIdentifier();
    }
    
    public function getCompany() {
        return $this->company;
    }

    public function setCompany($company) {
        $this->company = $company;
    }

 }

?>
