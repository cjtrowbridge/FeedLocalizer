<?php

function CrawlDomainForLinks($URL,$Depth = 5,$Pattern = false){
  global $CrawlSiteLinks;
  $CrawlSiteLinks=array();
  
  CrawlDomainForLinksRecurse($URL, $Depth,$Pattern);
  
  $Temp = $CrawlSiteLinks;
  unset($CrawlSiteLinks);
  return $Temp;
}

function CrawlDomainForLinksRecurse($URL,$Depth = 5, $Pattern = false){
  $Page = CacheURL($URL);
  //preg_match('/href=(["\'])([^\1]*)\1/i', $Page, $Links);
  
  $Exploded = explode('href=',$Page);
  
  unset($Exploded[0]);
  $Links = array();
  
  foreach($Exploded as $Link){
    if(trim($Link)==''){
      continue;
    }
    $Delimiter = substr($Link,0,1);
    if(!(
      $Delimiter=='"'||$Delimiter=="'"
    )){
      //RUH ROH
      echo '<p>idk what to do with this: '.$Link.'</p>';
      continue;
    }
    
    $Link = substr($Link,1);
    $Length = strpos($Link,$Delimiter);
    $Link = substr($Link,0,$Length);
    $Links[$Link]=$Link;
    
  }
}
