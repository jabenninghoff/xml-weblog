<?php
// $Id: types.inc.php,v 1.3 2002/11/01 16:46:21 loki Exp $
// xml-weblog type definitions

// basic datatype definitions
$type = array(
    "ID" => "int unsigned NOT NULL auto_increment",
    "URI" => "varchar(255) NOT NULL default ''",
    "boolean" => "tinyint NOT NULL default 0",
    "date" => "datetime NOT NULL default '0000-00-00 00:00:00'",
    "image" => "mediumblob NOT NULL",
    "image_small" => "blob NOT NULL",
    "int" => "int unsigned NOT NULL default 0",
    "lang" => "varchar(255) NOT NULL default 'en'",
    "string" => "varchar(255) NOT NULL default ''",
    "string_XHTML" => "varchar(255) NOT NULL default ''",
    "XHTML_code" => "text NOT NULL",
    "XHTML_fragment" => "text NOT NULL",
    "XHTML_long" => "mediumtext NOT NULL"
);

// display in admin tables
$admin_display = array(
    "ID" => true,
    "URI" => true,
    "boolean" => true,
    "date" => false,
    "image" => false,
    "image_small" => false,
    "int" => true,
    "lang" => true,
    "string" => true,
    "string_XHTML" => true,
    "XHTML_code" => false,
    "XHTML_fragment" => false,
    "XHTML_long" => false
);

// custom admin form handlers
$admin_form_handler = array(
    "article" => "admin_form_article",
    "block" => "admin_form_block",
    "icon" => "admin_form_image",
    "image" => "admin_form_image"
);

// custom admin form processors
$admin_form_processor = array(
    "icon" => "process_form_image",
    "image" => "process_form_image"
);

// image types for getimagesize()
$mime_type = array("", "image/gif", "image/jpeg", "image/png",
    "application/x-shockwave-flash", "PSD", "image/bmp", "image/tiff",
    "image/tiff", "JPC", "JP2", "JPX", "JB2", "SWC", "IFF"); 

// table definitions

// $object = array(
//     "field" => array("type", required),

$site = array(
    "id" => array("ID", 1),
    "url" => array("URI", 1),
    "article_limit" => array("int", 1),
    "name" => array("string", 1),
    "slogan" => array("string_XHTML", 0),
    "logo" => array("URI", 0),
    "description" => array("XHTML_fragment", 0),
    "header_content" => array("XHTML_code", 0),
    "disclaimer" => array("XHTML_fragment", 0),
    "footer_content" => array("XHTML_code", 0),
    "language" => array("lang", 1)
);

$message = array(
    "id" => array("ID", 1),
    "message_index" => array("int", 1),
    "start_date" => array("date", 0),
    "end_date" => array("date", 0),
    "content" => array("XHTML_fragment", 1),
    "language" => array("lang", 1)
);

$topic = array(
    "id" => array("ID", 1),
    "name" => array("string", 1),
    "description" => array("XHTML_fragment", 0),
    "icon" => array("URI", 1)
);

$block = array(
    "id" => array("ID", 1),
    "sidebar_align" => array("string", 1),
    "sidebar_index" => array("int", 1),
    "block_index" => array("int", 1),
    "title" => array("string", 1),
    "content" => array("XHTML_code", 1),
    "language" => array("lang", 1)
);

// site -> site.id, topic -> topic.id, author -> user.id
$article = array(
    "id" => array("ID", 1),
    "site" => array("int", 1),
    "topic" => array("int", 1),
    "title" => array("string", 1),
    "user" => array("int", 1),
    "date" => array("date", 1),
    "leader" => array("XHTML_long", 1),
    "content" => array("XHTML_long", 0),
    "language" => array("lang", 1)
);

$user = array(
    "id" => array("ID", 1),
    "userid" => array("string", 1),
    "password" => array("string", 1),
    "admin" => array("boolean", 1)
);

// image-BLOB tables
$image = array(
    "id" => array("ID", 1),
    "name" => array("string", 1),
    "src" => array("image", 1),
    "mime" => array("string", 1),
    "width" => array("int", 0),
    "height" => array("int", 0)
);

$icon = array(
    "id" => array("ID", 1),
    "name" => array("string", 1),
    "src" => array("image_small", 1),
    "mime" => array("string", 1),
    "width" => array("int", 0),
    "height" => array("int", 0)
);

$tables = array(
    "site", "message", "topic", "block", "article", "user", "image", "icon"
);

$create_schema_query = 
"CREATE TABLE schema (
  id int unsigned NOT NULL auto_increment,
  object varchar(255) NOT NULL default '',
  property varchar(255) NOT NULL default '',
  datatype varchar(255) NOT NULL default 'string',
  required tinyint NOT NULL default 1,
  PRIMARY KEY (id)
) TYPE=MyISAM;";

?>
