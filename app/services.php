<?php

$app['debug'] = false;

$app->register(new \Silex\Provider\FormServiceProvider());

$app->register(new \Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => array(ROOT, ROOT.'Resources/templates/'),
));


$app->register(new \Silex\Provider\TranslationServiceProvider(), array(
  'locale' => 'de',
  'translator.messages' => array()
));

$app['translator']->addLoader('xlf', new \Symfony\Component\Translation\Loader\XliffFileLoader());
$app['translator']->addResource('xlf', ROOT.'vendor/symfony/symfony/src/Symfony/Component/Validator/Resources/translations/validators.de.xlf', 'de', 'validators');

$app->register(new \Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new \Silex\Provider\ValidatorServiceProvider());

$app->register(new \Silex\Provider\SwiftmailerServiceProvider());


$app['db.params'] = array(
    'driver' => 'pdo_mysql',
    'user' => 'root',
    'password' => 'foobar',
    'database' => 'symfony'
);


$app['db.connection'] = $this->share(function($c) {
    return \Doctrine\DBAL\DriverManager::getConnection($c['db.params']);
});



