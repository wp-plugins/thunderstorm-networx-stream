<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'web307');
define('DB_PASSWORD', 'esyF0In2');
define('DB_PREFIX', 'wp_');
define('DB_DB', 'usr_web307_2');
define('API_DIR', dirname(__FILE__));
define('WP_DIR', dirname(API_DIR). '/thunderstorm');

mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysql_select_db(DB_DB);

require_once API_DIR. '/functions.php';
require_once API_DIR. '/settings.php';

if (!empty($_GET['lang'])) {
    require_once API_DIR. '/lang/'. $_GET['lang']. '.php';
}

?>
