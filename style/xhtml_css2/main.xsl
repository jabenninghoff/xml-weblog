<!-- $Id: main.xsl,v 1.12 2002/11/15 00:43:04 loki Exp $ -->

<!--
   -
   - Copyright (c) 2002, John Benninghoff <john@benninghoff.org>.
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
<xsl:output method="xml" indent="yes" encoding="iso-8859-1"
    omit-xml-declaration="yes"
    doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"
    doctype-system="/DTD/xhtml1-strict.dtd"/>

<xsl:template match="page">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{@lang}" lang="{@lang}">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
    <title><xsl:value-of select="@title"/></title>
    <link rel="stylesheet" href="style/xhtml_css2/basic.css" type="text/css"/>
    <xsl:if test="sidebar[@align='left']">
      <style type="text/css">@import "style/xhtml_css2/sidebar_left.css";</style>
    </xsl:if>
    <xsl:if test="sidebar[@align='right']">
      <style type="text/css">@import "style/xhtml_css2/sidebar_right.css";</style>
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
  <div class="masthead">
    <a class="img" href="index.php"><img src="{logo}" alt="{name}"/></a>
    <div class="header-slogan">
      <p class="zero"><xsl:copy-of select="slogan/text()|slogan/*"/></p>
    </div>
    <div class="header-content">
      <xsl:apply-templates select="content"/>
    </div>
    <p class="break"/>
  </div>
  <xsl:apply-templates select="message"/>
</xsl:template>

<xsl:template match="content">
  <xsl:copy-of select="./*"/>
</xsl:template>

<xsl:template match="message">
  <div class="message"><p><xsl:copy-of select="./text()|./*"/></p></div>
</xsl:template>

<xsl:template match="footer">
  <xsl:apply-templates select="content"/>
  <p><span class="small"><xsl:copy-of select="disclaimer/*|disclaimer/text()"/></span></p>
</xsl:template>

<xsl:template match="block">
  <div class="block-title"><p class="zero"><b><xsl:value-of select="title"/></b></p></div>
  <div class="block-main"><p class="zero"><xsl:copy-of select="content/text()|content/*"/></p></div>
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
    </xsl:if>
    <div class="byline">
      <p class="zero">
        posted by <b><xsl:value-of select="author"/></b> on
        <xsl:value-of select="date"/>
        <xsl:if test="not(@content='show') and normalize-space(content)">
          <b><a href="{url}">Read More...</a></b>
        </xsl:if>
      </p>
    </div>
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
    <xsl:value-of select="."/><br class="br"/>
  </xsl:if>
</xsl:template>

</xsl:stylesheet>
