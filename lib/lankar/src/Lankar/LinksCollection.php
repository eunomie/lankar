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
      '1' => new Link(1, 'f036cc20b5dadb35e0348895966ca03a6bf67a72', 'http://www.winsos.net/~yves'),
      '2' => new Link(2, '915481348948af7a11afe3d3e3f95a8a041fa5a3', 'http://twitter.com/_crev_')
    );
  }
}
