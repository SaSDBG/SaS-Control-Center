<?php
namespace sasCC\Broadcast;

/**
 * Broadcast-model class
 * @Entity
 * @Table(name="broadcasts")
 */
class Broadcast { 
    
    /**
     * @var String
     * @Column
     */
    protected $name;
    
    /**
     * @Column(type="text")
     */
    protected $content = '';
    
    /**
     * @Column(type="datetime", nullable=true)
     */
    protected $start;
    
    /**
     * @Column(type="datetime", nullable=true)
     */
    protected $end;
    
    /**
     * @Column(type="integer", nullable=true)
     */
    protected $type;
    /**
     * @var int
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;
       
    public function __construct() {
    }   
    
    public function getId() {
        return $this->id;
    }        
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
    
    public function getContent() {
        return $this->content;
    }
    
    public function setContent($content) {
        $this->content = $content;
    }
    
    public function getStart() {
        return $this->start;
    }
    
    public function setStart($start) {
        $this->start = $start;
    }
    
    public function getEnd() {
        return $this->end;
    }
    
    public function setEnd($end) {
        $this->end = $end;
    }
    
    public function getType() {
        return $this->type;
    }
    
    public function setType($type) {
        $this->type = $type;
    }
}
?>
