<?php
// $Id: config.php,v 1.1 2004/09/23 18:54:29 loki Exp $
// vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:

// configuration variables

/*
 * Copyright (c) 2002 - 2004 John Benninghoff <john@benninghoff.org>.
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

// php include path
ini_set('include_path', '/php-lib-path'.PATH_SEPARATOR.'/xml-weblog-lib-path'.PATH_SEPARATOR.'/xml-weblog-path');

// database configuration
$xwl_db_type = "mysql";         // currently, only mysql is supported
$xwl_db_server = "127.0.0.1";
$xwl_db_user = "xml";
$xwl_db_password = "weblog";
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
