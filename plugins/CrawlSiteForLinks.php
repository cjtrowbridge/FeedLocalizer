<?php

function CrawlSiteForLinks($URL,$Depth = 5,$Pattern = false){
  global $CrawlSiteLinks;
  $CrawlSiteLinks=array();
  
  CrawlSiteForLinksRecurse($URL, $Depth,$Pattern);
  
  $Temp = $CrawlSiteLinks;
  unset($CrawlSiteLinks);
  return $Temp;
}

function CrawlSiteForLinksRecurse($URL,$Depth = 5, $Pattern = false){
  $Page = CacheURL($URL);
  //preg_match('/href=(["\'])([^\1]*)\1/i', $Page, $Links);
  
  $re = '/\href=(["\'])(.*?)\1/';
  preg_match_all($re, $Page, $Links, PREG_SET_ORDER, 0);
  
  pd($Links);
  
  pd($Page);
  exit;
  
  foreach($Links as $Link){
    if($Pattern){
      if(strpos($Link,$Pattern)===false){
        continue;
      }
    }
    
    if(substr($Link,0,1)=='/'){
      $Link = $URL.$Link;
      $Link = str_replace('//','/',$Link);
    }
    
    global $CrawlSiteLinks;
    $CrawlSiteLinks[$Link]=$Link;
    
    if($Depth>0){
      //check if internal link
      echo 'need to recurse: '.$Link;
    }
    
  }
}

function crawl_page_recursive($url, $depth = 5,$Pattern = false){
  set_time_limit(0);
  $seen = array();
  if(($depth == 0) or (in_array($url, $seen))){
      return;
  }   
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
  $result = curl_exec ($ch);
  curl_close ($ch);
  if( $result ){
      $stripped_file = strip_tags($result, "<a>");
      preg_match_all("/<a[\s]+[^>]*?href[\s]?=[\s\"\']+"."(.*?)[\"\']+.*?>"."([^<]+|.*?)?<\/a>/", $stripped_file, $matches, PREG_SET_ORDER ); 
      foreach($matches as $match){
        pd($match);
        continue;
          $href = $match[1];
              if($Pattern){
                if(false === strpos($href, $Pattern)){
                  continue;
                }
              }
              if (0 !== strpos($href, 'http')) {
                  $path = '/' . ltrim($href, '/');
                  if (extension_loaded('http')) {
                      $href = http_build_url($href , array('path' => $path));
                  } else {
                      $parts = parse_url($href);
                      $href = $parts['scheme'] . '://';
                      if (isset($parts['user']) && isset($parts['pass'])) {
                          $href .= $parts['user'] . ':' . $parts['pass'] . '@';
                      }
                      $href .= $parts['host'];
                      if (isset($parts['port'])) {
                          $href .= ':' . $parts['port'];
                      }
                      $href .= $path;
                  }
              }
              crawl_page_recursive($href, $depth - 1);
          }
  }   
  //echo "Crawled {$href}";
  global $CrawlSiteLinks;
  $CrawlSiteLinks[]=$href;
}
