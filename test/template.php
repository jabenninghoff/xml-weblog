<?php include_once "include/functions.inc.php"; ?>
<?xml version="1.0" encoding="iso-8859-1" standalone="yes"?>
<!-- $Id: template.php,v 1.2 2002/10/14 15:12:05 loki Exp $ -->
<!-- XML weblog template/test page -->

<!-- page: defines a single weblog "page" -->
<page lang="x-klingon" title="page title (generated)">

  <!-- header: top of the page, with logo, slogan, etc.  -->
  <header>
    <name>site.name</name>
    <slogan>site.slogan</slogan>
    <logo>site.logo (image,optional)</logo>
    <url>site.url</url>
    <description>site.description</description>
    <content>site.header_content (XHTML+,optional)</content>
    <banner>banner (generated,optional)</banner>
    <!-- one or more messages -->
    <message>site.message</message>
  </header>

  <!--
    sidebar: either left- or right-aligned. contains 0 or more blocks. multiple
      sidebars are allowed, however, normally there is only one left and one
      right sidebar.
    -->
  <sidebar align="left" index="0">

    <!--
      block: a block of text in the header, footer, or sidebar. May be static or
        dynamic content (implemented on the back-end with <include> tag)
      -->
    <block index="0">

      <!-- block title: name of block -->
      <title>block.title</title>

      <!-- block content -->
      <content>block.content (XHTML+)</content>
    </block>

    <block index="1">
      <title>block.title</title>
      <content>block.content (XHTML+)</content>
    </block>

    <!-- ... additional blocks ... -->

  </sidebar>

  <!-- main: main section of document. index page contains articles. -->
  <main>
    <!-- 0 or more articles, default 10 most recent -->
    <!-- article: -->
    <article>

      <!-- metadata -->
      <id>article.id</id>
      <topic>article.topic</topic>
      <language>article.language</language>
      <url>article url (generated)</url>

      <!-- "header" info -->
      <title>article.title</title>
      <author>article.author</author>
      <date>article.date</date>

      <!-- actual content -->
      <leader>article.leader (XHTML+)</leader>
      <content>article.content (XHTML+)</content>

      <!-- comments (not yet implemented) -->
    </article>
  </main>

  <!-- footer: bottom of page, includes disclaimer -->
  <footer>
    <disclaimer>site.disclaimer</disclaimer>
    <content>
      site.footer_content (XHTML+,optional)
      <?php validate_self(); ?>
    </content>
  </footer>
</page>
