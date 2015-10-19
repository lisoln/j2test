
	<tr>
		<td colspan="2"><h4>
            {translate key="plugins.paymethod.liqpay.settings"}
        </td>
	</tr>

	<tr valign="top">
		<td class="label" width="20%">
            {fieldLabel name="liqpaypubkey" required="true" key="plugins.paymethod.liqpay.settings.liqpaypubkey"}</td>
		<td class="value" width="80%">
			<input type="text" class="textField" name="liqpaypubkey" id="liqpaypubkey" size="50" value="{$liqpaypubkey|escape}" /><br/>
			{translate key="plugins.paymethod.liqpay.settings.liqpaypubkey.description"}<br/>
			&nbsp;
		</td>
	</tr>
	<tr valign="top">
		<td class="label" width="20%">
            {fieldLabel name="liqpayprivatkey" required="true" key="plugins.paymethod.liqpay.settings.liqpayprivatkey"}</td>
		<td class="value" width="80%">
			<input type="text" class="textField" name="liqpayprivatkey" id="liqpayprivatkey" value="{$liqpayprivatkey|escape}" /><br/>
			{translate key="plugins.paymethod.liqpay.settings.liqpayprivatkey.description"}
		</td>
	</tr>
    <tr valign="top">
        <td class="label" width="20%">
            {fieldLabel name="liqpaydebug" required="true" key="plugins.paymethod.liqpay.settings.liqpaydebug"}</td>
        <td class="value" width="80%">
            <input type="checkbox" class="textField" name="liqpaydebug" id="liqpaydebug" {if $liqpaydebug} checked {/if}" /><br/>
            {translate key="plugins.paymethod.liqpay.settings.liqpaydebug.description"}
        </td>
    </tr>
	{if !$isCurlInstalled}
		<tr>
			<td colspan="2">
				<span class="instruct">{translate key="plugins.paymethod.liqpay.settings.curlNotInstalled"}</span>
			</td>
		</tr>
	{/if}
