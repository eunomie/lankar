<?php

namespace Lankar;

/**
* Url utils
*/
class UrlUtils {
  private function __construct() {}

  public static function unparse_url($parsed_url) { 
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

  /**
   * Clean an url by removing utm_* or xtor tracking elements
   */
  public static function clean_url($url) {
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
}

