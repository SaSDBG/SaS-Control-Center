<?php
use Symfony\Component\HttpFoundation\Request;

$app->get('/', function(Request $r) use ($app) {
    return $app->render('home.html.twig', array("title" => "SaS CP"));
})->bind('homepage');

$app->get('/test', function(Request $r) use ($app) {
    $app['companies.schemaManager']->createTables();
    return 'success!!';
});

$app->match('/companies/add', function(Request $r) use ($app) {
    $data = array(
        'name' => 'Project leader',
        'class' => 'K1',
    );
    $form = $app->form($data)
                ->add('name')
                ->add('class')->getForm();
    if($r->isMethod('POST')) {
        $form->bind($r);

        if ($form->isValid()) {
            $data = $form->getData();

            var_dump($data);

            // redirect somewhere
            return '';
        }
    }
    return $app['twig']->render('formtest.html.twig', array('form' => $form->createView()));

})->bind('add_company');

?>
