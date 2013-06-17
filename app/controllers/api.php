<?php
use Symfony\Component\HttpFoundation\Request;

// Returns all companies as JSON
$app->match('/api/companies', function(Request $r) use ($app) {
   
    $searchQuery = $r->get("q");
         
    // If no query is passed
    if($searchQuery == NULL)
    {
        $companies = $app['em']->getRepository('sasCC\Company\Company')
                               ->findAll();
    }
    // If query is passed
    else
    {
        $query = $app['em']->createQuery("SELECT u FROM sasCC\Company\Company u WHERE ( 
            u.name LIKE :query OR
            u.description LIKE :query           
            )");
        $query->setParameter("query", "%$searchQuery%");
        
        $companies = $query->getResult();
    }
    
    $data = array();
    
    foreach($companies as $company)
    {
        $tokens = array();
        $tokens[] = $company->getName();
        
        foreach($company->getChiefs() as $chief)
        {
           // $tokens[] = $chief->getFirstName();
           // $tokens[] = $chief->getLastName();
        }
       
        $chiefHtml = $app['twig']->render('api/chiefs.twig', array("chiefs" => $company->getChiefs()));
        
        $data[] = array(
            "id" => $company->getId(),
            "name" => $company->getName(),
            "category" => $company->getCategory(),
            "description" => $company->getDescription(),
            "chiefs" => $chiefHtml,
            
            "value" => $company->getName(),
            "tokens" => $tokens,
            "type" => "company"
        );
    }
    
    return json_encode($data);
   
})
->bind('api_companies')
->secure('ROLE_WIRTSCHAFT_PRIV');

// Returns all pupils as JSON
$app->match('/api/persons', function(Request $r) use ($app) {
        $searchQuery = $r->get("q");
         
    // If no query is passed
    if($searchQuery == NULL)
    {
        $pupils = $app['em']->getRepository('sasCC\Pupil\Pupil')
                               ->findAll();
    }
    // If query is passed
    else
    {
        $query = $app['em']->createQuery("SELECT u FROM sasCC\Pupil\Pupil u WHERE ( 
            u.firstName LIKE :query OR
            u.lastName LIKE :query
            )");
        $query->setParameter("query", "%$searchQuery%");
        
        $pupils = $query->getResult();
    }
    
    $data = array();
    
    foreach($pupils as $pupil)
    {
        $tokens = array();
        $tokens[] = $pupil->getName();
        $tokens[] = $pupil->getFirstName();
        $tokens[] = $pupil->getLastName();
   
        $data[] = array(
            "id" => $pupil->getId(),
            "name" => $pupil->getName(),
            "class" => $pupil->getClass()->getFullClass(),
            
            "value" => $pupil->getName(),
            "tokens" => $tokens,
            "type" => "pupil"
        );
    }
    
    return json_encode($data);
})
->bind('api_persons')
->secure('ROLE_WIRTSCHAFT_PRIV');
?>
