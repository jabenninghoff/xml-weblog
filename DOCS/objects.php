<?php
print '<?xml version="1.0" encoding="iso-8859-1"?>'."\n";
?>
<!DOCTYPE html  PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "DTD/xhtml1-strict.dtd">
<!-- $Id: objects.php,v 1.1 2002/06/23 07:16:06 loki Exp $ -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title>XML Weblog Objects</title>
    <style type="text/css">
      body {
        font-family: verdana, arial, helvetica, sans-serif;
        font-size: 10pt; 
      }
      h1, h2, h3 { font-family: arial, helvetica, sans-serif; }
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
        record in the <strong>sites</strong> table of the
        <strong>xml-master</strong> database; each record is an unique URI, and
        multiple URIs may point to the same site. The record holds all
        site-wide settings and configuration variables, specifically:
      </p>
      <ul>
        <li>site URI (index column)</li>
        <li>site name</li>
        <li>database name</li>
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
      <p>A <strong>page</strong> is a ... 
    <h2>header</h2>
    <h2>footer</h2>
<?php
print '<p><a href="http://validator.w3.org/check?uri=http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].';ss=1">validate</a></p>'."\n";
?>
  </body>
</html>
