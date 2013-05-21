<?php

error_reporting(-1);
$app = require __DIR__.'/../app/app.php';
$file = $argv[1];

$importer = new sasCC\Import\CSVPupilImporter($app['em']);
echo "importing...\n";
$importer->import($file);
echo "\n";

?>
