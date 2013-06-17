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
     * @Column(type="boolean", nullable=false)
     */
    protected $isManual = false;
    
    /**
     * @Column(type="boolean", nullable=false)
     */
    protected $isActive = true;

    /**
     * @Column(type="boolean", nullable=false)
     */
    protected $isVisible = true;
    
    /**
     * @var int
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;
       
    public function __construct() {
        $this->start = new \DateTime();
        $this->end = new \DateTime();
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
    
    public function getStartFormat() {
        return $this->start->format("d.m.Y H:i:s");
    }
            
    public function setStart($start) {
        $this->start = $start;       
    }
    
    public function getEnd() {
        return $this->end;
    }
    
    public function getEndFormat () {
        return $this->end->format("d.m.Y H:i:s");
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
    
    public function getIsManual() {
        return $this->isManual;
    }
    
    public function isManual() {
        return $this->getIsManual();
    }
    
    public function setIsManual($isManual) {
        $this->isManual = $isManual;
    }
    
    public function getIsActive() {
        return $this->isActive;
    }
    
    public function isActive() {
        return $this->getIsActive();
    }
    
    public function setIsActive($isActive) {
        $this->isActive = $isActive;
    }
    
    public function getIsVisible() {
        return $this->isVisible;
    }
    
    public function isVisible() {
        return $this->getIsVisible();
    }
    
    public function setIsVisible($isVisible) {
        $this->isVisible = $isVisible;
    }
}
?>
