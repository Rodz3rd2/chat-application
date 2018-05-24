<?php

// composer autoload
require __DIR__ . "/../vendor/autoload.php";

// our application
$app = include __DIR__ . "/../bootstrap/app.php";

$app->run();