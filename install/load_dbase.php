<?php
// $Id: load_dbase.php,v 1.14 2002/11/01 17:58:50 loki Exp $
// database/image loader

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

header('Content-Type: text/plain');

require_once "include/config.inc.php";
require_once "include/types.inc.php";
require_once "DB.php";

$db = DB::connect("$xwl_db_type://$xwl_db_user:$xwl_db_password@$xwl_db_server/$xwl_db_database", true);

if (DB::isError($db)) {
    $link = mysql_pconnect($xwl_db_server, $xwl_db_user, $xwl_db_password)
        or die("Error: couldn't connect!\n");
    mysql_create_db($xwl_db_database)
        or die("Error: couldn't create database!\n");
    $db = DB::connect("$xwl_db_type://$xwl_db_user:$xwl_db_password@$xwl_db_server/$xwl_db_database", true);
    if (DB::isError($db)) die("Error: WTF Happened ?\n");
} else die("Error: database already exists. not installing.\n");

ob_start();
echo $create_schema_query, "\n\n";

foreach ($tables as $table) {

    // table schema
    foreach ($$table as $col => $t) {
        echo "INSERT INTO schema VALUES(0,'$table','$col','$t[0]',$t[1]);\n";
    }
    echo "\n";

    echo "CREATE TABLE $table (\n";
    foreach ($$table as $col => $t) {
        echo "  $col {$type[$t[0]]},\n";
    }
    echo "  PRIMARY KEY (id)\n";
    echo ") TYPE=MyISAM;\n\n";
}
include "./sample-values.sql";
$query = split(";\n",ob_get_contents());
foreach ($query as $q) {
    $db->query(trim($q));
} 
ob_end_flush();

echo "\n";
ob_start();
include "./image_list.txt";
$list = split("\n",trim(ob_get_contents()));
ob_end_clean();

foreach ($list as $name) {
    echo "loading image: $name...";
    $base = basename($name);
    $load_image = AddSlashes(fread(fopen($name, "r"), filesize($name)));
    $size = getimagesize($name);
    $db->query("insert into image values (0,'$base','$load_image',
        '{$mime_type[$size[2]]}',$size[0],$size[1])");
       
    echo "loaded.\n";
}

ob_start();
include "./icon_list.txt";
$list = split("\n",trim(ob_get_contents()));
ob_end_clean();

foreach ($list as $name) {
    echo "loading icon: $name...";
    $base = basename($name);
    $load_image = AddSlashes(fread(fopen($name, "r"), filesize($name)));
    $size = getimagesize($name);
    $db->query("insert into icon values (0,'$base','$load_image',
        '{$mime_type[$size[2]]}',$size[0],$size[1])");
       
    echo "loaded.\n";
}

?>
