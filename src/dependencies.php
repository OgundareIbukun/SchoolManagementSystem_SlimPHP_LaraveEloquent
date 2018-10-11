<?php
// DIC configuration

/** @var Pimple\Container $container */

use Main\Middleware\OptionalAuth;
use League\Fractal\Manager;
use League\Fractal\Serializer\ArraySerializer;

$container = $app->getContainer();


// Error Handler
$container['errorHandler'] = function ($c) {
    return new \Main\Exceptions\ErrorHandler($c['settings']['displayErrorDetails']);
};

// App Service Providers
$container->register(new \Main\Services\Database\EloquentServiceProvider());
$container->register(new \Main\Services\Auth\AuthServiceProvider());

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];

    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));

    return $logger;
};

// Jwt Middleware
$container['jwt'] = function ($c) {

    $jws_settings = $c->get('settings')['jwt'];

    return new \Slim\Middleware\JwtAuthentication($jws_settings);
};

$container['optionalAuth'] = function ($c) {
  return new OptionalAuth($c);
};


// Request Validator
$container['validator'] = function ($c) {
    \Respect\Validation\Validator::with('\\Main\\Validation\\Rules');

    return new \Main\Validation\Validator();
};

// Fractal
$container['fractal'] = function ($c) {
    $manager = new Manager();
    $manager->setSerializer(new ArraySerializer());

    return $manager;
};