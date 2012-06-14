<?php

namespace Lankar;

class Link {
  private $id;
  private $sha1;
  private $url;

  function __construct($id, $url) {
    $this->id = $id;
    $this->hash = sha1($url);
    $this->url = $url;
  }

  public function id() {
    return $this->id;
  }

  public function hash() {
    return $this->hash;
  }

  public function url() {
    return $this->url;
  }
}
