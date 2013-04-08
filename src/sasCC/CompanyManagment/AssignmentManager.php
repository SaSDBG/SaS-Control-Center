<?php
namespace sasCC\CompanyManagment;
use sasCC\Pupil\Pupil;
use sasCC\Company\Company;

/**
 * Description of AssignmentManager
 *
 * @author drak3
 */
class AssignmentManager implements AssignmentManagerInterface {
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    
    public function __construct(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
    }
    
    public function getUnassignedPupils() {
        $query = $this->em->createQuery("SELECT u FROM sasCC\Pupil\Pupil u WHERE (u.company = null)");
        return $query->getResult();
    }
    
    public function assign(Pupil $pupil, Company $company) {
        if(($errors = $this->getAssignmentErrors($pupil, $company)) === []) {
            $this->doAssign($pupil, $company);
        }
        return $errors;
    }
    
    protected function getAssignmentErrors($pupil, $company) {
        $errors = [];
        $this->checkIfCompanyIsFull($company, $pupil, $errors);
        $this->checkIfClassConstraintsAreMatched($company, $pupil, $errors);
        
        return $errors;
    }
    
    protected function checkIfCompanyIsFull(Company $company, Pupil $pupil,  &$errors) {
        $maxWorkplaces = $company->getConstraints()->getMaximalNumberOfWorkplaces();
        $currentWorkplaces = count($company->getMembers());
        $minDiff = $pupil->getPupilLink() === null ? 1 : 2;
        if($maxWorkplaces - $currentWorkplaces < $minDiff) {
            $errors[] = "Der Gewünschte Betrieb ist voll";
        }
    }
    
    protected function checkIfClassConstraintsAreMatched(Company $company, Pupil $pupil, &$errors) {
        $class = $pupil->getClass();
        $minClass = $company->getConstraints()->getMinimalGrade();
        $maxClass = $company->getConstraints()->getMaximalGrade();
        if(!$class->isInBounds($minClass, $maxClass)) {
            $errors[] = "Der Gewünschte Betrieb ist nur für Schüler der Klassen $minClass bis $maxClass wählbar";
        }
    }
    
    protected function doAssign(Pupil $p, Company $c) {
        $p->setCompany($c);
        $this->em->flush($p);
    }
}

?>
