<?php

namespace Lankar;

use Lankar\UrlUtils;
use Lankar\HashUtils;


/** @Entity **/
class Link {
  /** @Id @GeneratedValue @Column(type="integer") **/
  protected $id;
  /** @Column(type="string") **/
  protected $url;
  /** @Column(type="string") **/
  protected $title;
  /** @Column(type="string") **/
  protected $hash;
  /** @Column(type="text") **/
  protected $desc;
  //protected $labels;
  /** @Column(type="string") **/
  protected $date;
  

  function __construct($url, $title, $desc, $labels, $date) {
    $this->url = UrlUtils::clean_url($url);
    $this->title = $title;
    $this->hash = HashUtils::small($this->url);
    $this->desc = $desc;
    $this->labels = array(); //$labels;
    $this->date = $date;
  }

  public function hash() {
    return $this->hash;
  }

	public function asArray() {
		return array(
			'url'    => $this->url,
      'title'  => $this->title,
      'hash'   => $this->hash,
      'desc'   => $this->desc,
      'labels' => $this->labels,
      'hash'   => $this->hash
		);
	}
}
