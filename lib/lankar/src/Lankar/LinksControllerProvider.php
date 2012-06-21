<?php

namespace Lankar;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Lankar\LinksCollection;
use Lankar\Link;

class LinksControllerProvider implements ControllerProviderInterface {
  public function connect(Application $app) {
    $controllers = $app['controllers_factory'];

    $controllers->get('/links/{pagenumber}', function(Request $request) use ($app) {
      $pagenumber = $request->attributes->get('name');
      $content = LinksCollection::getCollection()->getJson();
      return $app->json($content);
    });

    $controllers->get('/dblinks/{pagenumber}', function(Request $request) use ($app) {
      $links = $app['db']->fetchAll('SELECT "id", "url", "title", "hash", "desc", "date" from links');
      return $app->json(array(
        'links' => $links,
        'pagenumber' => 1,
        'total' => 2
      ));
    });

    $controllers->post('/link', function(Request $request) use ($app) {
      if (!$url = $request->get('url')) {
        return new Response('Missing url', 400);
      }
      $desc = $request->get('desc');
      if (!isset($desc)) {
        $desc = '';
      }
      $title = $request->get('title');
      if (!isset($title)) {
        $title = '';
      }
      $labels = $request->get('tags');
      if (!isset($desc) || !is_array($labels)) {
        $labels = array();
      } else {
        $labels = split('/[ ,]+/', $labels);
      }
      $date = date("d F Y H:m:s");
      $link = new Link($url, $title, $desc, $labels, $date);
      print_r($link);
      // save

      return new Response('Link '.$link->hash().' created', 201);
    });

    return $controllers;
  }
}
