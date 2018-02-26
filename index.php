<?php

ini_set('display_errors', 1);

define('DOC_ROOT', __DIR__);
define('CSV_ROOT', __DIR__ . '/files');

require_once 'system/view.php';
require_once 'system/route.php';

session_start();
Route::start();