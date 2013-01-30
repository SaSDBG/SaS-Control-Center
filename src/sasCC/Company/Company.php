<?php
namespace sasCC\Company;
use sasCC\Pupil\Pupil;

/**
 * Company-model class
 *
 * @author drak3
 * @Entity
 */
class Company {
    
    /**
     * @var String
     * @Column
     */
    protected $name;
        
    /**
     * @OneToOne(targetEntity="AssignmentConstraints", cascade={"persist"})
     */
    protected $constraints;
    
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
     * @var int
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;
    
    public function __construct() {
        $this->members = new \Doctrine\Common\Collections\ArrayCollection();
        $this->chiefs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->constraints = new AssignmentConstraints();
        $this->chiefs = array(
          new Pupil,  
        );
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
