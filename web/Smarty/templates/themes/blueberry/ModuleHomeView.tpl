{*
/*************************************
 * SPDX-FileCopyrightText: 2009-2020 Vtenext S.r.l. <info@vtenext.com> 
  * SPDX-License-Identifier: AGPL-3.0-only  
 ************************************/
*}
{* crmv@83340 crmv@98431 crmv@102334 crmv@104259 crmv@105193 *}

<script language="JavaScript" type="text/javascript" src="{"modules/`$MODULE`/`$MODULE`.js"|resourcever}"></script>
<script language="JavaScript" type="text/javascript" src="{"include/js/dtlviewajax.js"|resourcever}"></script>
<script language="JavaScript" type="text/javascript" src="{"include/js/ListView.js"|resourcever}"></script>
<script language="JavaScript" type="text/javascript" src="{"include/js/SimpleListView.js"|resourcever}"></script>
<script language="JavaScript" type="text/javascript" src="{"include/js/ModuleHome.js"|resourcever}"></script>
<script language="JavaScript" type="text/javascript" src="{"modules/Charts/Charts.js"|resourcever}"></script>

{if $MODHOMEVIEWTYPE neq 'ListView'}
	<div id="modhome_loader" style="display:none;">
		{include file="LoadingIndicator.tpl" LIOLDMODE=true}
	</div>
	{include file='Buttons_List.tpl'}
{/if}

{if $CAN_ADD_HOME_BLOCKS}
	{assign var="FLOAT_TITLE" value=$APP.LBL_ADD_WIDGET}
	{assign var="FLOAT_WIDTH" value="400px"}
	{capture assign="FLOAT_CONTENT"}
	<input type="hidden" id="newblock_modhomeid" />
	<table width="100%" cellspacing="5" cellpadding="2" border="0">
		<tr>
			<td align="right" width="50%">
				<span>{$APP.LBL_CHOOSE_MODHOME_BLOCK_TYPE}</span>
			</td>
			<td align="left" width="50%">
				<select id="newblock_select" onchange="ModuleHome.loadNewBlockConfig()">
					<option value="">--{$APP.Select}--</option>
				{foreach item=block from=$HOME_BLOCK_TYPES}
					<option value="{$block.type}">{$block.label}</option>
				{/foreach}
				</select>
			</td>
		</tr>
	</table>
	<hr>
	<div id="newblock_config_div">
	</div>
	{/capture}
	{include file="FloatingDiv.tpl" FLOAT_ID="ChooseNewBlock" FLOAT_BUTTONS=""}
{/if}

{assign var="FLOAT_TITLE" value=$APP.NewModuleHomeView}
{assign var="FLOAT_WIDTH" value="400px"}
{capture assign="FLOAT_BUTTONS"}
{/capture}
{capture assign="FLOAT_CONTENT"}
<table border="0" cellspacing="2" cellpadding="3" width="100%" align="center">
	<tr>
		<td class="dvtCellLabel" align="right">
			{$APP.Name}
		</td>
		<td class="dvtCellInfo">
			<input type="text" name="homeviewname" id="homeviewname" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'">
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right">
			<button type="button" class="crmbutton save" onclick="ModuleHome.createView()">{$APP.LBL_CREATE}</button>
		</td>
	</tr>
</table>
{/capture}
{include file="FloatingDiv.tpl" FLOAT_ID="ModHomeAddView"}

{* crmv@199319 *}
{assign var="FLOAT_TITLE" value=$APP.LBL_EDIT_MODHOME_VIEW}
{assign var="FLOAT_WIDTH" value="400px"}
{capture assign="FLOAT_BUTTONS"}
{/capture}
{capture assign="FLOAT_CONTENT"}
	<table border="0" cellspacing="2" cellpadding="3" width="100%" align="center">
		<tr>
			<td class="dvtCellLabel" align="right">
				{$APP.Name}
			</td>
			<td class="dvtCellInfo">
				<input type="text" name="homevieweditname" id="homevieweditname" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'">
				<input type="hidden" name="modhomeidedit" value="">
			</td>
		</tr>
		<tr id="editViewCV">
			<td class="dvtCellLabel" align="right">
				{$APP.LBL_DEFAULT_FILTER}
			</td>
			<td class="dvtCellInfo">
				<select class="detailedViewTextBox" id="homecvidedit"></select>
			</td>
		</tr>
		<tr id="editViewReport">
			<td class="dvtCellLabel" align="right">
				Report
			</td>
			<td class="dvtCellInfo">
				<input type="text" name="chooserEditReportName" id="chooserEditReportName" class="detailedViewTextBox detailedViewTextBoxOff" readonly="" style="width:100%">
				<input type="hidden" id="chooserEditReportId" value="" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div id="reportChooserFolder" style="display:none;width:100%;height:350px;overflow-y:auto"></div>
				<div id="reportChooserList" style="display:none;width:100%;height:350px;overflow-y:auto;display:none"></div>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				<button type="button" class="crmbutton save" onclick="ModuleHome.doEdit()">{$APP.LBL_EDIT}</button>
			</td>
		</tr>
	</table>
{/capture}
{include file="FloatingDiv.tpl" FLOAT_ID="ModHomeEditView"}
{* crmv@199319 *}

