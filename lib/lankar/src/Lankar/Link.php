<?php

namespace Lankar;

class Link {
  public $id;
  private $hash;
  public $url;

  function __construct($id, $hash, $url) {
    $this->id = $id;
    $this->hash = $hash;
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
