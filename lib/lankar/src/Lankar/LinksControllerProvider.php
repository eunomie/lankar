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
      $pagenumber = (int)$request->attributes->get('pagenumber');
      $query = 'SELECT "id" from links'; // order by date
      $res = $app['db']->fetchAll($query);
      $count = count($res);
      $start = min(($pagenumber - 1) * 10, $count);
      $stop = min($pagenumber * 10 + 1, $count);
      $linksid = array();
      for ($i = $start; $i < $stop; $i++) {
        array_push($linksid, intval($res[$i]['id']));
      }
      $stmt = $app['db']->executeQuery('SELECT "id", "url", "title", "hash", "desc", "date" from links where "id" in (?)', array($linksid), array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY));
      $links = $stmt->fetchAll();
      return $app->json(array(
        'links' => $links,
        'pagenumber' => $pagenumber,
        'total' => count($count / 10)
      ));
    });

    $controllers->get('/go/{hash}', function(Request $request) use ($app) {
      $hash = $request->attributes->get('hash');
      $query = 'SELECT "url" from links where "hash" = ?';
      $link = $app['db']->fetchAssoc($query, array($hash));

      return $app->redirect($link['url']);
    });

    $controllers->get('/link/{hashOrUrl}', function(Request $request) use ($app) {
      $hashOrUrl = $request->attributes->get('hashOrUrl');
      $query = 'SELECT "id", "url", "title", "hash", "desc", "date" from links';
      if (strlen($hashOrUrl) == 6 && 'http' != substr($hashOrUrl, 0, strlen('http'))) {
        $query .= ' where "hash" = ?';
      } else {
        // need to decode URL
        $query .= ' where "url" = ?';
      }
      $link = $app['db']->fetchAssoc($query, array($hashOrUrl));
      return $app->json($link);
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
      $arr = $link->asArray();
      $app['db']->insert('links', array(
        '"url"'    => $arr['url'],
        '"title"'  => $arr['title'],
        '"hash"'   => $arr['hash'],
        '"desc"'   => $arr['desc'],
        '"hash"'   => $arr['hash']
      ));

      return new Response('Link '.$link->hash().' created', 201);
    });

    return $controllers;
  }
}