{capture assign="FLOAT_CONTENT"}
<table border="0" cellspacing="2" cellpadding="3" width="100%" align="center">
	<tr>
		<td class="dvtCellLabel" align="right">
			{$APP.Name}
		</td>
		<td class="dvtCellInfo">
			<input type="text" name="homeviewname3" id="homeviewname3" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'">
		</td>
	</tr>
	<tr>
		<td class="dvtCellLabel" align="right">
			{$APP.LBL_DEFAULT_FILTER}
		</td>
		<td class="dvtCellInfo">
			<select class="detailedViewTextBox" id="homecvid"></select>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right">
			<button type="button" class="crmbutton save" onclick="ModuleHome.createListView()">{$APP.LBL_CREATE}</button>
		</td>
	</tr>
</table>
{/capture}
{include file="FloatingDiv.tpl" FLOAT_ID="ModHomeAddListView"}

{assign var="FLOAT_TITLE" value=$APP.AddModuleHomeViewReport}
{assign var="FLOAT_WIDTH" value="600px"}
{capture assign="FLOAT_BUTTONS"}
{/capture}
{capture assign="FLOAT_CONTENT"}
<table border="0" cellspacing="2" cellpadding="3" width="100%" align="center">
	<tr>
		<td class="dvtCellLabel" align="right">
			{$APP.Name}
		</td>
		<td class="dvtCellInfo">
			<input type="text" name="homeviewname2" id="homeviewname2" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'">
		</td>
	</tr>
	<tr>
		<td class="dvtCellLabel" align="right">
			Report
		</td>
		<td class="dvtCellInfo">
			<input type="text" name="chooserReportName" id="chooserReportName" class="detailedViewTextBox detailedViewTextBoxOff" readonly="" style="width:100%">
			<input type="hidden" id="chooserReportId" value="" />
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div id="reportChooserFolder" style="display:none;width:100%;height:350px;overflow-y:auto"></div>
			<div id="reportChooserList" style="display:none;width:100%;height:350px;overflow-y:auto;display:none"></div>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right">
			<button type="button" class="crmbutton save" onclick="ModuleHome.createReportView()">{$APP.LBL_CREATE}</button>
		</td>
	</tr>
</table>

{/capture}
{include file="FloatingDiv.tpl" FLOAT_ID="ModHomeAddViewReport"}

