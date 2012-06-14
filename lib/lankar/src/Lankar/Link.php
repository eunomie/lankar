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

  private static function cleanUrl($url) {
    $parsed = parse_url($url);
    // remove utm_* in query
    if (isset($parsed['query']) && strlen($parsed['query']) > 0) {
      parse_str($parsed['query'], $args);
      foreach ($args as $key => $value) {
        if (preg_match('/^utm_/', $key) > 0) {
          unset($args[$key]);
        }
      }
      $parsed['query'] = http_build_query($args);
      if (strlen($parsed['query']) == 0) {
        unset($parsed['query']);
      }
    }
    // remove xtor in fragment
    if (isset($parsed['fragment']) && strlen($parsed['fragment']) > 0) {
      if (preg_match('/^xtor=RSS-/', $parsed['fragment']) > 0) {
        unset($parsed['fragment']);
      }
    }
    return self::unparse_url($parsed);
  }

  private function unparse_url($parsed_url) { 
    $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : ''; 
    $host     = isset($parsed_url['host']) ? $parsed_url['host'] : ''; 
    $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : ''; 
    $user     = isset($parsed_url['user']) ? $parsed_url['user'] : ''; 
    $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : ''; 
    $pass     = ($user || $pass) ? "$pass@" : ''; 
    $path     = isset($parsed_url['path']) ? $parsed_url['path'] : ''; 
    $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : ''; 
    $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : ''; 
    return "$scheme$user$pass$host$port$path$query$fragment"; 
  }

  function __construct($id, $url) {
    $this->id = $id;
    $this->hash = self::smallHash($url);
    $this->url = self::cleanUrl($url);
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
