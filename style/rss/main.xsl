<!-- $Id: main.xsl,v 1.6 2004/06/16 16:13:49 loki Exp $ -->
<!-- vim: set expandtab tabstop=2 softtabstop=2 shiftwidth=2: -->

<!--
   -
   - Copyright (c) 2002 - 2004 John Benninghoff <john@benninghoff.org>.
   - All rights reserved.
   -
   - Redistribution and use in source and binary forms, with or without
   - modification, are permitted provided that the following conditions
   - are met:
   -
   - 1. Redistributions of source code must retain the above copyright
   -    notice, this list of conditions and the following disclaimer.
   - 2. Redistributions in binary form must reproduce the above copyright
   -    notice, this list of conditions and the following disclaimer in the
   -    documentation and/or other materials provided with the distribution.
   - 3. All advertising materials mentioning features or use of this software
   -    must display the following acknowledgement:
   -	This product includes software developed by John Benninghoff.
   - 4. Neither the name of the copyright holder nor the names of its 
   -    contributors may be used to endorse or promote products derived from
   -    this software without specific prior written permission.
   -
   - THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
   -  "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED
   - TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
   - PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
   - CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
   - EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
   - PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
   - PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
   - LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
   - NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
   - SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
   -
  -->

<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:dc="http://purl.org/dc/elements/1.1/">

<xsl:output method="xml" indent="yes" encoding="iso-8859-1"
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
            %cdata_open%<xsl:copy-of select="leader/*"/><xsl:copy-of select="content/*"/>%cdata_close%
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
