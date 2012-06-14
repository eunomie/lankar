<?php

namespace Lankar;

class Link {
  private $id;
  private $sha1;
  private $url;


  public static function smallHash($text) {
    // Shaarli 0.0.38 beta - Shaare your links...
    // The personal, minimalist, super-fast, no-database delicious clone. By sebsauvage.net
    // http://sebsauvage.net/wiki/doku.php?id=php:shaarli
    // Licence: http://www.opensource.org/licenses/zlib-license.php
    /* Returns the small hash of a string
       eg. smallHash('20111006_131924') --> yZH23w
       Small hashes:
         - are unique (well, as unique as crc32, at last)
         - are always 6 characters long.
         - only use the following characters: a-z A-Z 0-9 - _ @
         - are NOT cryptographically secure (they CAN be forged)
       In Shaarli, they are used as a tinyurl-like link to individual entries.
    */
    $sh = rtrim(base64_encode(hash('crc32',$text,true)),'=');
    $sh = str_replace('+','-',$sh); // Get rid of characters which need encoding in URLs.
    $sh = str_replace('/','_',$sh);
    $sh = str_replace('=','@',$sh);

    return $sh;
  }

  function __construct($id, $url) {
    $this->id = $id;
    $this->hash = self::smallHash($url);
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
