<?php
// $Id: object.php,v 1.8 2003/11/01 04:11:44 loki Exp $
// vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:

// xml-weblog objects (block, user, etc.)

/*
 * Copyright (c) 2002, 2003 John Benninghoff <john@benninghoff.org>.
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

// object class list
$xwl_object_class = array(
    "article", "block", "icon", "image", "message", "site", "topic", "user"
);

// object definitions

class XWL_object
{
    var $property;
    var $required;

    // private functions
    function _add_property($name, $datatype, $required)
    {
        $this->property[$name] = new $datatype;
        $this->required[$name] = $required;
    }

    function _admin_input($prop, $mode) {
        $input  = "            <tr>\n";
        $input .= "              <td><b>$prop</b></td>\n";
        $input .= "              <td>";
        $input .= $this->property[$prop]->admin_input($prop, $mode);
        $input .= "</td>\n";
        $input .= "            </tr>\n";

        return $input;
    }

    function _admin_input_select($name, $option, $value, $default)
    {
        if (!in_array($default, $option)) $default = $option[0];
        $input  = "            <tr>\n";
        $input .= "              <td><b>$name</b></td>\n";
        $input .= "              <td><select name=\"$name\">\n";
        for ($i=0; $option[$i]; $i++) {
            $sel = $option[$i] == $default ? "selected=\"selected\" " : "";
            $input .= "                <option ".$sel." value=\"$value[$i]\">$option[$i]</option>\n";
        }
        $input .= "              </select></td>\n";
        $input .= "            </tr>\n";

        return $input;
    }

    // public functions
    function XWL_object()
    {
        // _add_property($name, $datatype, $required)
        $this->_add_property("id", "XWL_ID", 1);
    }

    function load_SQL($result)
    {
        if (!$result) return;
        foreach ($result as $key => $value) {
            $this->property[$key]->set_value($value);
        }
    }

    function XML_values()
    {
        // convert to _xml values for convenience
        foreach ($this->property as $key => $value) {
            $xml_value[$key] = $value->display_XML();
        }
        return $xml_value;
    }

    function admin_display($prop)
    {
        return $this->property[$prop]->admin_display;
    }

    function admin_form($action, $mode, $class)
    {
        $form = "        <form action=\"$action\" method=\"post\" enctype=\"multipart/form-data\">\n";

        $form .= "          <table>\n";
        foreach ($this->property as $key => $value) {
            $form .= $this->_admin_input($key, $mode);
        }
        $form .= "          </table>\n";

        $form .= "          <p>\n";
        $form .= "            <input name=\"mode\" type=\"hidden\" value=\"$mode\"/>\n";
        $form .= "            <input name=\"type\" type=\"hidden\" value=\"$class\"/>\n";
        if ($mode == "edit") $button = "Save";
        else $button = ucfirst($mode);
        $form .= "            <input name=\"submit\" type=\"submit\" value=\"$button\"/><input name=\"cancel\" type=\"submit\" value=\"Cancel\"/>\n";
        $form .= "          </p>\n";

        $form .= "        </form>\n";

        return $form;
    }
}

class XWL_site extends XWL_object
{
    function XWL_site()
    {
        // _add_property($name, $datatype, $required)
        $this->_add_property("id", "XWL_ID", true);
        $this->_add_property("url", "XWL_URI", true);
        $this->_add_property("article_limit", "XWL_integer", true);
        $this->_add_property("name", "XWL_string", true);
        $this->_add_property("slogan", "XWL_string_XHTML", false);
        $this->_add_property("logo", "XWL_URI", false);
        $this->_add_property("description", "XWL_XHTML_fragment", false);
        $this->_add_property("header_content", "XWL_XHTML_code", false);
        $this->_add_property("disclaimer", "XWL_XHTML_fragment", false);
        $this->_add_property("footer_content", "XWL_XHTML_code", false);
        $this->_add_property("language", "XWL_lang", true);
    }
}

class XWL_message extends XWL_object
{
    function XWL_message()
    {
        // _add_property($name, $datatype, $required)
        $this->_add_property("id", "XWL_ID", 1);
        $this->_add_property("message_index", "XWL_integer", true);
        $this->_add_property("start_date", "XWL_date", false);
        $this->_add_property("end_date", "XWL_date", false);
        $this->_add_property("content", "XWL_XHTML_fragment", true);
        $this->_add_property("language", "XWL_lang", true);
    }
}

class XWL_topic extends XWL_object
{
    function XWL_topic()
    {
        // _add_property($name, $datatype, $required)
        $this->_add_property("id", "XWL_ID", true);
        $this->_add_property("name", "XWL_string", true);
        $this->_add_property("description", "XWL_XHTML_fragment", false);
        $this->_add_property("icon", "XWL_URI", true);
    }
}

class XWL_block extends XWL_object
{
    function XWL_block()
    {
        // _add_property($name, $datatype, $required)
        $this->_add_property("id", "XWL_ID", true);
        $this->_add_property("sidebar_align", "XWL_string", true);
        $this->_add_property("sidebar_index", "XWL_integer", true);
        $this->_add_property("block_index", "XWL_integer", true);
        $this->_add_property("title", "XWL_string", true);
        $this->_add_property("content", "XWL_XHTML_code", false);
        $this->_add_property("sysblock", "XWL_filename", false);
        $this->_add_property("language", "XWL_lang", true);
    }

    function _admin_input($prop, $mode) {
        if ($prop == "sidebar_align" && $mode != "delete") {
            $opt = array("left", "right");
            return $this->_admin_input_select($prop, $opt, $opt, $this->property[$prop]->value);
        } else {
            return parent::_admin_input($prop, $mode);
        }
    }
}

class XWL_article extends XWL_object
{
    var $linked_properties = array(
        "topic_name", "topic_icon", "user_name"
    );

    function XWL_article()
    {
        // _add_property($name, $datatype, $required)
        // site -> site.id, topic -> topic.id, user -> user.id
        $this->_add_property("id", "XWL_ID", true);
        $this->_add_property("site", "XWL_key", true);
        $this->_add_property("topic", "XWL_key", true);
        $this->_add_property("title", "XWL_string", true);
        $this->_add_property("user", "XWL_key", true);
        $this->_add_property("date", "XWL_date", true);
        $this->_add_property("leader", "XWL_XHTML_long", true);
        $this->_add_property("content", "XWL_XHTML_long", false);
        $this->_add_property("language", "XWL_lang", true);

        // linked properties
        $this->_add_property("topic_name", "XWL_string", false);
        $this->_add_property("topic_icon", "XWL_URI", false);
        $this->_add_property("user_name", "XWL_string", false);
    }

    function admin_display($prop)
    {
        return in_array($prop,$this->linked_properties) ? false : $this->property[$prop]->admin_display;
    }

    function _admin_input($prop, $mode) {
        if (in_array($prop, $this->linked_properties)) {
            return;
        } elseif (in_array($prop, array("site","topic","user"))) {
            $i = 0;
            foreach ($GLOBALS["XWL_{$prop}_list"] as $item) {
                if ($this->property[$prop]->value == $item['id']) $def = $item['name'];
                $opt[$i] = $item['name'];
                $val[$i] = $item['id'];
                $i++;
            }
            return $this->_admin_input_select($prop, $opt, $val, $def);
        } else {
            return parent::_admin_input($prop, $mode);
        }
    }
}

class XWL_user extends XWL_object
{
    function XWL_user()
    {
        // _add_property($name, $datatype, $required)
        $this->_add_property("id", "XWL_ID", true);
        $this->_add_property("userid", "XWL_string", true);
        $this->_add_property("password", "XWL_string", true);
        $this->_add_property("admin", "XWL_boolean", true);
        $this->_add_property("block", "XWL_XHTML_fragment", false);
    }

    function _admin_input($prop, $mode) {
        if ($prop == "password") {
            $input = "            <tr>\n";
            $input .= "              <td><b>Password</b></td>\n";
            $input .= "              <td>";
            $input .= '<input name="password" type="password" maxlength="255" size="40"/>';
            $input .= "</td>\n";
            $input .= "            </tr>\n";

            return $input;
        } else {
            return parent::_admin_input($prop, $mode);
        }
    }
}

class XWL_image extends XWL_object
{
    function XWL_image()
    {
        // _add_property($name, $datatype, $required)
        // image-BLOB tables
        $this->_add_property("id", "XWL_ID", true);
        $this->_add_property("name", "XWL_string", true);
        $this->_add_property("src", "XWL_imagedata", true);
        $this->_add_property("mime", "XWL_string", true);
        $this->_add_property("width", "XWL_integer", false);
        $this->_add_property("height", "XWL_integer", false);
    }

    function _admin_input($prop, $mode) {
        if (in_array($prop, array("mime","width","height")) && $mode != "delete") {
            return "<tr><td>$prop</td><td><i>automatically generated</i></td></tr>";
        } else {
            return parent::_admin_input($prop, $mode);
        }
    }
}

class XWL_icon extends XWL_object
{
    function XWL_icon()
    {
        // _add_property($name, $datatype, $required)
        $this->_add_property("id", "XWL_ID", true);
        $this->_add_property("name", "XWL_string", true);
        $this->_add_property("src", "XWL_imagedata_small", true);
        $this->_add_property("mime", "XWL_string", true);
        $this->_add_property("width", "XWL_integer", false);
        $this->_add_property("height", "XWL_integer", false);
    }

    function _admin_input($prop, $mode) {
        if (in_array($prop, array("mime","width","height")) && $mode != "delete") {
            return "<tr><td>$prop</td><td><i>automatically generated</i></td></tr>";
        } else {
            return parent::_admin_input($prop, $mode);
        }
    }
}
?>
