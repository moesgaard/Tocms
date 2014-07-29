<?xml version="1.0" encoding="ISO-8859-1"?>
<html xsl:version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns="http://www.w3.org/1999/xhtml">
  <body style="font-family:Arial;font-size:12pt;background-color:#EEEEEE">
      <xsl:for-each select="note">
       <div style="background-color:teal;color:white;padding:4px">
      <span style="font-weight:bold"><xsl:value-of select="module_name"/></span> - <xsl:value-of select="module_version"/>
       </div>
       <div style="margin-left:20px;margin-bottom:1em;font-size:10pt">
        created by:  <xsl:value-of select="author">
       <span style="font-style:italic">
        <xsl:value-of select="is_dev"/> Version
       </span>
       <span style="font-style:italic">
        <xsl:if test="is_optional = 0"/>
            This is not a optional package
        </xsl:if>
       </span>
       </div>
       </xsl:for-each>
       </br>
       </html>