<div id="Buttons_List_HomeMod" class="level4Bg listview-tabs">
	<table id="bl3" border=0 cellspacing=0 cellpadding=2 width=100% class="small">
		<tr height="34">
			<td align="left" valign="middle">
				{* if count($MODHOMEVIEWS) > 1}
				<div class="pull-left">
					Configurazione:
					&nbsp;&nbsp;&nbsp;&nbsp;
				</div>
				<div class="pull-left">
					<select class="" id="modhomeSelect" style="max-width:200px" onchange="ModuleHome.changeView(jQuery(this).val())">
						{foreach item=VIEW from=$MODHOMEVIEWS}
							<option value="{$VIEW.modhomeid}" {if $MODHOMEID == $VIEW.modhomeid}selected=""{/if}>{$VIEW.name}</option>
						{/foreach}
					</select>
				</div>
				{/if *}

				<div class="pull-left" style="position:relative">
					{* @nileio this code is buggy responsbile for clicking on a tab but not changing it. TODO: fix this *}
					{* HORRIBLE HORRIBLE CODE!! Please use CSS and proper classes, not this shit!! *}
					<table border="0" cellspacing="0" cellpadding="3" width="100%" style="position:relative;top:3px;height:30px">
					<tr>
					{foreach item=VIEW from=$MODHOMEVIEWS}
						{if $MODHOMEID == $VIEW.modhomeid}
							{assign var="_class" value="dvtSelectedCell"}
							{assign var="VIEWNAME" value=$VIEW.name}
						{else}
							{assign var="_class" value="dvtUnSelectedCell"}
						{/if}
						{*@nileio modified to the best i could to make it at least better - note clicks should now be on teh whole td for the unselected tab
						fixes an issue where clicks were only on the label*}
						<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
						<td class="{$_class}" align="center" id="tdViewTab_{$VIEW.modhomeid}" nowrap=""  {if $_class=="dvtUnSelectedCell"} onclick="ModuleHome.changeView('{$VIEW.modhomeid}')"{/if}>
							{if $_class=="dvtUnSelectedCell"}<a href="javascript:void(0)" onclick="ModuleHome.changeView('{$VIEW.modhomeid}')">{$VIEW.name}</a>
							{else}
								<span>{$VIEW.name}</span>
							{/if}
							{if ($CAN_DELETE_HOME_VIEWS || $CAN_ADD_HOME_BLOCKS) && $_class == 'dvtSelectedCell'}
							<div class="dropdown vcenter" id="editModHomeBlocks_{$VIEW.modhomeid}">
								<span id="pencil_{$VIEW.modhomeid}" data-toggle="dropdown" style="width:20px;height:18px;">
									<i class="vteicon valign-bottom md-sm md-link mav-menu-hover-icon">expand_more</i> {* crmv@120023 *} {*@nileio *}
								</span>
								<ul class="dropdown-menu dropdown-autoclose">
									{if $CAN_DELETE_HOME_VIEWS}
									<!-- sanitize the name -->
									{assign var="VIEWNAME_SAFE" value='"'|str_replace:"&quot;":$VIEWNAME}
									{assign var="VIEWNAME_SAFE" value="'"|str_replace:"\'":$VIEWNAME_SAFE}
									<li><a href="javascript:void(0);" style="padding: 5px;" onclick="ModuleHome.removeView('{$VIEW.modhomeid}', '{$VIEWNAME_SAFE}', true)">
										<i class="vteicon md-sm"  id="remove_custom_tab" style="padding-right: 5px">remove</i><span style="vertical-align: top;">{$APP.LBL_REMOVE_MODHOME_VIEW}</span></a>
									</li>
									<li><a href="javascript:void(0);" style="padding: 5px;" onclick="ModuleHome.editView('{$VIEW.modhomeid}', '{$VIEWNAME_SAFE}', '{$VIEW.reportid}', '{$VIEW.cvid}')">
										<i class="vteicon md-sm"  id="edit_custom_tab" style="padding-right: 5px">edit</i><span style="vertical-align: top;">{$APP.LBL_EDIT_MODHOME_VIEW}</span></a>
										</li>{* crmv@199319 *}
									{/if}
									{if $CAN_ADD_HOME_BLOCKS && !$VIEW.reportid && !$VIEW.cvid}
									<li><a href="javascript:void(0);" onclick="ModuleHome.chooseNewBlock('{$VIEW.modhomeid}')">{$APP.LBL_ADD_WIDGET}</a></li>
									{/if}
								</ul>
							</div>
							{/if}
							
						</td>
					{/foreach}
					</tr>
					</table>
				</div>

				{if $CAN_ADD_HOME_VIEWS}
				<div class="pull-left" id="add_home_views" style="padding-top:8px;padding-left:8px;">
					<div class="dropdown" id="editModHomeViews">
						<a data-toggle="dropdown">
							<i class="vteicon md-link mav-menu-hover-icon" style="vertical-align:middle" title="{$APP.LBL_ADD_ITEM} tab">add</i>

						</a>
						<ul class="dropdown-menu dropdown-autoclose">
							<li><a href="javascript:void(0);" onclick="ModuleHome.addView()">{$APP.AddModuleHomeView}</a></li>
							<li><a href="javascript:void(0);" onclick="ModuleHome.addListView()">{$APP.AddModuleHomeListView}</a></li>
							<li><a href="javascript:void(0);" onclick="ModuleHome.addReportView()">{$APP.AddModuleHomeViewReport}</a></li>
						</ul>
					</div>
				</div>
				{/if}

			</td>
		</tr>
	</table>
</div>
{* <script type="text/javascript">calculateButtonsList3();</script> *}


<input type="hidden" name="blockcolumns" id="blockcolumns" value="4" />

<div id="ModuleHomeMatrix" class="ModuleHomeMatrix">
	
	{foreach item=VIEW from=$MODHOMEVIEWS}
		{if $VIEW.modhomeid != $MODHOMEID}
			{continue} {* crmv@181170 *}
		{/if}
		
		<input type="hidden" name="modhomeid" id="modhomeid" value="{$MODHOMEID}">
		
		{assign var="BLOCKIDS" value=$VIEW.blockids}
	
		{if count($VIEW.blocks) > 0}
		
			<div id="MainMatrix" class="topMarginHomepage MainMatrix" style="width:98%;"> {* crmv@30014 crmv@97209 *}
			{foreach item=BLOCK from=$VIEW.blocks}
				{include file="ModuleHome/Block.tpl"}
			{/foreach}
			</div>
		
		{elseif $VIEW.reportid > 0}
			{assign var="REPORTID" value=$VIEW.reportid}
			{Reports::saveReportAndRun($REPORTID)} {* crmv@181170 *}
		{elseif $VIEW.cvid > 0}
			{include file=$LISTVIEWTPL}

		{else}
			<div style="text-align:center;padding:20px">
			<p>{$LBL_NO_HOME_BLOCKS}</p>
			</div>
		{/if}
	
	{/foreach}
	
</div>

<script type="text/javascript">
	ModuleHome.initialize('MainMatrix');
	
	{if $EDITMODE}
	jQuery(document).ready(function() {ldelim}
		ModuleHome.enterEditMode();
	{rdelim});
	{/if}
	
	(function() {ldelim}
		var blocks = {$BLOCKIDS|@json_encode};
		ModuleHome.loadBlocks('{$MODHOMEID}', blocks);
	{rdelim})();
</script>