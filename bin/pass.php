<?php
error_reporting(-1);
$app = require __DIR__.'/../app/app.php';
$toHash = $argv[1];
// find the encoder for a UserInterface instance
echo $app['security.encoder.digest']->encodePassword($toHash, '');
echo "\n";
?>
