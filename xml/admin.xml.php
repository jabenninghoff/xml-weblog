<?php
// $Id: admin.xml.php,v 1.33 2004/07/11 23:17:15 loki Exp $
// vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4:

// admin front page

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

require_once "XWL.php";
require_once "include/site.php";
require_once "include/auth.inc.php";

// print_form functions

function print_form($object_class, $edit_mode, $object_id)
{
    global $xwl_db, $page;

    if ($edit_mode == "create") {
        echo "      <title>", ucfirst($object_class."s"), "</title>\n";

        // only display object list in create mode
        $object = $xwl_db->fetch_objects($object_class);
        foreach ($object as $obj) {
            echo "      <object type=\"$object_class\">\n";
            foreach ($obj->property as $name => $o) {
                if ($obj->admin_display($name)) {
                    echo "        ";
                    echo "<property name=\"$name\">", $o->display_XML(), "</property>\n";
                }
            }
            echo "        <property><a href=\"$page?class=$object_class&amp;mode=edit&amp;id=", $obj->property['id']->value, "\">edit</a></property>\n";
            echo "        <property><a href=\"$page?class=$object_class&amp;mode=delete&amp;id=", $obj->property['id']->value, "\">delete</a></property>\n";
            echo "      </object>\n";
        }
    } else {
        echo "      <title>", ucfirst($edit_mode), " ", ucfirst($object_class), "</title>\n";
    }

    echo "      <content>\n";
    // actual form
    if ($edit_mode == "create") {
        $object_class_xwl = "XWL_$object_class";
        $object_form = new $object_class_xwl;
    } else {
        $object_form = $xwl_db->fetch_object($object_class, $object_id);
    }

    if ($object_form) {
        $act = "$page?class=$object_class&amp;mode=$edit_mode";
        if ($edit_mode != "create") $act .= "&amp;id=$object_id";
        echo $object_form->admin_form($act, $edit_mode, $object_class);
    } else {
        echo "<p>xml_weblog error: invalid $object_class id '$object_id'</p>\n";
    }
    echo "      </content>\n";

}

function process_form($object_class, $edit_mode, $object_id)
{
    global $xwl_db;

    echo "      <title>", ucfirst($edit_mode), " ", ucfirst($object_class), "</title>\n";

    echo "      <content>\n";

    if ($edit_mode == "delete") {
        if ($xwl_db->delete_object($object_class, $object_id)) {
            echo "<p>$object_class id '$object_id' deleted.</p>\n";
        } else {
            echo "<p>xml_weblog error: error deleting $object_class id '$object_id'</p>\n";
        }
    } else {
        $xwl_class = "XWL_$object_class";
        $object = new $xwl_class;
        foreach ($_POST as $name => $post) {
            if ($object->property[$name]) {
                if ($name == "password" && $_POST['password'] != $_POST['saved_password']) {
                    // here the magic happens -- we want to crypt the password (which has changed)
                    $object->property[$name]->set_password(XWL::magic_unslash($post));
                } else {
                    $object->property[$name]->set_value(XWL::magic_unslash($post));
                }
            }
        }

        if ($object_class == "image" || $object_class == "icon") {
            if ($file = $_FILES['src']) {
                if (is_uploaded_file($file['tmp_name'])) {
                    $object->load_image_file($file['tmp_name']);
                }
            }
        }

        if ($missing = $object->missing_required()) {
            echo "        <p><strong>please re-enter missing values:</strong><em>";
            foreach ($missing as $val) {
                echo " $val";
            }
            echo "</em></p>\n";

            // redraw form
            $act = "$page?class=$object_class&amp;mode=$edit_mode&amp;id=$object_id";
            echo $object->admin_form($act, $edit_mode, $object_class);
        } else {
            // store object
            if ($edit_mode == "edit") {
                $success = $xwl_db->edit_object($object_class, $object);
                $action = "updated";
            } else {
                // edit_mode = create
                $success = $xwl_db->create_object($object_class, $object);
                $action = "created";
            }

            if ($success) {
                echo "        <p>$object_class $action.</p>\n";
                // dump results
                echo "        <table>\n";
                foreach ($object->property as $key => $prop) {
                    echo "        <tr><td><strong>$key</strong></td><td>", $prop->HTML_safe_value(), "</td></tr>\n";
                }
                echo "        </table>\n";
            } else {
                echo "        <p>xml_weblog error: $object_class not $action.</p>\n";
            }
        }
    }
    echo "      </content>\n";
}

// main

// check authentication
if (!xwl_auth_user_authenticated() || !xwl_auth_user_authorized("admin")) {
    xwl_auth_unauthorized($xwl_auth_realm);
    exit;
}

if (($page = basename($_SERVER['PHP_SELF'])) == "admin.xml.php") {
    // standalone
    header('Content-Type: text/xml');
}

XWL::xml_declaration();

echo "<page lang=\"en\" title=\"{$xwl_site_value_xml['name']}\">\n\n";

require "xml/header.xml.php";
echo "\n";

require "xml/sidebar.xml.php";
echo "\n";

echo "  <!-- main: main section of document. index page contains articles. -->\n";
echo "    <main>\n";
echo "      <admin>\n";

// create top (object select) menu
foreach ($xwl_object_class as $object) {
    echo "      <menu link=\"$page?class=$object\">", ucfirst($object."s"), "</menu>\n";
}

$object_class = in_array($_GET['class'],$xwl_object_class) ? $_GET['class'] : $xwl_object_class[0];
$edit_mode = ($_GET['mode'] == "edit" || $_GET['mode'] == "delete") ? $_GET['mode'] : "create";

$temp_id = new XWL_ID;
if ($temp_id->set_value(XWL::magic_unslash($_GET['id']))) {
    $object_id = $temp_id->value;
} else {
    // fall back to create mode when given an invalid id
    $edit_mode = "create";
}

if (isset($_POST['submit'])) {
    process_form($object_class, $edit_mode, $object_id);
} elseif (isset($_POST['cancel'])) {
    // cancelled -- display default page
    print_form($object_class, "create", 0);
} else {
    print_form($object_class, $edit_mode, $object_id);
}

echo "      </admin>\n";
echo "    </main>\n";

require "xml/footer.xml.php";
echo "\n";

echo "</page>\n";
?>
