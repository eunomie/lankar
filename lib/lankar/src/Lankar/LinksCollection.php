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
      new Link(
        'http://www.winsos.net/~yves',
        'Ceci **est** simplement mon blog personnel. Je le met à jour de manière assez irrégulière, même si ça commence à redevenir suffisamment fréquent.\n\nPour info le premier billet date de _septembre 2005_\n\n> Test de citation',
        array('next', 'perso'),
        '15 juin 2012'),
      new Link(
        'http://twitter.com/_crev_',
        "Mon compte twitter. Pas grand chose dessus, je m'en sert pas mal pour suivre des gens. Disons que je m'y met petit à petit.",
        array("next", "perso", "twitter"),
        "15 juin 2012"),
      new Link(
        "http://fabien.potencier.org/article/63/sami-yet-another-php-api-documentation-generator",
        "Générateur de documentation pour php, basé sur Silex, twig, ...",
        array("next", "devel", "php", "documentation"),
        "19 juin 2012")
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

  public function getJson() {
    $content = array(
      'links'      => $this->getAsArray(),
      'pagenumber' => 1,
      'total'      => 2
    );

    return $content;
  }
}
