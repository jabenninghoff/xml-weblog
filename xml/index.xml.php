<?php
// $Id: index.xml.php,v 1.11 2002/10/30 15:43:03 loki Exp $

require_once "include/article.inc.php";
require_once "include/db.inc.php";
require_once "include/functions.inc.php";

// build variables
$site = fetch_site(base_url());
$block = fetch_block();
$message = fetch_message();
$article = fetch_article($site['article_limit']);

if (basename($_SERVER['PHP_SELF']) == "index.xml.php") {
    // standalone
    header('Content-Type: text/xml');
}

xml_declaration();
?>
<!-- XML-weblog front page -->
<page lang="en" title="<?php echo $site['name']; ?>">

<?php require "xml/header.xml.php"; ?>

<?php require "xml/sidebar.xml.php"; ?>

  <!-- main: main section of document. index page contains articles. -->
  <main>
    <!-- 0 or more articles, default 10 most recent. topmost is index 0 -->
<?php
$i = 0;
while ($article[$i]) {
    display_article($article[$i], $i++, "");
}
?>
  </main>

<?php require "xml/footer.xml.php"; ?>

</page>
