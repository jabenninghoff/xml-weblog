<?php
// $Id: XWL.php,v 1.1 2003/04/22 14:07:37 loki Exp $
// vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:

// xml-weblog base library

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

// defines
define('_XWL_UNSIGNED_INT_MAX', 4294967295); // 4 byte unsigned int
define('_XWL_STRING_MAXLEN',    255);

// private functions
function _only_has($source, $valid_chars)
{
    if (strspn($source, $valid_chars) == strlen($source)) return true;
    else return false;
}

// basic datatype definitions
class XWL_datatype
{
    var $value;
    var $sql_type;
    var $admin_display;

    function set_value($input)
    {
        // tests - return false on failure

        $this->value = $input;
        return true;
    }
}

class XWL_ID extends XWL_datatype
{
    var $value = 0;
    var $sql_type = "int unsigned NOT NULL auto_increment";
    var $admin_display = true;

    function set_value($input)
    {
        // positive integers only
        if (!is_numeric($input) || $input < 0 || $input > _XWL_UNSIGNED_INT_MAX) return false;

        $this->value = $input;
        return true;
    }
}

class XWL_URI extends XWL_datatype
{
    var $value = "";
    var $sql_type = "varchar(255) NOT NULL default ''";
    var $admin_display = true;

    function set_value($input)
    {
        $alpha = "abcdefghijklmnopqrstuvwxyz"."ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $num =  "0123456789";
        $dns = "-.";
        $url = "$-_.+"."!*'(),%";
        $path = "/";
        $query = ";:@&=";

        // strings only
        if (!is_string($input) || strlen($input) > _XWL_STRING_MAXLEN) return false;

        $parsed_uri = parse_url($input);
    
        // sanity check - make sure we parsed_uri right
        $new_uri = "";
        if (isset($parsed_uri['scheme'])) $new_uri .= "$parsed_uri[scheme]://";
        if (isset($parsed_uri['pass'])) $new_uri .= "$parsed_uri[user]:$parsed_uri[pass]@";
        elseif (isset($parsed_uri['user'])) $uri .= "$parsed_uri[user]@";
        if (isset($parsed_uri['host'])) $new_uri .= $parsed_uri['host'];
        if (isset($parsed_uri['port'])) $new_uri .= ":$parsed_uri[port]";
        if (isset($parsed_uri['path'])) $new_uri .= $parsed_uri['path'];
        if (isset($parsed_uri['query'])) $new_uri .= "?$parsed_uri[query]";
        if (isset($parsed_uri['fragment'])) $new_uri .= "#$parsed_uri[fragment]";
        if ($input != $new_uri) return false;

        // check each component
        if ($parsed_uri['scheme'] && $parsed_uri['scheme'] != "http") return false;
        if (!_only_has($parsed_uri['user'],$alpha.$num.$url)) return false;
        if (!_only_has($parsed_uri['pass'],$alpha.$num.$url)) return false;
        if (!_only_has($parsed_uri['host'],$alpha.$num.$dns)) return false;
        if (!_only_has($parsed_uri['port'],$num)) return false;
        if (!_only_has($parsed_uri['path'],$alpha.$num.$url.$path.$query)) return false;
        if (!_only_has($parsed_uri['query'],$alpha.$num.$url.$query)) return false;
        if (!_only_has($parsed_uri['fragment'],$alpha.$num.$url)) return false;
    
        // uri is OK!
        $this->value = $input;
        return true;
    }
}

class XWL_boolean extends XWL_datatype
{
    var $value = false;
    var $sql_type = "tinyint NOT NULL default 0";
    var $admin_display = true;

    function set_value($input)
    {
        // simply cast the value to a boolean type
        $value = (boolean) $input;

        // always works.
        return true;
    }
}

class XWL_date extends XWL_datatype
{
    var $value = "0000-00-00 00:00:00";
    var $sql_type = "datetime NOT NULL default '0000-00-00 00:00:00'";
    var $admin_display = false;

    function set_value($input)
    {
        // accept blank (false) input as the "zero" date
        if (!$input) {
            $this->value = "0000-00-00 00:00:00";
            return true;
        }

        // allow any php-parseable time/date string
        if (($timestamp = strtotime($input)) === -1) return false;

        $this->value = date("Y-m-d H:i:s",$timestamp);
        return true;
    }
}

// test section
header('Content-Type: text/plain');

$test_values = array(
    "XWL_ID" => "801928",
    "XWL_URI" => "http://www.technomagik.net/index.php",
    "XWL_boolean" => "1",
    "XWL_date" => "7/17/71",
);

foreach ($test_values as $key => $test) {
    $object = new $key;
    if ($object->set_value($test)) echo $object->value, "\n";
    else echo "bad $key!\n";
}

$type = array(
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
