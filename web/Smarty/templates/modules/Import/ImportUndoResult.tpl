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

<input type="hidden" name="module" value="{$FOR_MODULE}" />
<table style="width:70%;margin-left:auto;margin-right:auto;margin-top:10px;" cellpadding="10" cellspacing="10" class="searchUIBasic">
	<tr>
		<td class="heading2" align="left" colspan="2">
			{'LBL_IMPORT'|@getTranslatedString:$MODULE} {$FOR_MODULE|@getTranslatedString:$FOR_MODULE} - {'LBL_UNDO_RESULT'|@getTranslatedString:$MODULE}
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
		<td colspan="2" valign="top">
			<table cellpadding="10" cellspacing="0" align="center" class="dvtSelectedCell thickBorder">
				<tr>
					<td>{'LBL_TOTAL_RECORDS'|@getTranslatedString:$MODULE}</td>
					<td width="10%">:</td>
					<td width="10%">{$TOTAL_RECORDS}</td>
				</tr>
				<tr>
					<td>{'LBL_NUMBER_OF_RECORDS_DELETED'|@getTranslatedString:$MODULE}</td>
					<td width="10%">:</td>
					<td width="10%">{$DELETED_RECORDS_COUNT}</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td align="right" colspan="2">
		{include file='modules/Import/Import_Done_Buttons.tpl'}
		</td>
	</tr>
</table>