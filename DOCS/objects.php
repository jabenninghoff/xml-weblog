<?php
print '<?xml version="1.0" encoding="iso-8859-1"?>'."\n";
?>
<!DOCTYPE html  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<!-- $Id: objects.php,v 1.3 2002/07/03 23:33:47 loki Exp $ -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>XML Weblog Objects</title>
    <style type="text/css">
      body {
        font-family: verdana, arial, helvetica, sans-serif;
        font-size: 10pt; 
      }
      h1, h2, h3 { font-family: arial, helvetica, sans-serif; }
      h3 {
        font-weight: normal;
        font-size: 12pt;
      }
    </style>
  </head>
  <body>
    <h1>XML Weblog Objects</h1>
      <p>
        Description of the XML-wl application. Uses XML, XSLT, PHP and MySQL
        to store &amp; display XHTML web pages.
      </p>
    <h2>site</h2>
      <p>
        A <strong>site</strong> is the root object for the XML weblog. It is
        not represented in the internal XML source document. The site is a
        record in the <strong>sites</strong> table of the <strong>xml-master
        </strong> database; each record is an unique URI, and multiple URIs may
        point to the same site. The record holds all site-wide settings and
        configuration variables, specifically:
      </p>
      <ul>
        <li>site URI (index column)</li>
        <li>site name</li>
        <li>database name (<strong>xml-<em>site_name</em>)</strong></li>
        <li>logo (stored as an image in the database)</li>
        <li>slogan</li>
        <li>administrator/author email address</li>
        <li>number of articles on front page</li>
        <li>...</li>
      </ul>
      <p>
        A site is allowed to have different settings, logos, etc, depending on
        which URI is used to access the site. (while sharing the same database)
        A visitor accessing the web server will be presented with the site
        configuration matching the URI row.
      </p>
    <h2>page</h2>
      <p>
        A <strong>page</strong> consists of a <strong>header</strong>,
        <strong>footer</strong>, and <strong>blocks</strong>, normally
        represented as an XHTML web page. The "front page" of a site displays
        the header, left blocks, the <strong>main</strong> block, right blocks,
        and the footer. As described below, the <strong>articles</strong> are
        displayed in the main block.
      </p>
      <p>
        Pages currently have no attributes, only children, and are implemented
        only in php code. Internally, pages are represented as XML and are
        transformed using XSLT/CSS to XHTML (or some other format).
      </p>
    <h2>header</h2>
    <h2>footer</h2>
    <h2>block</h2>
    <h3>main block</h3>
    <h3>static block</h3>
    <h3>code block</h3>
    <h2>article</h2>
    <h2>user</h2>
    <h2>comment</h2>
      <p>
        <strong>users</strong> and <strong>comments</strong> will be
        implemented in version 2.0.
      </p>
<?php
print '<p><a href="http://validator.w3.org/check?uri=http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].';ss=1">validate</a></p>'."\n";
?>
  </body>
</html>
