<!-- $Id: basic_xhtml.xsl,v 1.5 2002/10/15 04:14:37 loki Exp $ -->
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" indent="yes" encoding="ISO-8859-1"/>

<xsl:template match="page">
<xsl:text disable-output-escaping="yes">
&lt;!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "/DTD/xhtml1-strict.dtd">
</xsl:text>
<html xmlns="http://www.w3.org/1999/xhtml">
  <xsl:attribute name="xml:lang">
    <xsl:value-of select="@lang"/>
  </xsl:attribute>
  <xsl:attribute name="lang">
    <xsl:value-of select="@lang"/>
  </xsl:attribute>
  <head>
    <title><xsl:value-of select="@title"/></title>
  </head>
  <body>
    <xsl:apply-templates select="header"/>
    <table width="100%">
      <tr>
        <td>
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
  <p><xsl:copy-of select="banner/text()|banner/*"/></p>
  <h1>
    <img>
      <xsl:attribute name="src">
        <xsl:value-of select="logo"/>
      </xsl:attribute>
      <xsl:attribute name="alt">
        <xsl:value-of select="logo"/>
      </xsl:attribute>
    </img>
    <xsl:value-of select="name"/>
  </h1>
  <p>
    <a>
      <xsl:attribute name="href">
        <xsl:value-of select="url"/>
      </xsl:attribute>
      <xsl:value-of select="url"/>
    </a>:
    <xsl:copy-of select="description/text()|description/*"/>
  </p>
  <p><xsl:copy-of select="slogan/text()|slogan/*"/></p>
  <p><xsl:copy-of select="content/text()|content/*"/></p>
  <p><xsl:copy-of select="message/text()|message/*"/></p>
</xsl:template>

<xsl:template match="footer">
  <xsl:copy-of select="content/*"/>
  <p><xsl:value-of select="disclaimer"/></p>
</xsl:template>

<xsl:template match="text()">
  <xsl:if test="string-length(normalize-space(current())) != 0">
    <xsl:value-of select="."/><br/>
  </xsl:if>
</xsl:template>

</xsl:stylesheet>
