<?php

namespace Lankar;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

use Lankar\LinksCollection;
use Lankar\Link;

class LinksControllerProvider implements ControllerProviderInterface {
  public function connect(Application $app) {
    $controller = new ControllerCollection();

    $controller->get('/', function() use ($app) {
			$links = LinksCollection::getCollection()->getAsArray();
			return $app['twig']->render('links.twig', array('links' => $links));
    });

    return $controller;
  }
}
