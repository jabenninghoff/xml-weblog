<?php
// $Id: object.php,v 1.2 2003/04/22 21:50:52 loki Exp $
// vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:

// xml-weblog objects (block, user, etc.)

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

// object class list
$xwl_object_class = array(
    "site", "message", "topic", "block", "article", "user", "image", "icon"
);

// object definitions

class XWL_object
{
    var $property;
    var $required;

    function _add_property($name, $datatype, $required)
    {
        $this->property[$name] = new $datatype;
        $this->required[$name] = $required;
    }

    function XWL_object()
    {
        // _add_property($name, $datatype, $required)
        $this->_add_property("id", "XWL_ID", 1);
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
}

class XWL_article extends XWL_object
{
    function XWL_article()
    {
        // _add_property($name, $datatype, $required)
        // site -> site.id, topic -> topic.id, author -> user.id
        $this->_add_property("id", "XWL_ID", true);
        $this->_add_property("site", "XWL_integer", true);
        $this->_add_property("topic", "XWL_integer", true);
        $this->_add_property("title", "XWL_string", true);
        $this->_add_property("user", "XWL_integer", true);
        $this->_add_property("date", "XWL_date", true);
        $this->_add_property("leader", "XWL_XHTML_long", true);
        $this->_add_property("content", "XWL_XHTML_long", false);
        $this->_add_property("language", "XWL_lang", true);
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
}
?>
