{*
/*************************************
 * SPDX-FileCopyrightText: 2009-2020 Vtenext S.r.l. <info@vtenext.com> 
  * SPDX-License-Identifier: AGPL-3.0-only  
 ************************************/
*}
<script language="JavaScript" type="text/javascript" src="include/js/jquery.js"></script>
<script type="text/javascript" charset="utf-8">
	jQuery.noConflict();
</script>
<script language="JavaScript" type="text/javascript" src="modules/Import/resources/Import.js"></script>
{literal}
<script type="text/javascript">
jQuery(document).ready(function() {
	setTimeout(function() {
		jQuery("[name=importStatusForm]").get(0).submit();
		}, 5000); // crmv@90169
});
</script>
{/literal}

<form onsubmit="VteJS_DialogBox.block();" action="index.php" enctype="multipart/form-data" method="POST" name="importStatusForm">
	<input type="hidden" name="__csrf_token" value="{$CSRF_TOKEN}"> {* crmv@171581 *}
	<input type="hidden" name="module" value="{$FOR_MODULE}" />
	<input type="hidden" name="action" value="Import" />
	{if $CONTINUE_IMPORT eq 'true'}
	<input type="hidden" name="mode" value="continue_import" />
	{else}
	<input type="hidden" name="mode" value="" />
	{/if}
</form>
<table style="width:70%;margin-left:auto;margin-right:auto;margin-top:10px;" cellpadding="10" cellspacing="10" class="searchUIBasic">
	<tr>
		<td class="heading2" align="left" colspan="2">
			{'LBL_IMPORT'|@getTranslatedString:$MODULE} {$FOR_MODULE|@getTranslatedString:$FOR_MODULE} - 
			<span class="style1">{'LBL_RUNNING'|@getTranslatedString:$MODULE} ... </span>
		</td>
	</tr>
	{if $ERROR_MESSAGE neq ''}
	<tr>
		<td class="style1" align="left" colspan="2">
			{$ERROR_MESSAGE}
		</td>
	</tr>
	{/if}
	<tr>
		<td valign="top">
			<table cellpadding="10" cellspacing="0" align="center" class="dvtSelectedCell thickBorder">
				<tr>
					<td>{'LBL_TOTAL_RECORDS_IMPORTED'|@getTranslatedString:$MODULE}</td>
					<td width="10%">:</td>
					<td width="30%">{$IMPORT_RESULT.IMPORTED} / {$IMPORT_RESULT.TOTAL}</td>
				</tr>
				<tr>
					<td colspan="3">
						<table cellpadding="10" cellspacing="0" class="calDayHour">
							<tr>
								<td>{'LBL_NUMBER_OF_RECORDS_CREATED'|@getTranslatedString:$MODULE}</td>
								<td width="10%">:</td>
								<td width="10%">{$IMPORT_RESULT.CREATED}</td>
							</tr>
							<tr>
								<td>{'LBL_NUMBER_OF_RECORDS_UPDATED'|@getTranslatedString:$MODULE}</td>
								<td width="10%">:</td>
								<td width="10%">{$IMPORT_RESULT.UPDATED}</td>
							</tr>
							<tr>
								<td>{'LBL_NUMBER_OF_RECORDS_SKIPPED'|@getTranslatedString:$MODULE}</td>
								<td width="10%">:</td>
								<td width="10%">{$IMPORT_RESULT.SKIPPED}</td>
							</tr>
							<tr>
								<td>{'LBL_NUMBER_OF_RECORDS_MERGED'|@getTranslatedString:$MODULE}</td>
								<td width="10%">:</td>
								<td width="10%">{$IMPORT_RESULT.MERGED}</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="right">
		<input type="button" name="cancel" value="{'LBL_CANCEL_IMPORT'|@getTranslatedString:$MODULE}" class="crmButton small delete"
			   onclick="location.href='index.php?module={$FOR_MODULE}&action=Import&mode=cancel_import&import_id={$IMPORT_ID}'" />
		</td>
	</tr>
</table>