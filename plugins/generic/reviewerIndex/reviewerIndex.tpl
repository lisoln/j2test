{**
 * reviewerIndex.tpl
 *
 * Copyright (c) 2009 Mahmoud Saghaei
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Form for list of reviewers who have completed a review.
 *
 *}
{assign var="pageTitle" value="plugins.generic.reviewerindex.displayName"}
{include file="common/header.tpl"}
<input type="button" class="button" value="{translate key="plugins.generic.reviewerindex.printversion"}" onclick="javascript:openRTWindow('{url page="about" op="reviewerIndex" path="1"}');" />

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

{include file="common/footer.tpl"}
