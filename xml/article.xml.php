<?php
// $Id: article.xml.php,v 1.8 2002/10/30 15:43:03 loki Exp $

require_once "include/article.inc.php";
require_once "include/db.inc.php";
require_once "include/functions.inc.php";

if (basename($_SERVER['PHP_SELF']) == "article.xml.php") {
    // standalone
    header('Content-Type: text/xml');
}

// build variables
$site = fetch_site(base_url());
$block = fetch_block();

$id = valid_ID($_GET['id']);
$article = fetch_article_single($id);

xml_declaration();
?>
<page lang="en" title="<?php echo $site['name']; ?>">

<?php require "xml/header.xml.php"; ?>

<?php require "xml/sidebar.xml.php"; ?>

  <!-- main: main section of document. index page contains articles. -->
  <main>
<?php display_article($article, 0, "show"); ?>
  </main>

<?php require "xml/footer.xml.php"; ?>

</page>
