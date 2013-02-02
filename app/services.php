<?php


use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;


/**
 * Depends on db config in config.php
 */
require 'config.php';

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


$app['em.entity-paths'] = array(ROOT."/src/sasCC/Company", ROOT."/src/sasCC/Pupil", ROOT.'/src/sasCC/User');

$app['em.config'] = $app->share(function($app) {
    return Setup::createAnnotationMetadataConfiguration($app['em.entity-paths'], $app['debug']);
});

$app['em'] = $app->share(function($app) {
    return EntityManager::create($app['db.params'], $app['em.config']);
});


$app->register(new Silex\Provider\SessionServiceProvider());

$app['route_class'] = '\sasCC\Route';

$app['user_provider.orm'] = $app->share(function($app) {
   return new \sasCC\User\EntityUserProvider($app['em']); 
});

$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => [
        'unsecured' => [
          'pattern' => '^/login$',
          'anonymous' => true,
        ],
        'secured' => [
            'pattern' => '^/.*$',
            'anonymous' => false,
            'form' => array('login_path' => '/login', 'check_path' => '/check'),
            'logout' => array('logout_path' => '/logout'),
            'users' => $app['user_provider.orm'],
        ]
    ]
));

$app['security.role_hierarchy'] = [
    'ROLE_ADMIN' => ['ROLE_PRIV'],
    'ROLE_PRIV' => ['ROLE_WIRTSCHAFT_ADMIN', 'ROLE_POLITIK_ADMIN', 'ROLE_SONSTIGES_ADMIN', 'ROLE_FINANZEN_ADMIN'],
    
    'ROLE_WIRTSCHAFT_ADMIN'  => ['ROLE_WIRTSCHAFT_PRIV'],
    'ROLE_WIRTSCHAFT_PRIV'   => ['ROLE_WIRTSCHAFT_CREATE'],
    'ROLE_WIRTSCHAFT_CREATE' => [],
    
    'ROLE_POLITIK_ADMIN'     => ['ROLE_POLITIK_PRIV'],
    'ROLE_POLITIK_PRIV'      => ['ROLE_POLITIK_CREATE'],
    'ROLE_POLITIK_CREATE'    => [],
    
    'ROLE_SONSTIGES_ADMIN'   => ['ROLE_SONSTIGES_PRIV'],
    'ROLE_SONSTIGES_PRIV'    => ['ROLE_SONSTIGES_CREATE'],
    'ROLE_SONSTIGES_CREATE'  => [],
    
    'ROLE_FINANZEN_ADMIN'    => ['ROLE_FINANZEN_PRIV'],
    'ROLE_FINANZEN_PRIV'     => ['ROLE_FINANZEN_CREATE'],
    'ROLE_FINANZEN_CREATE'   => [],
];

