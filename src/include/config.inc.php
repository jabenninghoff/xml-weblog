<?php
// configuration variables

// php settings
date_default_timezone_set('UTC'); // set UTC

// database configuration
$xwl_db_type = "mysql";         // currently, only mysql is supported
$xwl_db_server = "mysql";
$xwl_db_user = "weblog";
$xwl_db_password = $_ENV["MYSQL_PASSWORD"];
$xwl_db_database = "xml_weblog";

// global defaults
$xwl_default_article_limit = 10;
$xwl_default_site = 1;
$xwl_default_topic = 1;
$xwl_default_user = 1;
$xwl_default_lang = "en";
$xwl_default_style = "xhtml_css2";

// auth defaults
$xwl_auth_realm = "private";

// xmlrpc defaults
$xwl_blogger_title = date("F j, Y");

?>
