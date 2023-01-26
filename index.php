<?php

require 'vendor/autoload.php';
$f3 = \Base::instance();

// Initialize config
$f3->config('app/config.ini');

// Define routes
$f3->config('app/routes.ini');

// Execute application
$f3->run();
