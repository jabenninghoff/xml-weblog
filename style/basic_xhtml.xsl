<!-- $Id: basic_xhtml.xsl,v 1.2 2002/10/12 01:08:21 loki Exp $ -->
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" indent="yes"/>

<xsl:template match="page">
<html>
  <xsl:attribute name="lang">
    <xsl:value-of select="language"/>
  </xsl:attribute>
  <head>
    <title><xsl:value-of select="title"/></title>
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
