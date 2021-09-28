{*
/*************************************
 * SPDX-FileCopyrightText: 2009-2020 Vtenext S.r.l. <info@vtenext.com> 
  * SPDX-License-Identifier: AGPL-3.0-only  
 ************************************/
*}

{* crmv@140887 *}

<ul id="Buttons_List_Contestual" class="vteUlTable">
	{* @nileio this gets populated when you click the Other button *}
	{if !empty($BUTTONS)}
		{foreach key=button_check item=button_label from=$BUTTONS}
			{if $button_check eq 'back'}
				<li>
					<a class="crmbutton with-icon save crmbutton-nav" href='index.php?module={$MODULE}&action=index'>
						{if $FOLDERID > 0}
							<i class="vteicon">undo</i>
							{$APP.LBL_GO_BACK}
						{else}
							<i class="vteicon">folder</i>
							{$APP.LBL_FOLDERS}
						{/if}
					</a>
				</li>
			{/if}
		{/foreach}
	{/if}
	{* moved this button to here from ListViewEntries.tpl *}
	{if $HIDE_CV_FOLLOW neq '1'  && $MODULE neq 'Calendar' && $MODULE neq 'Home' && ($REQUEST_ACTION eq 'index' || $REQUEST_ACTION eq 'ListView' || $REQUEST_ACTION eq 'KanbanView' || empty($REQUEST_ACTION)) && !$DISABLE_CAL_CONTESTUAL_BUTTON}
		{assign var=FOLLOWIMG value=$VIEWID|@getFollowImg:'customview'}
		{if preg_match('/_on/', $FOLLOWIMG)}
			{assign var=FOLLOWTITLE value='LBL_UNFOLLOW'|getTranslatedString:'ModNotifications'}
		{else}
			{assign var=FOLLOWTITLE value='LBL_FOLLOW'|getTranslatedString:'ModNotifications'}
		{/if}
		<li>
			<i data-toggle="tooltip" data-placement="bottom" id="followImgCV" title="{$FOLLOWTITLE}" class="vteicon md-link" onClick="ModNotificationsCommon.followCV();">{$VIEWID|@getFollowCls:'customview'}</i>
		</li>

	{/if}
	{* @nileio Other button this is where normal listview for Contacts/Leads/etc. entities other button is populated *}


	{if $MODULE eq 'Home' && $REQUEST_ACTION eq 'index'}
		<li>
			<div class="dropdown">
				<button type="button" class="crmbutton with-icon success crmbutton-nav" data-toggle="dropdown">
					<i class="vteicon">add</i>
					{'LBL_HOME_ADDWINDOW'|getTranslatedString:$MODULE}
				</button>
				<ul class="dropdown-menu dropdown-autoclose">
					<li>
						<a href="javascript:VTE.Homestuff.chooseType('Module');" id="addmodule">
							{$MOD.LBL_HOME_MODULE}
						</a>
					</li>
					{if $ALLOW_RSS eq "yes"}
						<li>
							<a href="javascript:VTE.Homestuff.chooseType('RSS');" id="addrss">
								{$MOD.LBL_HOME_RSS}
							</a>
						</li>
					{/if}
					{if $ALLOW_CHARTS eq "yes"}
						<li>
							<a href="javascript:VTE.Homestuff.chooseType('Charts');" id="addchart">
								{$APP.SINGLE_Charts}
							</a>
						</li>
					{/if}
					<li>
						<a href="javascript:VTE.Homestuff.chooseType('URL');" id="addURL">
							{$MOD.LBL_URL}
						</a>
					</li>
				</ul>
			</div>
		</li>
	{elseif $CHECK.EditView eq 'yes' || ($MODULE eq 'Projects' && ($ISPROJECTADMIN eq 'yes' || $ISPROJECTLEADER eq 'yes'))}
		{if $MODULE eq 'Messages'}
			<li>
				<button type="button" class="crmbutton with-icon success crmbutton-nav" onclick="OpenCompose('','create');">
					<i class="vteicon">add</i>
					{'LBL_COMPOSE'|getTranslatedString:'Messages'}
				</button>
			</li>
			<li>
				<button type="button" class="crmbutton with-icon info crmbutton-nav" onclick="fetch();">
					<i class="vteicon" title="{'LBL_FETCH'|getTranslatedString:'Messages'}" id="fetchImg">autorenew</i>
					{include file="LoadingIndicator.tpl" LIID="fetchImgLoader" LIEXTRASTYLE="display:none" LIOLDMODE=true}
					{'LBL_FETCH'|getTranslatedString:'Messages'}
				</button>
			</li>
		{elseif $MODULE neq 'Calendar' && $HIDE_BUTTON_CREATE neq true}
			<li>
				<a class="crmbutton with-icon success crmbutton-nav" href="index.php?module={$MODULE}&action=EditView&return_action=DetailView&parenttab={$CATEGORY}&folderid={$FOLDERID}">
					<i class="vteicon">add</i>
					{$APP.LBL_CREATE_BUTTON_LABEL}
				</a>
			</li>
		{/if}
	{/if}

	{if $MODULE eq 'Calendar' && $REQUEST_ACTION eq 'index' && !$DISABLE_CAL_CONTESTUAL_BUTTON}

		<li id="CalendarAddButton" style="display:none"></li>
	{/if}
	{if $MODULE eq 'Webforms' && $REQUEST_ACTION eq 'index'}
		<li>
			<a class="crmbutton with-icon success crmbutton-nav" href="index.php?module={$MODULE}&action=WebformsEditView&return_action=DetailView&parenttab={$CATEGORY}">
				<i class="vteicon add">add</i>
				{$APP.LBL_CREATE_BUTTON_LABEL} {$SINGLE_MOD|getTranslatedString:$MODULE}
			</a>
		</li>
	{/if}
	{if $MODULE eq 'Reports'}
		<li>
			<button type="button" class="crmbutton with-icon success crmbutton-nav" onclick="Reports.createNew('{$FOLDERID}')">
				<i class="vteicon">add</i>
				{'LBL_CREATE_REPORT'|@getTranslatedString:$MODULE}
			</button>
		</li>
	{/if}
	{if $MODULE eq 'Home' && $REQUEST_ACTION eq 'index'}
		<li>
			<button type="button" class="crmbutton with-icon save crmbutton-nav" onclick="VTE.Homestuff.showOptions('changeLayoutDiv');">
				<i class="vteicon">view_module</i>
				{'LBL_HOME_LAYOUT'|getTranslatedString:$MODULE}
			</button>
		</li>
		<li>
			<a class="crmbutton with-icon save crmbutton-nav" href="index.php?module=Users&action=EditView&record={$CURRENT_USER_ID}&scroll=home_page_components&return_module=Home&return_action=index">
				<i class="vteicon">tune</i>
				{$APP.LBL_PREFERENCES} {$MODULELABEL}
			</a>
		</li>
	{/if}
	{* crmv@197575 *}
	{if ($MODULE eq 'Campaigns' || $MODULE eq 'Newsletter') && ($REQUEST_ACTION eq 'index' || $REQUEST_ACTION eq 'ListView')}
		<li>
			<a class="crmbutton with-icon success crmbutton-nav" href="javascript:openNewsletterWizard('$MODULE$', '');">
				<i class="vteicon2 fa-magic no-hover"></i> {'Newsletter'|getTranslatedString}
			</a>
		</li>
	{/if}
	{* crmv@197575e *}
	{if $MODULE eq 'Calendar' && $REQUEST_ACTION eq 'index' && !$DISABLE_CAL_CONTESTUAL_BUTTON}
		{assign var=scroll value="LBL_CALENDAR_CONFIGURATION"|getTranslatedString:"Users"}
		{assign var=scroll value=$scroll|replace:' ':'_'}
		{if 'Geolocalization'|vtlib_isModuleActive} {* crmv@186646 *}
			<li id="geoCalendarContainer">
				<button type="button" class="crmbutton with-icon save crmbutton-nav" onclick="window.wdCalendar.GeoCalendar();">
					<i class="vteicon">location_on</i>
					{'Geolocalization'|getTranslatedString:'Geolocalization'}
				</button>
			</li>
		{/if}
		{* crmv@194723 *}
		<li id="loadRolesModalContainer" style="display:none;">
			<button type="button" id="loadRolesModal" class="crmbutton with-icon save crmbutton-nav" onclick="window.wdCalendar.CalendarResources.onLoadRolesModal();">
				<i class="vteicon">people</i>
				{'LBL_SELECT_RESOURCES'|getTranslatedString:'Calendar'}
			</button>
		</li>
		{* crmv@194723e *}
		{* crmv@158543 *}
		<li>
			{if $IS_ADMIN == 1}
				<div class="dropdown">
					<button type="button" class="crmbutton only-icon save crmbutton-nav" data-toggle="dropdown">
						<i class="vteicon" data-toggle="tooltip" data-placement="bottom" title="{$APP.LBL_SETTINGS} {$MODULELABEL}">settings_applications</i>
					</button>
					<ul class="dropdown-menu dropdown-autoclose">
						<li>
							<a href="index.php?module=Users&action=EditView&record={$CURRENT_USER_ID}&scroll={$scroll}&return_module=Calendar&return_action=index">{$APP.LBL_SETTINGS} {$MODULELABEL}</a>
						</li>
						<li>
							<a href="index.php?module=Settings&amp;action=ModuleManager&amp;module_settings=true&amp;formodule={$MODULE}&amp;parenttab=Settings">{$APP.LBL_ADVANCED}</a>
						</li>
					</ul>
				</div>
			{else}
				<a class="crmbutton only-icon save crmbutton-nav" href="index.php?module=Users&action=EditView&record={$CURRENT_USER_ID}&scroll={$scroll}&return_module=Calendar&return_action=index">
					<i class="vteicon" data-toggle="tooltip" data-placement="bottom" title="{$APP.LBL_SETTINGS} {$MODULELABEL}">settings</i>
				</a>
			{/if}
		</li>
		{* crmv@158543e *}
	{/if}
	
	{* Contestual buttons *}
	
	{if $REQUEST_ACTION neq 'UnifiedSearch' && !$DISABLE_CAL_CONTESTUAL_BUTTON}
	
		{include file="Buttons/SDKButtons.tpl"}
	
	{/if}

	
	{if $CAN_ADD_HOME_BLOCKS || $CAN_ADD_HOME_VIEWS || $CAN_DELETE_HOME_VIEWS || $CAN_TOGGLE_EDITMODE || $CAN_EDIT_COUNTS} {* crmv@160778 *} {* crmv@173746 *}
		{assign var="CAN_EDIT_HOMEVIEW" value="yes"}
	{else}
		{assign var="CAN_EDIT_HOMEVIEW" value="no"}
	{/if}
	{* @nileio disabling this altogether .. the edit mode and settings tab. TODO: test with vanila both admin/non admin behavior with different modules/calandar
	{if $CHECK.moduleSettings eq 'yes' && ($IS_ADMIN == 1 || $CAN_EDIT_HOMEVIEW == 'yes') && !$DISABLE_CAL_CONTESTUAL_BUTTON} {* crmv@160778 *}
       	{* @nileio hide the view settings dropdown
		<li class="dropdown" id="moduleSettingsTd">
			{if $IS_ADMIN eq 1}
				<button type="button" class="crmbutton only-icon save crmbutton-nav" data-toggle="dropdown">
					<i class="vteicon" data-toggle="tooltip" data-placement="bottom" title="{"LBL_CONFIGURATION"|getTranslatedString:"Settings"}">settings_applications</i>
				</button>
			{else}
				<button type="button" class="crmbutton only-icon save crmbutton-nav" onclick="ModuleHome.toggleEditMode()">
					<i class="vteicon" data-toggle="tooltip" data-placement="bottom" title="{$APP.LBL_CONFIG_PAGE}">settings_applications</i>
				</button>
			{/if}
			<ul class="dropdown-menu">
				<li><a href="javascript:;" onclick="ModuleHome.toggleEditMode()">{$APP.LBL_CONFIG_PAGE}</a></li>
				{if $IS_ADMIN eq 1}
					<li><a href="index.php?module=Settings&amp;action=ModuleManager&amp;module_settings=true&amp;formodule={$MODULE}&amp;parenttab=Settings">{$APP.LBL_ADVANCED}</a></li>
				{/if}
			</ul>
       	</li>
       	<li id="moduleSettingsResetTd" style="display:none">
			<button type="button" class="crmbutton with-icon save crmbutton-nav" href="javascript:;" onclick="ModuleHome.toggleEditMode()">
				<i class="vteicon">settings_applications</i>
				{$APP.LBL_DONE_BUTTON_TITLE}
			</button>
		</li>
	{/if}
	*}
</ul>