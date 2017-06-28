<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require('config.php');
include('plugins/Loader.php');
Loader('plugins');

echo '<p>Done! <a href="/">Home</a></p>';
