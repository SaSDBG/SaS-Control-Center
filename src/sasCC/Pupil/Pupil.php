<?php

namespace sasCC\upil;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pupil
 *
 * @author drak3
 */
class Pupil {
    protected $name;
    
    protected $class;
    
    protected $id;
    
    protected $company;
    
    protected $role;
    
    const ROLE_WORKER = 'worker';
    const ROLE_CHIEF = 'chief';
    
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
    
    public function getCompany() {
        return $this->company;
    }

    public function setCompany($company) {
        $this->company = $company;
    }

    public function getRole() {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }




}

?>
