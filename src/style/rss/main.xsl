<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:dc="http://purl.org/dc/elements/1.1/">

<xsl:output method="xml" indent="yes" encoding="utf-8"
    omit-xml-declaration="no"/>

<xsl:variable name="rootURL" select="page/header/url"/>

<xsl:template match="page">
    <rss version="2.0">
        <channel>
            <xsl:apply-templates select="header"/>
            <xsl:apply-templates select="main"/>
        </channel>
    </rss>
</xsl:template>

<xsl:template match="header">
    <title><xsl:value-of select="name"/></title>
    <link><xsl:value-of select="url"/>index.php</link>
    <description><xsl:value-of select="description"/></description>
    <!-- unimplemented
        <language></language>
        <copyright></copyright>
        <managingEditor></managingEditor>
        <webMaster></webMaster>
        <lastBuildDate></lastBuildDate>
        <category></category>
        <cloud></cloud>
        <ttl></ttl>
        <image></image>
      -->
    <generator>xml-weblog 1.1-BETA</generator>
    <docs>http://blogs.law.harvard.edu/tech/rss</docs>
</xsl:template>

<xsl:template match="article">
    <item>
        <title><xsl:value-of select="title"/></title>
        <link><xsl:value-of select="$rootURL"/><xsl:value-of select="url"/></link>
        <description>
            <xwl function="cdata_open"/><xsl:copy-of select="leader/*"/><xsl:copy-of select="content/*"/><xwl function="cdata_close"/>
        </description>
        <!-- disabled (duplicate)
        <author><xsl:value-of select="author/name"/> &lt;<xsl:value-of select="author/mail"/>&gt;</author>
        -->
        <xsl:element name="dc:creator"><xsl:value-of select="author/name"/></xsl:element>
        <category><xsl:value-of select="topic/name"/></category>
        <!-- unimplemented
            <comments></comments>
            <enclosure></enclosure>
          -->
        <guid isPermaLink="true"><xsl:value-of select="$rootURL"/><xsl:value-of select="url"/></guid>
        <pubDate><xsl:value-of select="date"/></pubDate>
    </item>
</xsl:template>

</xsl:stylesheet>
