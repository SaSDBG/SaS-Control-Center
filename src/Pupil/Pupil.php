<?php

namespace sasCC\Entities\Pupil;
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


}

?>
