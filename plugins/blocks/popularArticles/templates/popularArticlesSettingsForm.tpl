{**
 * plugins/blocks/popularArticles/templates/popularArticlesSettingsForm.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Popular articles plugin management form.
 *
 *}
{strip}
{assign var="pageTitle" value="plugins.blocks.popularArticles.displayName"}
{include file="common/header.tpl"}
{/strip}
<script type="text/javascript">
	$(function() {ldelim}
		// Attach the form handler.
		$('#popularArticlesSettingsForm').pkpHandler('$.pkp.controllers.form.FormHandler');
	{rdelim});
</script>

<form class="pkp_form" id="popularArticlesSettingsForm" method="post" action="{plugin_url path="save"}">

	{include file="controllers/notification/inPlaceNotification.tpl" notificationId="popularArticlesSettingsFormNotification"}

	{fbvFormArea id="popularArticlesSettings"}
		{fbvFormSection for="maxArticlesCount" description="plugins.blocks.popularArticles.settings.maxArticlesCount.description"}
			{fbvElement type="text" id="maxArticlesCount" value=$maxArticlesCount size=$fbvStyles.size.SMALL}
		{/fbvFormSection}
		{fbvFormSection for="months" description="plugins.blocks.popularArticles.settings.months.description"}
			{fbvElement type="text" id="months" value=$months size=$fbvStyles.size.SMALL}
		{/fbvFormSection}
		{fbvFormSection for="mixOldStats" description="plugins.blocks.popularArticles.settings.mixOldStats.description" list=true}
			{fbvElement type="checkbox" id="mixOldStats" checked=$mixOldStats value=1 label="plugins.blocks.popularArticles.settings.mixOldStats.label"}
		{/fbvFormSection}
	{/fbvFormArea}
	{fbvFormButtons id="popularArticlesSettingsFormSubmit" submitText="common.save" hideCancel=true}
</form>
{include file="common/footer.tpl"}
