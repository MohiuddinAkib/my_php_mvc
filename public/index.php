<?php

use App\libraries\Route;

// Autoload all class with composer
require_once '../vendor/autoload.php';
// Require bootstrap.php
require_once '../app/bootstrap.php';
// Requiring helpers 
require_once ROOT_URL . '/app/helpers/helpers.php';

// Load .env file
$dotenv = new Dotenv\Dotenv(ROOT_URL);
$dotenv->load();

// Instantiate Route class
$route = new Route();

// Requiring routes file
require_once APP_URL . '/routes.php';



