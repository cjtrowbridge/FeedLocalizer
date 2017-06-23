<?php

function CrawlDomainForLinks($URL,$Depth = 5){
  global $CrawlSiteLinks,$CrawledAlready;
  $CrawlSiteLinks = array();
  $CrawledAlready = array();
  
  CrawlDomainForLinksRecurse($URL, $Depth);
  
  unset($CrawledAlready);
  return $CrawlSiteLinks;
}

function CrawlDomainForLinksRecurse($URL,$Depth = 5, $BaseURL = ''){
  if($BaseURL==''){
    $BaseURL=$URL;
  }
  
  global $CrawledAlready;
  if(isset($CrawledAlready[$URL])){
    return;
  }
  $CrawledAlready[$URL]=$URL;
  
  $Page = CacheURL($URL);
  
  $Exploded = explode('href=',$Page);
  
  unset($Exploded[0]);
  $Links = array();
  
  foreach($Exploded as $Link){
    if(trim($Link)==''){continue;}
    
    $Delimiter = substr($Link,0,1);
    if(!($Delimiter=='"'||$Delimiter=="'")){
      //RUH ROH
      echo '<p>idk what to do with this: '.$Link.'</p>';
      continue;
    }
    
    $Link = substr($Link,1);
    $Length = strpos($Link,$Delimiter);
    $Link = substr($Link,0,$Length);
    
    if(substr($Link,0,1)=='/'){
      $Link = $BaseURL.'/'.$Link;
      $Link = str_replace('//','/',$Link);
      $Link = str_replace('//','/',$Link);
      $Link = str_replace('//','/',$Link);
    }
    
    global $CrawlSiteLinks;
    $CrawlSiteLinks[$Link]=$Link;
    
    CrawlDomainForLinksRecurse($Link,$Depth-1,$BaseURL);
      
  }
}
