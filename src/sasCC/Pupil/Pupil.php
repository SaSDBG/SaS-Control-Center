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
    protected $rawClass;

    /**
     * @ManyToOne(targetEntity="sasCC\Company\Company", cascade={"persist"})
     */
    protected $firstWish;

    /**
     * @ManyToOne(targetEntity="sasCC\Company\Company", cascade={"persist"})
     */
    protected $secondWish;

    /**
     * @ManyToOne(targetEntity="sasCC\Company\Company", cascade={"persist"})
     */
    protected $thirdWish;

    /**
     * @OneToOne(targetEntity="sasCC\Pupil\Pupil", inversedBy="pupils", cascade={"persist"})
     */
    protected $pupilLink;

    public function __construct() {
        $this->class = new SchoolClass();
    }

    public function isTeacher() {
        return $this->getClass()->isTeacher();
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

    public function getCompany() {
        return $this->company;
    }

    public function setCompany($company) {
        $this->company = $company;
    }
    
    public function getRawClass() {
        return isset($this->rawClass) ? $this->rawClass : $this->class->getFullClass();
    }
    
    public function setRawClass($class) {
        $this->rawClass = $class;
        $this->setClass(SchoolClass::parse($class));
    }
    
    public function getFirstWish()
    {
        return $this->firstWish;
    }

    public function setFirstWish($wish)
    {
        $this->firstWish = $wish;
    }
    
    public function getSecondWish()
    {
        return $this->secondWish;
    }
    
    public function setSecondWish($wish)
    {
        $this->secondWish = $wish;
    }
    
    public function getThirdWish()
    {
        return $this->thirdWish;
    }
    
    public function setThirdWish($wish)
    {
        $this->thirdWish = $wish;
    }
    
    public function getPupilLink()
    {
        return $this->pupilLink;
    }
    
    public function setPupilLink($link)
    {
        $this->pupilLink = $link;
    }
    
    public function getFullName() {
        return "{$this->lastName} {$this->firstName} ({$this->getClass()->getFullClass()})";
    }

    public function getName() {
        return "{$this->firstName} {$this->lastName}";
    }

}

?>
