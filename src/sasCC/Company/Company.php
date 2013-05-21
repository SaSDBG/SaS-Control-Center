<?php
namespace sasCC\Company;
use sasCC\Pupil\Pupil;

/**
 * Company-model class
 *
 * @author drak3
 * @Entity
 * @Table(name="companies")
 */
class Company { 
    
    /**
     * @var String
     * @Column
     */
    protected $name;
    
    /**
     * @Column(type="text", nullable=true)
     * @var String
     */
    protected $needs = '';
    
    /**
     * @Column(type="text")
     */
    protected $description = '';
    
    /**
     * @OneToMany(targetEntity="sasCC\Pupil\Pupil", mappedBy="company", cascade={"persist"})
     */
    protected $members;
    
    /**
     *
     * @ManyToMany(targetEntity="\sasCC\Pupil\Pupil", cascade={"persist"})
     * @JoinTable(name="company_chiefs",
     *            joinColumns={@JoinColumn(name="company_id", referencedColumnName="id")},
     *            inverseJoinColumns={@JoinColumn("pupil_id", referencedColumnName="id", unique=true)}
     *           )
     */
    protected $chiefs;
    
    /**
     * @Column
     */
    protected $category;
    
    /**
     * @Column(type="text", nullable=true)
     */
    protected $room;
    
    /**
     * @Column(type="boolean")
     */
    protected $isMarkedToDelete = false;
    
    /**
     * @var int
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;
       
    public function __construct() {
        $this->members = new \Doctrine\Common\Collections\ArrayCollection();
        $this->chiefs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->chiefs = array(
          new Pupil,  
        );
    }
    
    public function getIsMarkedToDelete() {
        return $this->isMarkedToDelete;
    }

    public function setIsMarkedToDelete($isMarkedToDelete) {
        $this->isMarkedToDelete = $isMarkedToDelete;
    }

    public function isMarkedToDelete() {
        return $this->getIsMarkedToDelete();
    }
        
    public function getRoom() {
        return $this->room;
    }

    public function setRoom($room) {
        $this->room = $room;
    }

        
    public function getCategory() {
        return $this->category;
    }

    public function setCategory($category) {
        $this->category = $category;
    }

        
    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

        
    public function getMembers() {
        return $this->members;
    }

    public function setMembers($members) {
        $this->members = $members;
    }

    public function getChiefs() {
        return $this->chiefs;
    }

    public function setChiefs($chiefs) {
        $this->chiefs = $chiefs;
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
        return $this->needs;
    }

    public function setNeeds($needed) {
        $this->needs = $needed;
    }




}

?>
