<!-- $Id: basic_xhtml.xsl,v 1.3 2002/10/14 15:12:05 loki Exp $ -->
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
    <table width="100%">
      <tr>
        <xsl:apply-templates select="header"/>
      </tr>
      <tr>
        <td>
          <xsl:apply-templates select="sidebar"/>
        </td>
        <td>
          <xsl:apply-templates select="main"/>
        </td>
      </tr>
      <tr>
        <xsl:apply-templates select="footer"/>
      </tr>
    </table>
  </body>
</html>
</xsl:template>

</xsl:stylesheet>
