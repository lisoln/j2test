{**
 * printerFriendly.tpl
 *
 * Copyright (c) 2003-2007 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Reviewer Index -- printer friendly version.
 *
 * $Id: printerFriendly.tpl,v 1.18 2008/01/03 01:26:07 asmecher Exp $
 *}
{assign var="pageTitle" value="plugins.generic.reviewerindex.displayName"}
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{translate key=$pageTitle|escape}</title>
	<meta http-equiv="Content-Type" content="text/html; charset={$defaultCharset|escape}" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<link rel="stylesheet" href="{$baseUrl}/styles/common.css" type="text/css" />

	{foreach from=$stylesheets item=cssUrl}
		<link rel="stylesheet" href="{$cssUrl}" type="text/css" />
	{/foreach}

	<script type="text/javascript" src="{$baseUrl}/js/general.js"></script>
	{$additionalHeadData}
</head>
<body>

<div id="container">

<div id="body">

<div id="main">

<h2>{$siteTitle|escape}</h2>

<div id="content">
<h5>{translate key="plugins.generic.reviewerindex.displayName"} {translate key="search.dateFrom"} {$dateFrom|date_format:$dateFormatLong} {translate key="search.dateTo"} {$dateTo|date_format:$dateFormatLong}</h5>
<table width="80%" class="listing">
	<tr>
		<td colspan="{$cols}" class="headseparator">&nbsp;</td>
	</tr>
	<tr class="heading" valign="bottom">
	     {foreach from=$selectedFields item=f}
	          {assign var='key' value=$Fields.$f}
	          <td>{translate key=$key}</td>
	     {/foreach}
	</tr>
	<tr>
		<td colspan="{$cols}" class="headseparator">&nbsp;</td>
	</tr>
	{foreach from=$users item=user name=reviewer}
	<tr valign="top">
	     {foreach from=$selectedFieldNames item=f}
	          <td>{$user.$f|capitalize|escape}</td>
	     {/foreach}
	</tr>
	<tr>
	{if $smarty.foreach.reviewer.last}
		<td colspan="{$cols}" class="endseparator">&nbsp;</td>
	{else}
		<td colspan="{$cols}" class="separator">&nbsp;</td>
	{/if}
	</tr>
     {/foreach}
</table>
</div>

</div>
</div>
</div>

<script type="text/javascript">
<!--
	window.print();
// -->
</script>

</body>
</html>