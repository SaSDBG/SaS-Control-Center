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
       /* if($this->start instanceof \DateTime)
            return $this->start;
        else
            return \DateTime::createFromFormat ("dd.mm.YYYY HH:ii:ss", $this->start);*(
        */
        return $this->start;
    }
    
    public function setStart($start) {
        //$this->start = \DateTime::createFromFormat("dd.mm.YYYY HH:ii:ss", $start);
        $this->start = $start;
        
    }
    
    public function getEnd() {
    /*    if($this->end instanceof \DateTime)
            return $this->end;
        else 
            return \DateTime::createFromFormat ("dd.mm.YYYY HH:ii:ss", $this->end);*/
        return $this->end;
    }
    
    public function setEnd($end) {
      //  $this->end = \DateTime::createFromFormat("dd.mm.YYYY HH:ii:ss", $end);
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
