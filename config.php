<?php
// HTTP
define('HTTP_SERVER', 'http://dev.ua/');

// HTTPS
define('HTTPS_SERVER', 'https://dev.ua/');

// DIR
define('DIR_APPLICATION', 'W:/domains/dev.ua/catalog/');
define('DIR_SYSTEM', 'W:/domains/dev.ua/system/');
define('DIR_IMAGE', 'W:/domains/dev.ua/image/');
define('DIR_STORAGE', 'W:/domains/dev.ua/storage/');
define('DIR_LANGUAGE', DIR_APPLICATION . 'language/');
define('DIR_TEMPLATE', DIR_APPLICATION . 'view/theme/');
define('DIR_CONFIG', DIR_SYSTEM . 'config/');
define('DIR_CACHE', DIR_STORAGE . 'cache/');
define('DIR_DOWNLOAD', DIR_STORAGE . 'download/');
define('DIR_LOGS', DIR_STORAGE . 'logs/');
define('DIR_MODIFICATION', DIR_STORAGE . 'modification/');
define('DIR_SESSION', DIR_STORAGE . 'session/');
define('DIR_UPLOAD', DIR_STORAGE . 'upload/');

// DB
define('DB_DRIVER', 'mysqli');
define('DB_HOSTNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'foodlife');
define('DB_PORT', '3306');
define('DB_PREFIX', 'oc_');
