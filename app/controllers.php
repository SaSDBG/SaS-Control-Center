<?php
use Symfony\Component\HttpFoundation\Request;
use sasCC\Company\Company;
use sasCC\CompanyManagment\Form\CompanyType;
use sasCC\CompanyManagment\Form\PupilType;
use sasCC\CompanyManagment\Form\ConstraintsType;
use sasCC\Pupil\Pupil;
use sasCC\Pupil\SchoolClass;
use sasCC\Company\AssignmentConstraints;

$app->get('/', function(Request $r) use ($app) {
    return $app->render('home.html.twig', array("title" => "SaS CP"));
})->bind('home');

$app->get('/test', function(Request $r) use ($app) {
    $testCompany = new Company();
    $testCompany->setName('foo');
    $testCompany->setNeeds('We need alot!!');
    
    $testClass = new SchoolClass();
    $testClass->setGrade('9');
    $testClass->setIdentifyer('b');
    
    $testPupil1 = new Pupil();
    $testPupil1->setClass($testClass);
    $testPupil1->setName('testie test');
    $testPupil1->setCompany($testCompany);
    
    $testPupil2 = new Pupil();
    $testPupil2->setClass($testClass);
    $testPupil2->setName('testine testchen');
    $testPupil2->setCompany($testCompany);
    
    $testCompany->setChiefs(array($testPupil1));
    
    $testConstraints = new AssignmentConstraints();
    $testConstraints->setMaximalGrade('K2');
    $testConstraints->setMinimalGrade('5');
    $testConstraints->setMaximalNumberOfWorkplaces(20);
    $testConstraints->setMinimalNumberOfWorkplaces(5);
    $testConstraints->setSpecialRules('special rule is special');
    
    $testCompany->setConstraints($testConstraints);
    
    $app['em']->persist($testCompany);
    $app['em']->flush();
    return 'success!!';
});

$app->match('/companies/add', function(Request $r) use ($app) {
    $company = new Company();
    $form = $app['form.factory']->create(new CompanyType(), $company);
    
    if($r->isMethod('POST')) {
        $form->bindRequest($r);
        if ($form->isValid()) {
            $app['em']->persist($company);
            $app['em']->flush();
            return 'success';
        }
    }
    
    return $app['twig']->render('company.add.html.twig', array('form' => $form->createView(), "title" => "Betrieb hinzufügen"));

})->bind('add_company');

?>
