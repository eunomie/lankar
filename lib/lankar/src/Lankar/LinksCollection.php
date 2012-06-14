<?php

namespace Lankar;

use Lankar\Link;

class LinksCollection {
  public static function getCollection() {
    return new LinksCollection();
  }

  private function __construct() {}

  public function get() {
    return array(
      '1' => new Link(1, 'http://www.winsos.net/~yves'),
      '2' => new Link(2, 'http://twitter.com/_crev_')
    );
  }
}
