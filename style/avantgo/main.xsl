<!-- $Id: main.xsl,v 1.5 2003/10/22 21:44:36 loki Exp $ -->
<!-- vim: set expandtab tabstop=2 softtabstop=2 shiftwidth=2: -->

<!--
   -
   - Copyright (c) 2002, 2003 John Benninghoff <john@benninghoff.org>.
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
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" indent="yes" encoding="iso-8859-1"
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
    <a href="avantgo.php"><img src="{logo}" alt="{name}"/></a><br/>
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
    posted by <b><xsl:value-of select="author"/></b> on
    <xsl:value-of select="date"/>
    <xsl:if test="not(@content='show') and normalize-space(content)">
      <b><a href="avantgo_{url}">Read More...</a></b>
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
