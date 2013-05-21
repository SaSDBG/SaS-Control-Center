<?php

namespace sasCC\Import;
use sasCC\Pupil\Pupil as RealPupil;

/**
 * Description of CSVPupilImporter
 *
 * @author drak3
 */
class CSVPupilImporter {
    
    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;
    
    public function __construct(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
    }
    
    public function import($csvFile) {
        $parser = new CSVParser();
        $pupils = $parser->parse($csvFile);
        $pupilNo = 0;
        foreach($pupils as $pupil) {
            $pupilNo++;
            echo "inserting pupil no. $pupilNo\n";
            $this->insert($pupil);
        }
        //$this->em->flush();
    }
    
    protected function insert(Pupil $p) {
        $realPupil = new RealPupil();
        $realPupil->setFirstName($p->firstName);
        $realPupil->setLastName($p->lastName);
        $realPupil->setRawClass($p->rawClass);
        
        $company = $this->em->getRepository('sasCC\Company\Company')->find($p->companyID);
        $realPupil->setCompany($company);
        $this->em->persist($realPupil);
    }
}

?>
