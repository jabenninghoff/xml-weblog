<!-- $Id: main.xsl,v 1.2 2002/10/31 09:25:13 loki Exp $ -->
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" indent="yes" encoding="ISO-8859-1"
    doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"
    doctype-system="/DTD/xhtml1-strict.dtd"/>

<xsl:template match="page">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{@lang}" lang="{@lang}">
  <head>
    <title><xsl:value-of select="@title"/></title>
    <link rel="stylesheet" href="style/xhtml_css2/default.css" type="text/css"/>
    <xsl:if test="sidebar[@align='left']">
      <link rel="stylesheet" href="style/xhtml_css2/sidebar_left.css" type="text/css"/>
    </xsl:if>
    <xsl:if test="sidebar[@align='right']">
      <link rel="stylesheet" href="style/xhtml_css2/sidebar_right.css" type="text/css"/>
    </xsl:if>
  </head>
  <body>
    <div class="header">
      <xsl:apply-templates select="header"/>
    </div>
    <xsl:if test="sidebar[@align='left']">
      <div class="sidebar-left">
        <xsl:apply-templates select="sidebar[@align='left']"/>
      </div>
    </xsl:if>
    <xsl:if test="sidebar[@align='right']">
      <div class="sidebar-right">
        <xsl:apply-templates select="sidebar[@align='right']"/>
      </div>
    </xsl:if>
    <div class="main">
      <xsl:apply-templates select="main"/>
    </div>
    <hr/>
    <div class="footer">
      <xsl:apply-templates select="footer"/>
    </div>
  </body>
</html>
</xsl:template>

<xsl:template match="header">
  <xsl:if test="banner">
    <div class="center"><p><xsl:copy-of select="banner/text()|banner/*"/></p></div>
  </xsl:if>
  <table class="head" width="100%" cellpadding="10">
    <tr>
       <td>
         <p><a class="img" href="index.php"><img src="{logo}" alt="{name}"/></a><br/>
         <xsl:copy-of select="slogan/text()|slogan/*"/></p>
       </td>
       <td align="right" valign="bottom">
         <xsl:apply-templates select="content"/>
       </td>
    </tr>
  </table>
  <xsl:apply-templates select="message"/>
  <xsl:if test="not(message)">
    <br/>
  </xsl:if>
</xsl:template>

<xsl:template match="content">
  <xsl:copy-of select="./*"/>
</xsl:template>

<xsl:template match="message">
  <div class="center"><p><xsl:copy-of select="./text()|./*"/></p></div>
  <hr/>
</xsl:template>

<xsl:template match="footer">
  <xsl:apply-templates select="content"/>
  <p><span class="small"><xsl:copy-of select="disclaimer/*|disclaimer/text()"/></span></p>
</xsl:template>

<xsl:template match="block">
  <p>
    <b><xsl:value-of select="title"/></b><br/>
    <xsl:copy-of select="content/text()|content/*"/>
  </p>
</xsl:template>

<xsl:template match="main">
  <xsl:for-each select="article">
    <h3><xsl:value-of select="title"/></h3>
    <p>
      <span class="topic-icon">
        <img src="{topic/icon}" alt="{topic/name}"/>
      </span>
    </p>
    <xsl:copy-of select="leader/*"/>
    <xsl:if test="@content='show'">
      <xsl:copy-of select="content/*"/>
      <hr/>
    </xsl:if>
      <p>
        posted by <b><xsl:value-of select="author"/></b> on
        <xsl:value-of select="date"/>
        <xsl:if test="not(@content='show') and normalize-space(content)">
          <b><a href="{url}">Read More...</a></b>
        </xsl:if>
      </p>
    <xsl:if test="not(@content='show') and position()!=last()">
      <xsl:comment><xsl:value-of select="last()"/></xsl:comment>
      <hr/>
    </xsl:if>
  </xsl:for-each>
  <xsl:apply-templates select="admin"/>
</xsl:template>

<xsl:template match="admin">
  <p>
    <xsl:apply-templates select="menu"/>
  </p>
  <h2><xsl:value-of select="title"/></h2>
  <xsl:if test="object">
    <table>
      <xsl:for-each select="object">
        <xsl:if test="position()=1">
          <tr>
            <xsl:for-each select="property">
              <td><b><xsl:value-of select="@name"/></b></td>
            </xsl:for-each>
          </tr>
        </xsl:if>
        <tr>
          <xsl:for-each select="property">
            <td><xsl:copy-of select="./text()|./*"/></td>
          </xsl:for-each>
        </tr>
      </xsl:for-each>
    </table>
  </xsl:if>
  <xsl:apply-templates select="content"/>
</xsl:template>

<xsl:template match="menu">
  <a href="{@link}"><xsl:value-of select="."/></a>
</xsl:template>

<xsl:template match="text()">
  <xsl:if test="normalize-space(.)">
    <xsl:value-of select="."/><br/>
  </xsl:if>
</xsl:template>

</xsl:stylesheet>
