<?php


use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;


/**
 * Depends on db config in config.php
 */
if(canInclude('config.php')) {
    require 'config.php';
} else {
    require 'config.dist.php';
}

$app['debug'] = true;

$app->register(new \Silex\Provider\FormServiceProvider());

$app->register(new \Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => array(ROOT, ROOT.'/resources/tpl/'),
));


$app->register(new \Silex\Provider\TranslationServiceProvider(), array(
  'locale' => 'de',
  'translator.messages' => array()
));

//$app['translator']->addLoader('xlf', new \Symfony\Component\Translation\Loader\XliffFileLoader());
//$app['translator']->addResource('xlf', ROOT.'/vendor/symfony/symfony/src/Symfony/Component/Validator/Resources/translations/validators.de.xlf', 'de', 'validators');

$app->register(new \Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new \Silex\Provider\ValidatorServiceProvider());

$app->register(new \Silex\Provider\SwiftmailerServiceProvider());





$app['db.connection'] = $app->share(function($c) {
    return \Doctrine\DBAL\DriverManager::getConnection($c['db.params']);
});

$app['companies.schemaManager'] = $app->share(function($app){
    return new sasCC\CompanyManagment\SchemaManager($app['db.connection']);
});


$app['em.entity-paths'] = array(ROOT."/src/sasCC/Company", ROOT."/src/sasCC/Pupil");

$app['em.config'] = $app->share(function($app) {
    return Setup::createAnnotationMetadataConfiguration($app['em.entity-paths'], $app['debug']);
});

$app['em'] = $app->share(function($app) {
    return EntityManager::create($app['db.params'], $app['em.config']);
});



function canRequire($file) {
    return is_file($file) && is_readable($file);
}