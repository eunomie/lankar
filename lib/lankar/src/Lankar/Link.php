<?php

namespace Lankar;

use Lankar\UrlUtils;
use Lankar\HashUtils;


class Link {
  protected $id;
  protected $url;
  protected $title;
  protected $hash;
  protected $desc;
  protected $labels;
  protected $date;
  

  function __construct($url, $title, $desc, $labels, $date) {
    $this->url = UrlUtils::clean_url($url);
    $this->title = $title;
    $this->hash = HashUtils::small($this->url);
    $this->desc = $desc;
    $this->labels = $labels;
    $this->date = $date;
  }

  public function hash() {
    return $this->hash;
  }

	public function asArray() {
		return array(
			'"url"'    => $this->url,
      '"title"'  => $this->title,
      '"hash"'   => $this->hash,
      '"desc"'   => $this->desc,
      //'"labels"' => $this->labels,
      '"created_at"'   => $this->date
		);
	}
}
