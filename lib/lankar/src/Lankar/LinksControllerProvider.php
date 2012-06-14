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

    $controller->get('/', function() {
      $links = LinksCollection::getCollection();
      $output = '<ul>';
      foreach ($links->get() as $id => $link) {
        $output .= '<li><a href="'.$link->url().'" id="'.$link->hash().'">'.$link->url().'</a></li>';
      }
      $output .= '</ul>';

      return $output;
    });

    return $controller;
  }
}
