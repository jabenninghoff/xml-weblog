<!-- $Id: basic_xhtml.xsl,v 1.10 2002/10/18 22:00:12 loki Exp $ -->
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
        <td>
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
         <xsl:copy-of select="description/text()|description/*"/><br/>
         <xsl:copy-of select="content/text()|content/*"/><br/>
       </td>
    </tr>
  </table>
  <p class="center"><xsl:copy-of select="slogan/text()|slogan/*"/></p>
  <hr/>
  <p class="center"><xsl:copy-of select="message/text()|message/*"/></p>
  <hr/>
</xsl:template>

<xsl:template match="footer">
  <xsl:copy-of select="content/*"/>
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
  <xsl:for-each select="id|topic|language">
    <xsl:value-of select="name()"/>: <xsl:value-of select="."/>
    <xsl:text> </xsl:text>
  </xsl:for-each>
  <br/>
  <xsl:copy-of select="leader/*"/>
  <p>
    posted by <b><xsl:value-of select="author"/></b> on
    <xsl:value-of select="date"/><br/>
    <b><a href="{url}">Read More...</a></b>
  </p>

  <!-- main content not normally displayed here -->
  <p><i>main content:</i></p>
  <xsl:copy-of select="content/*"/>
  <hr/>
</xsl:template>

<xsl:template match="text()">
  <xsl:if test="normalize-space(.)">
    <xsl:value-of select="."/><br/>
  </xsl:if>
</xsl:template>

</xsl:stylesheet>
