<?php

require_once __DIR__.'/../vendor/autoload.php';

require_once __DIR__.'/../config/config.php';

$app = new Silex\Application();

$app['debug'] = lankar_debug;

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
  'db.options' => $lankar_db_options
));
$app->mount('/', new Lankar\LinksControllerProvider());

$app->run();
