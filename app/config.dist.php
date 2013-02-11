<?php
//configure and move to config.php
$app['db.params'] = array(
    'driver' => 'pdo_mysql', 
    'user' => '',
    'password' => '',
    'dbname' => '',
    'host' => 'localhost'
);

$app['users'] = array(
    'user' => array('ROLE', 'pass')
);
        
$app['debug'] = false;
        

?>
