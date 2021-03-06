{**
 * plugins/paymethod/paypal/templates/paymentForm.tpl
 *
 * Copyright (c) 2013-2015 Simon Fraser University Library
 * Copyright (c) 2006-2009 Gunther Eysenbach, Juan Pablo Alperin
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Form for submitting a PayPal payment
 *}
{strip}
{assign var="pageTitle" value="plugins.paymethod.liqpay"}
{include file="common/header.tpl"}
{/strip}

<p><img src="{$baseUrl}/plugins/paymethod/liqpay/images/liqpay5.png" alt="liqpay" /></p>
<p>{translate key="plugins.paymethod.liqpay.warning"}</p>

	{include file="common/formErrors.tpl"}
	{if $params.item_name}
	<table class="data" width="100%">
		<tr>
			<td class="label" width="20%">{translate key="plugins.paymethod.liqpay.purchase.title"}</td>
			<td class="value" width="80%"><strong>{$params.item_name|escape}</strong></td>
		</tr>
	</table>
	{/if}
	{if $params.amount}
	<table class="data" width="100%">
		<tr>
			<td class="label" width="20%">{translate key="plugins.paymethod.liqpay.purchase.fee"}</td>
			<td class="value" width="80%"><strong>{$params.amount|string_format:"%.2f"}{if $params.currency_code} ({$params.currency_code|escape}){/if}</strong></td>
		</tr>
	</table>
	{/if}
	{if $params.item_description}
	<table class="data" width="100%">
		<tr>
			<td class="label" colspan="2">{$params.item_description|escape|nl2br}</td>
		</tr>
	</table>
	{/if}


	<p>{$formliqpay}</p>

{include file="common/footer.tpl"}
