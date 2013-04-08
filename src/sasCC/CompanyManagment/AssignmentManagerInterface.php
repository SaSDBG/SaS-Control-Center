<?php
namespace sasCC\CompanyManagment;

/**
 *
 * @author drak3
 */
interface AssignmentManagerInterface {
    public function getUnassignedPupils();
    public function assign(\sasCC\Pupil\Pupil $pupil, \sasCC\Company\Company $company);
}

?>
