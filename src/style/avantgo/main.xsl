<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" indent="yes" encoding="utf-8"
    doctype-public="-//W3C//DTD HTML 3.2 Final//EN"/>

<xsl:template match="page">
<html>
  <head>
    <meta name="HandheldFriendly" content="True"/>
    <title><xsl:value-of select="@title"/></title>
  </head>
  <body>
    <xsl:apply-templates select="header"/>
    <xsl:apply-templates select="main"/>
    <xsl:apply-templates select="footer"/>
  </body>
</html>
</xsl:template>

<xsl:template match="header">
  <p>
    <a href="avantgo.php"><img src="assets/avantlogo.gif" alt="{name}"/></a><br/>
    <xsl:copy-of select="slogan/text()|slogan/*"/><br/>
    <xsl:copy-of select="content/p/*"/>
  </p>
  <hr/>
  <xsl:apply-templates select="message"/>
</xsl:template>

<xsl:template match="message">
  <p><xsl:copy-of select="./text()|./*"/></p>
  <hr/>
</xsl:template>

<xsl:template match="footer">
  <p><xsl:copy-of select="disclaimer/*|disclaimer/text()"/></p>
</xsl:template>

<xsl:template match="article">
  <h3><xsl:value-of select="title"/></h3>
  <p><u><xsl:value-of select="topic/name"/></u></p>
  <xsl:copy-of select="leader/*"/>
  <xsl:if test="@content='show'">
    <xsl:copy-of select="content/*"/>
  </xsl:if>
  <p>
    posted by <strong><xsl:value-of select="author/name"/></strong> on
    <xsl:value-of select="date"/>
    <xsl:if test="not(@content='show') and normalize-space(content)">
      <strong><a href="avantgo_{url}">Read More...</a></strong>
    </xsl:if>
  </p>
  <hr/>
</xsl:template>

<xsl:template match="text()">
  <xsl:if test="normalize-space(.)">
    <xsl:value-of select="."/><br class="br"/>
  </xsl:if>
</xsl:template>

</xsl:stylesheet>
