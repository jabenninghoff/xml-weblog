<?php
// $Id: types.inc.php,v 1.7 2003/04/03 17:30:34 loki Exp $
// xml-weblog type definitions

/*
 * Copyright (c) 2002, John Benninghoff <john@benninghoff.org>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. All advertising materials mentioning features or use of this software
 *    must display the following acknowledgement:
 *	This product includes software developed by John Benninghoff.
 * 4. Neither the name of the copyright holder nor the names of its 
 *    contributors may be used to endorse or promote products derived from
 *    this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 *  "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED
 * TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
 * LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

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
    "image" => "admin_form_image",
    "user" => "admin_form_user"
);

// custom admin form processors
$admin_form_processor = array(
    "icon" => "process_form_image",
    "image" => "process_form_image",
    "user" => "process_form_user"
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
    "content" => array("XHTML_code", 0),
    "sysblock" => array("string", 0),
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
    "admin" => array("boolean", 1),
    "block" => array("XHTML_fragment", 0)
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
