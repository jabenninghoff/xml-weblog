<?php
// $Id: load_dbase.php,v 1.24 2004/04/30 21:24:19 loki Exp $
// vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:

// database/image loader

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

header('Content-Type: text/plain');

require_once "../include/config.inc.php";
require_once "../lib/XWL.php";
require_once "DB.php";

$db = DB::connect("$xwl_db_type://$xwl_db_user:$xwl_db_password@$xwl_db_server/$xwl_db_database", true);

if (DB::isError($db)) {
    $link = mysql_pconnect($xwl_db_server, $xwl_db_user, $xwl_db_password) or die("Error: couldn't connect!\n");
    mysql_query("CREATE DATABASE $xwl_db_database") or die("Error: couldn't create database!\n");
    $db = DB::connect("$xwl_db_type://$xwl_db_user:$xwl_db_password@$xwl_db_server/$xwl_db_database", true);
    if (DB::isError($db)) die("Error: WTF Happened ?\n");
    $db->query("SET SQL_MODE = '';") or die ("Error: couldn't set SQL_MODE!\n"); // workaround for strict SQL modes
} else die("Error: database already exists. not installing.\n");

ob_start();

foreach ($xwl_object_class as $class) {

    echo "CREATE TABLE $class (\n";
    $xwl_class = "XWL_$class";
    $obj = new $xwl_class;
    foreach ($obj->property as $name => $prop) {
        if ($prop->sql_type) echo "  $name {$prop->sql_type},\n";
    }
    echo "  PRIMARY KEY (id)\n";
    echo ") ENGINE=MyISAM;\n\n";
}

include "./sample-values.sql";

// generate default admin password using system default encryption algorithm
echo "\nINSERT INTO user VALUES (1, 'Administrator', 'www@xml-weblog.org', 'admin','", crypt("weblog"), "',1,0,'');\n";

// match unix/dos/mac newline
$query = preg_split("/;[(\n)(\r\n)(\cM)]/",ob_get_contents());
foreach ($query as $q) {
    $db->query(trim($q));
}

ob_end_flush();
?>
