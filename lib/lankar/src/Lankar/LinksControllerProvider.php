<?php

namespace Lankar;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Lankar\Link;

class LinksControllerProvider implements ControllerProviderInterface {
  private $linksPerPage = 3;

  public function connect(Application $app) {
    $controllers = $app['controllers_factory'];

    $controllers->get('/links/{pagenumber}', function(Request $request) use ($app) {
      $pagenumber = (int)$request->attributes->get('pagenumber');
      $query = 'SELECT "id" from links order by "created_at" desc';
      $res = $app['db']->fetchAll($query);
      $count = count($res);
      $start = min(($pagenumber - 1) * $this->linksPerPage, $count);
      $stop = min($pagenumber * $this->linksPerPage, $count);
      $linksid = array();
      for ($i = $start; $i < $stop; $i++) {
        array_push($linksid, intval($res[$i]['id']));
      }
      $selectquery = <<<EOF
SELECT
  links."id", links."url", links."title", links."hash", links."desc", links."created_at",
  tags."name"
FROM links LEFT OUTER JOIN
  link_tags INNER JOIN
    tags ON link_tags.tag_id = tags.id
  ON links.id = link_tags.link_id
WHERE
  links."id" in (?)
order by created_at desc
EOF;
      $stmt = $app['db']->executeQuery($selectquery, array($linksid), array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY));
      $dblinks = $stmt->fetchAll();
      $links = array();
      foreach ($dblinks as $link) {
        $key = $link['id'];
        if (!array_key_exists($key, $links)) {
          $links[$key] = $link;
          $links[$key]['labels'] = array();
        }
        if (isset($link['name'])) {
          array_push($links[$key]['labels'], $link['name']);
        }
      }
      $arrlinks = array();
      foreach ($links as $key => $value) {
        array_push($arrlinks, $value);
      }
      return $app->json(array(
        'links' => $arrlinks,
        'pagenumber' => $pagenumber,
        'total' => ceil($count / $this->linksPerPage)
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
      $query = 'SELECT "id", "url", "title", "hash", "desc", "created_at" from links';
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
      $date = date("Y-m-d H:i:s");
      $link = new Link($url, $title, $desc, $labels, $date);
      $app['db']->insert('links', $link->asArray());

      return new Response('Link '.$link->hash().' created', 201);
    });

    return $controllers;
  }
}
