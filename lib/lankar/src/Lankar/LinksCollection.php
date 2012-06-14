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
      '2' => new Link(2, 'http://twitter.com/_crev_'),
      '3' => new Link(3, 'http://n.survol.fr/n/document-store-a-recommander?utm_source=dlvr.it&utm_medium=twitter&utm_campaign=document-store-a-recommander'),
      '4' => new Link(4, 'http://www.usinenouvelle.com/article/fleur-pellerin-dans-les-allees-de-futur-en-seine.N176704#xtor=RSS-215')
    );
  }
	
	public function getAsArray() {
		$collection = $this->get();
		$arr = array();
		foreach ($collection as $link) {
			array_push($arr, $link->asArray());
		}
		return $arr;
	}
}
