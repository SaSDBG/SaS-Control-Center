<?php
namespace sasCC\Company;

/**
 * Company-model class
 *
 * @author drak3
 * @ Entity @Table(name="companies")
 */
class Company {
    /**
     * @var String
     * 
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
    
    /**
     * @Id @Collumn(type="integer") @GeneratedValue
     * @var int
     */
    protected $id;
    
    public function getId() {
        return $this->id;
    }
    
    public function setId($id) {
        $this->id = $id;
    }
        
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
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
