<?php

namespace Lankar;

use Lankar\UrlUtils;
use Lankar\HashUtils;

class Link {
  private $id;
  private $hash;
  private $url;

  function __construct($id, $url) {
    $this->id = $id;
    $this->hash = HashUtils::small($url);
    $this->url = UrlUtils::clean_url($url);
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

	public function asArray() {
		return array(
			'id' => $this->id,
			'hash' => $this->hash,
			'url' => $this->url
		);
	}
}
