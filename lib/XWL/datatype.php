<?php
// $Id: datatype.php,v 1.1 2003/04/22 17:37:57 loki Exp $
// vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:

// xml-weblog datatype definitions

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
define('_XWL_UNSIGNED_INT_MAX', 4294967295);    // 4 byte unsigned int
define('_XWL_STRING_MAXLEN',    255);
define('_XWL_TEXT_MAXLEN',      65535);         // 64K
define('_XWL_MEDIUMTEXT_MAXLEN',16777215);      // 16MB
define('_XWL_BLOB_SIZE',        65535);         // 64K
define('_XWL_MEDIUMBLOB_SIZE',  16777215);      // 16MB

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

    function HTML_safe_value()
    {
        return htmlspecialchars($this->value);
    }

    function SQL_safe_value()
    {
        return addslashes($this->value);
    }
}

class XWL_integer extends XWL_datatype
{
    var $value = 0;
    var $sql_type = "int unsigned NOT NULL default 0";
    var $admin_display = true;

    function set_value($input)
    {
        // positive integers only
        if (!is_numeric($input) || $input < 0 || $input > _XWL_UNSIGNED_INT_MAX) return false;

        $this->value = $input;
        return true;
    }
}

class XWL_ID extends XWL_integer
{
    var $sql_type = "int unsigned NOT NULL auto_increment";
}

class XWL_string extends XWL_datatype
{
    var $value = "";
    var $sql_type = "varchar(255) NOT NULL default ''";
    var $admin_display = true;

    function _valid_string($input)
    {
        // check for valid length string
        if (!is_string($input) || strlen($input) > _XWL_STRING_MAXLEN) return false;
        else return true;
    }

    function set_value($input)
    {
        // strings only
        if (!$this->_valid_string($input)) return false;

        $this->value = $input;
        return true;
    }
}

class XWL_URI extends XWL_string
{
    function set_value($input)
    {
        $alpha = "abcdefghijklmnopqrstuvwxyz"."ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $num =  "0123456789";
        $dns = "-.";
        $url = "$-_.+"."!*'(),%";
        $path = "/";
        $query = ";:@&=";

        // strings only
        if (!$this->_valid_string($input)) return false;

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
        if (!XWL::_only_has($parsed_uri['user'],$alpha.$num.$url)) return false;
        if (!XWL::_only_has($parsed_uri['pass'],$alpha.$num.$url)) return false;
        if (!XWL::_only_has($parsed_uri['host'],$alpha.$num.$dns)) return false;
        if (!XWL::_only_has($parsed_uri['port'],$num)) return false;
        if (!XWL::_only_has($parsed_uri['path'],$alpha.$num.$url.$path.$query)) return false;
        if (!XWL::_only_has($parsed_uri['query'],$alpha.$num.$url.$query)) return false;
        if (!XWL::_only_has($parsed_uri['fragment'],$alpha.$num.$url)) return false;

        // uri is OK!
        $this->value = $input;
        return true;
    }
}

class XWL_lang extends XWL_string
{
    function set_value($input)
    {
        // strings only
        if (!$this->_valid_string($input)) return false;

        // valid rfc1766 language codes
        if (!preg_match("/^[a-zA-Z]{2}$/", $input)
          && !preg_match("/^[a-zA-Z]{2}-[-a-zA-Z]*$/", $input)
          && !preg_match("/^[ix]-[-a-zA-Z]*$/", $input))
            return false;

        $this->value = $input;
        return true;
    }
}

class XWL_string_XHTML extends XWL_string
{
    function set_value($input)
    {
        // strings only
        if (!$this->_valid_string($input)) return false;

        $valid_tags = "<a><b><i><s><span>";
        $this->value = strip_tags(XWL::_safe_gpc_stripslashes($input), $valid_tags);

        return true;
    }

    function HTML_safe_value()
    {
        // should already be valid HTML
        return $this->value;
    }
}

class XWL_filename extends XWL_string
{
    function set_value($input)
    {
        $alpha = "abcdefghijklmnopqrstuvwxyz"."ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $num =  "0123456789";
        $sym = "-_.";

        // strings only
        if (!$this->_valid_string($input)) return false;

        if (!XWL::_only_has($input, $alpha.$num.$sym) && $input != ".." & $input != ".") return false;

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
        $this->value = (boolean) $input;

        // always works.
        return true;
    }

    function HTML_safe_value()
    {
        // convert to a human-readable string
        return $this->value ? "true" : "false";
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

class XWL_image extends XWL_datatype
{
    var $value;
    var $sql_type = "mediumblob NOT NULL";
    var $admin_display = false;

    function set_value($input_file)
    {
        // valid image files of mediumblob size or less
        if (!is_uploaded_file($input_file['tmp_name'])) return false;
        if (!getimagesize($input_file['tmp_name'])) return false;
        if ($size = filesize($input_file['tmp_name']) > _XWL_MEDIUMBLOB_SIZE) return false;

        $this->value = fread(fopen($input_file['tmp_name'], "r"), $size);
        return true;
    }

}

class XWL_image_small extends XWL_datatype
{
    var $value;
    var $sql_type = "blob NOT NULL";
    var $admin_display = false;

    function set_value($input_file)
    {
        // valid image files of blob size or less
        if (!is_uploaded_file($input_file['tmp_name'])) return false;
        if (!getimagesize($input_file['tmp_name'])) return false;
        if ($size = filesize($input_file['tmp_name']) > _XWL_BLOB_SIZE) return false;

        $this->value = fread(fopen($input_file['tmp_name'], "r"), $size);
        return true;
    }
}

class XWL_XHTML extends XWL_datatype
{
    var $_valid_tags = "";

    var $value = "";
    var $sql_type = "text NOT NULL";
    var $admin_display = false;

    function _valid_XHTML($input)
    {
        // check for valid length text (string)
        if (!is_string($input) || strlen($input) > _XWL_TEXT_MAXLEN) return false;
        else return true;
    }

    function set_value($input)
    {
        // XHTML only
        if (!$this->_valid_XHTML($input)) return false;

        $this->value = strip_tags(XWL::_safe_gpc_stripslashes($input), $this->_valid_tags);
        return true;
    }

    function HTML_safe_value()
    {
        // should already be valid HTML
        return $this->value;
    }
}

class XWL_XHTML_code extends XWL_XHTML
{
    var $_valid_tags = "<a><b><i><s><span><pre><br><br/><img><p><code>";
}

class XWL_XHTML_fragment extends XWL_XHTML
{
    var $_valid_tags = "<a><b><i><s><span><pre><br><br/><img>";
}

class XWL_XHTML_long extends XWL_XHTML
{
    var $_valid_tags = "<a><b><i><s><span><blockquote><table><tr><td><ul><ol><li><pre><br><br/><img><p>";

    var $sql_type = "mediumtext NOT NULL";

    function _valid_XHTML($input)
    {
        // check for valid length text (string)
        if (!is_string($input) || strlen($input) > _XWL_MEDIUMTEXT_MAXLEN) return false;
        else return true;
    }
}
