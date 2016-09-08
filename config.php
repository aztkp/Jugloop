<?php

 ini_set ('display_errors', 1);

 require_once(__DIR__ . '/vendor/autoload.php');

 define('CONSUMER_KEY', 'laLhsQyWoP1k5evSL4KlDzkp0');
 define('CONSUMER_SECRET', 'MR9c7ZLcsSnCC6iZvvq9BMHKpp1bRUyHPByyC1cTFtMtEQYUSK');
 define('CALLBACK_URL', 'http://192.168.33.10/login');

 define('DSN', 'mysql:host=localhost;dbname=jugloop');
 define('DB_USERNAME', 'butiyo');
 define('DB_PASSWORD', 'ohara');

 session_start();
 require_once(__DIR__ . '/functions.php');
 require_once(__DIR__ . '/autoload.php');
