<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app['debug'] = true;

$app->mount('/links', new Lankar\LinksControllerProvider());

$app->run();
