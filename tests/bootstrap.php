<?php

// composer autoload
require __DIR__ . "/../vendor/autoload.php";

$dotenv = new \Dotenv\Dotenv(base_path(), ".env.testing");
$dotenv->overload();

$app = include __DIR__ . "/../bootstrap/app.php";