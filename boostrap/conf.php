<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

define('APP_PATH', realpath(__DIR__ . '/..'));
define('IMG_PATH', APP_PATH . '/images');
define('THUMB_ORIGINAL', IMG_PATH . '/original');
define('THUMB', IMG_PATH . '/thumb');
define('THUMB_200', IMG_PATH . '/200');
define('THUMB_400', IMG_PATH . '/400');
define('THUMB_100', IMG_PATH . '/100');
define('THUMB_HISTO', IMG_PATH . '/histo');

if (!is_dir(IMG_PATH)) {
    mkdir(IMG_PATH);
}

if (!is_dir(THUMB_ORIGINAL)) {
    mkdir(THUMB_ORIGINAL);
}

if (!is_dir(THUMB)) {
    mkdir(THUMB);
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
