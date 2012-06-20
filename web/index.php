<?php

require_once __DIR__.'/../vendor/autoload.php';

require_once __DIR__.'/config/config.php';

$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
  'twig.path' => __DIR__.'/../views',
));
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
  'db.options' => $lankar_db_options
));
$app->mount('/links', new Lankar\LinksControllerProvider());

$app->run();
