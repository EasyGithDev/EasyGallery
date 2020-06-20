<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

define('APP_PATH', realpath(__DIR__ . '/..'));
define('PUBLIC_PATH', APP_PATH . '/public');
define('IMG_PATH', APP_PATH . '/images');
define('THUMB_PATH', PUBLIC_PATH . '/thumb');
define('THUMB_200', THUMB_PATH . '/200');
define('THUMB_400', THUMB_PATH . '/400');
define('THUMB_100', THUMB_PATH . '/100');
define('THUMB_HISTO', THUMB_PATH . '/histo');

if (!is_dir(IMG_PATH)) {
    mkdir(IMG_PATH);
}

if (!is_dir(THUMB_PATH)) {
    mkdir(THUMB_PATH);
}

if (!is_dir(THUMB_100)) {
    mkdir(THUMB_100);
}

if (!is_dir(THUMB_200)) {
    mkdir(THUMB_200);
}

if (!is_dir(THUMB_400)) {
    mkdir(THUMB_400);
}

if (!is_dir(THUMB_HISTO)) {
    mkdir(THUMB_HISTO);
}

require APP_PATH . '/vendor/autoload.php';
