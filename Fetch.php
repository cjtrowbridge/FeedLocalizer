<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

include('plugins/Loader.php');
Loader('plugins');

$URL = $_GET['url'];
$Links = CrawlDomainForLinks($URL,2,'rss');
pd($Links);
exit;

Loader('api/v1');

Event('Fetch');

echo '<p>Done! <a href="/">Home</a></p>';
