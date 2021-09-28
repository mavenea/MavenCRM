{*
/*************************************
 * SPDX-FileCopyrightText: 2009-2020 Vtenext S.r.l. <info@vtenext.com> 
  * SPDX-License-Identifier: AGPL-3.0-only  
 ************************************/
*}
{* crmv@OPER6288 crmv@102334 *}
{if $smarty.request.ajax neq ''}
&#&#&#{$ERROR}&#&#&#
{/if}
{if $KANBAN_NOT_AVAILABLE}
	{$APP.LBL_KANBAN_NOT_AVAILABLE}
{else}

	{* crmv@168361 *}
	{*@nileio the whole custom links solution changed / now embedded within the view list menu - we dont need this
	removed the custom links table from there. it was a hack in the main product for some reason !
	*}


	{* crmv@168361e *}
	
	{* crmv@139896 *}
	<table border="0" cellspacing="0" cellpadding="0" width="100%" class="small" align="center">
		<tr valign="top" id="kanban_grid_h" class="kanbanGridHeader">
			{foreach name=kanban_foreach item=KANBAN_COL from=$KANBAN_ARR}
				{math equation="x/y" x=100 y=$smarty.foreach.kanban_foreach.total+1 format="%d" assign=width} {* crmv@181170 *}
				<td width="{$width}%" style="min-width:100px;"><div class="dvInnerHeader"><div class="dvInnerHeaderTitle">{$KANBAN_COL.label|getTranslatedString}</div></div></td> {* crmv@181450 *}
			{/foreach}
			<td width="{$width}%" style="min-width:250px; display:none" id="previewContainer_Summary_h"></td>
		</tr>
		<tr valign="top">
			{foreach name=kanban_foreach key=KANBAN_ID item=KANBAN_COL from=$KANBAN_ARR}
				<td>
					<ul id="{$KANBAN_ID}" lastpageapppended="{$LAST_PAGE_APPENDED}" class="kanbanSortableList" style="list-style:none; margin:0px;">
						{include file='KanbanColumn.tpl'}
					</ul>
				</td>
			{/foreach}
			<td id="previewContainer_Summary" style="display:none"><div id="previewContainer_Summary_scroll"></div></td>
		</tr>
	</table>
	{* crmv@139896e *}
{/if}

<script type="text/javascript" id="init_kanban_script">
{if $smarty.request.ajax eq 'true'}
	KanbanView.init('{$MODULE}','{$VIEWID}');
{else}
	jQuery(document).ready(function(){ldelim}
		KanbanView.init('{$MODULE}','{$VIEWID}');
	{rdelim});
{/if}

// crmv@187842
(function() {ldelim}
	var mainContainer = jQuery('body').get(0);
	var wrapperHeight = parseInt(visibleHeight(mainContainer));

	var buttonList = jQuery('#Buttons_List_HomeMod').get(0);
	var buttonListHeight = parseInt(visibleHeight(buttonList));
	
	jQuery('#ModuleHomeMatrix').css('min-height', (wrapperHeight-buttonListHeight-33) + 'px');
{rdelim})();
// crmv@187842e
</script>