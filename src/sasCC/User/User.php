<?php
namespace sasCC\User;
use Symfony\Component\Validator\ExecutionContext;

/**
 * Description of User
 *
 * @author drak3
 * @Entity
 * @Table(name="users")
 */
class User implements \Symfony\Component\Security\Core\User\UserInterface{
    
    /**
     * @Id @Column(type="integer") @GeneratedValue
     */
    protected $id;
    
    /**
     * @Column
     */
    protected $userName;
    
    /**
     * @Column
     */
    protected $password;
    
    /**
     * @Column
     */
    protected $salt;
    
    /**
     * @Column
     */
    protected $roles;
    
    /**
     * @Column
     */
    protected $area;
    
    /**
     * @Column
     */
    protected $privileges;
    
    /**
     * @Column(type="boolean", nullable=false)
     */
    protected $firstPass = true;
    
    protected $plainPass;
    protected $plainPassSave;
    
    public function getFirstPass() {
        return $this->firstPass;
    }
    
    public function isFirstPass() {
        return $this->getFirstPass();
    }

    public function setFirstPass($firstPass) {
        $this->firstPass = $firstPass;
    }

        
    public function getPlainPassSave() {
        return $this->plainPassSave;
    }

    public function setPlainPassSave($plainPassSave) {
        $this->plainPassSave = $plainPassSave;
    }
    
    public function getPlainPass() {
        return $this->plainPass;
    }

    public function setPlainPass($plainPass) {
        $this->plainPass = $plainPass;
    }

        
    public function getArea() {
        return $this->area;
    }

    public function setArea($area) {
        $this->area = $area;
        $this->refreshRoles();
    }

    public function getPrivileges() {
        return $this->privileges;
    }

    public function setPrivileges($privileges) {
        $this->privileges = $privileges;
        $this->refreshRoles();
    }
    
    protected function refreshRoles() {
        $role = 'ROLE_';
        $this->getArea() === 'ALLE' ? $area = '' : $area = $this->getArea().'_';
        $role .= $area;
        $role .= $this->getPrivileges();
        $this->setRoles(array($role));
    }

        
    public function eraseCredentials() {
        $this->setPlainPass('');
    }

    public function getId() {
        return $this->id;
    }

    public function getUserName() {
        return $this->userName;
    }

    public function setUserName($userName) {
        $this->userName = $userName;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getSalt() {
        return $this->salt;
    }

    public function setSalt($salt) {
        $this->salt = $salt;
    }

    public function getRoles() {
        return \explode(',', $this->roles);
    }

    public function setRoles($roles) {
        $this->roles = \implode(',', $roles);
    }
}

?>
