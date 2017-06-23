<?php

function CacheURL($URL,$TTL = 3600){
  $Path = 'cache/'.sha256($URL).'.json';
  if(file_exists($Path)){
    if((filemtime($Path)+$TTL)>time()){
      return file_get_contents($Path);
    }
  }
  
  $Data = file_get_contents($URL);
  if($Data==false){
    die('Failed to fetch '.$URL);
  }
  file_put_contents($Path,$Data);
  return $Data;
}
