<?php
// sitewide (dynamic) global variables

require_once "lib/XWL.php";
require_once "include/config.inc.php";

// database initialization
$xwl_db = new XWL_database;
$xwl_db->connect($xwl_db_type, $xwl_db_user, $xwl_db_password, $xwl_db_server, $xwl_db_database);

// site configuration
$xwl_site = $xwl_db->fetch_site(XWL::base_url());
$xwl_site_value_xml = $xwl_site->XML_values();

// set missing values with defaults
if (!$xwl_site_value_xml['article_limit'])
    $xwl_site_value_xml['article_limit'] = $xwl_default_article_limit;

?>
