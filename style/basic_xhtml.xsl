<!-- $Id: basic_xhtml.xsl,v 1.18 2002/10/29 23:28:51 loki Exp $ -->
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" indent="yes" encoding="ISO-8859-1"
    doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"
    doctype-system="/DTD/xhtml1-strict.dtd"/>

<xsl:template match="page">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{@lang}" lang="{@lang}">
  <head>
    <title><xsl:value-of select="@title"/></title>
    <style type="text/css">
      p.center {text-align: center}
      img {border-width: 0 }
      table.head {border: solid gray}
    </style>
  </head>
  <body>
    <xsl:apply-templates select="header"/>
    <table width="100%">
      <tr>
        <td valign="top">
          <xsl:apply-templates select="sidebar"/>
        </td>
        <td valign="top">
          <xsl:apply-templates select="main"/>
        </td>
      </tr>
    </table>
    <xsl:apply-templates select="footer"/>
  </body>
</html>
</xsl:template>

<xsl:template match="header">
  <p class="center"><xsl:copy-of select="banner/text()|banner/*"/></p>
  <table class="head" width="100%">
    <tr>
       <td><a href="index.php"><img src="{logo}" alt="{name}"/></a></td>
       <td>
         <xsl:apply-templates select="content"/><br/>
       </td>
    </tr>
  </table>
  <p class="center"><xsl:copy-of select="slogan/text()|slogan/*"/></p>
  <hr/>
  <xsl:apply-templates select="message"/>
</xsl:template>

<xsl:template match="content">
  <xsl:copy-of select="./*"/>
</xsl:template>

<xsl:template match="message">
  <p class="center"><xsl:copy-of select="./text()|./*"/></p>
  <hr/>
</xsl:template>

<xsl:template match="footer">
  <xsl:apply-templates select="content"/>
  <p><xsl:value-of select="disclaimer"/></p>
</xsl:template>

<xsl:template match="block">
  <p>
    <b><xsl:value-of select="title"/></b><br/>
    <xsl:copy-of select="content/text()|content/*"/>
  </p>
</xsl:template>

<xsl:template match="article">
  <h3><xsl:value-of select="title"/></h3>
  <p><img src="{topic/icon}" alt="{topic/name}"/></p>
  <xsl:copy-of select="leader/*"/>
  <xsl:if test="@content">
    <xsl:copy-of select="content/*"/>
    <hr/>
  </xsl:if>
    <p>
      posted by <b><xsl:value-of select="author"/></b> on
      <xsl:value-of select="date"/>
      <xsl:if test="not(@content)">
        <b><a href="{url}">Read More...</a></b>
      </xsl:if>
    </p>
  <xsl:if test="not(@content)">
    <hr/>
  </xsl:if>
</xsl:template>

<xsl:template match="admin">
  <xsl:apply-templates select="menu"/>
  <h2><xsl:value-of select="title"/></h2>
  <xsl:if test="object">
    <table>
      <xsl:for-each select="object">
        <xsl:if test="position()=1">
          <tr>
            <xsl:for-each select="property">
              <th><xsl:value-of select="@name"/></th>
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

<xsl:template match="comment()">
  <xsl:comment><xsl:value-of select="."/></xsl:comment>
</xsl:template>

</xsl:stylesheet>
