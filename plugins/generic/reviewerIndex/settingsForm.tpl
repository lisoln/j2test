{**
 * settingsForm.tpl
 *
 * Copyright (c) 2009 Mahmoud Saghaei
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Form for list of reviewers who have completed a review.
 *
 *}
{assign var="pageTitle" value="plugins.generic.reviewerindex.displayName"}
{include file="common/header.tpl"}

{translate key="plugin.generic.reviewerindex.introduction"}

<br />

<form method="post" action="{plugin_url path="settings"}" enctype="multipart/form-data">

{include file="common/formErrors.tpl"}
{literal}
<script type="text/javascript">
<!--
// Move author up/down
function toggleHidShow() {
	if (document.getElementById("hide_show").checked) {
		document.getElementById("searchTools").style["display"] = "none";
	} else {
		document.getElementById("searchTools").style["display"] = "block";
	}
}
// -->
</script>
{/literal}
<div class="separator">&nbsp;</div>
<table>
<tr>
<td><input type="submit" class="button defaultButton" name="listReviewers" value="{translate key="plugins.generic.reviewerindex.listReviewers"}" />
<input type="button" value="{translate key="common.cancel"}" class="button" onclick="document.location.href='{url page="manager" op="plugins" escape=false}'" /></td>
<td align="right">
<label for="hide_show">{translate key="plugins.generic.reviewerindex.hidetools"}</label><input type="checkbox" name="hideTools" id="hide_show" onclick="toggleHidShow()" value="hide" {if $hideTools}checked="checked"{/if}/>
</td>
</tr>
</table>
<div id="searchTools" style="display: block">
<table class="listing">
<tr>
<td>
<fieldset id="fieldset_select_fields">
<legend>{translate key="plugins.generic.reviewerindex.selectfields"}:</legend>
{html_options_translate name=selectedFields[] options=$Fields selected=$selectedFields size="12" multiple="multiple"}
</fieldset>
</td>
<td>
<fieldset id="fieldset_display_order">
<legend>{translate key="plugins.generic.reviewerindex.sortorder"}:</legend>
	{section name=dispOrder start=0 loop=9 step=1}
	{assign var='index' value=$smarty.section.dispOrder.index}
	{html_options_translate name=selectedOrderField[$index] options=$orderFields all_extra="class=\"selectMenu\"" selected=$selectedOrderField[$index]}
	{html_radios name="order[$index]" options=$orders selected=$order[$index] separator="&nbsp;"}
	<br />
	{/section}
</fieldset>
</td>
</tr>
<tr>
<td colspan="2" align="center">
<fieldset id="fieldset_date_fields">
<legend>{translate key="plugins.generic.reviewerindex.dateinterval"}:</legend>
<table>
     <tr>
          <td align="right">{translate key="common.between"}</td>
          <td>{html_select_date prefix="fromDate" time=$dateFrom year_empty="" month_empty="" day_empty="" start_year="-5" end_year="+1"}</td>
          <td align="right">{translate key="common.and"}</td>
          <td>{html_select_date prefix="toDate" time=$dateTo year_empty="" month_empty="" day_empty="" start_year="-5" end_year="+1"}</td>
     </tr>
</table>
</fieldset>
</td>
</tr>
</table>
</div>
</form>
{literal}
<script type="text/javascript" language="javascrpt">
toggleHidShow();
</script>
{/literal}
<div class="separator">&nbsp;</div>
<br />
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
