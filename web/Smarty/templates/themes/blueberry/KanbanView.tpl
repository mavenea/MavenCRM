{*
/*************************************
 * SPDX-FileCopyrightText: 2009-2020 Vtenext S.r.l. <info@vtenext.com> 
  * SPDX-License-Identifier: AGPL-3.0-only  
 ************************************/
*}
{* crmv@OPER6288 crmv@102334 *}

<script language="JavaScript" type="text/javascript" src="{"modules/`$MODULE`/`$MODULE`.js"|resourcever}"></script>
<script language="JavaScript" type="text/javascript" src="{"include/js/dtlviewajax.js"|resourcever}"></script>
<script language="JavaScript" type="text/javascript" src="{"include/js/ListView.js"|resourcever}"></script>
<script language="JavaScript" type="text/javascript" src="{"include/js/KanbanView.js"|resourcever}"></script>
<script language="javascript" type="text/javascript" src="include/js/jquery_plugins/slimscroll/jquery.slimscroll.min.js"></script>

{include file='Buttons_List.tpl'}

<div class="container-fluid">
	<div class="row">
		<div class="col-sm-12">
			<div id="Buttons_List_Kanban">
				<table id="bl3" border=0 cellspacing=0 cellpadding=2 width=100% class="small">
					<tr height="34">
						<td align="left" style="padding-right:5px;">
							<!-- Filters -->
							<table border=0 cellspacing=0 cellpadding=0 class="small"><tr>
									<td>
										{if $HIDE_CUSTOM_LINKS neq '1'}
											{capture name="CUSTOMVIEW_OPTIONS"}
												{foreach from=$ALL_VIEWS_OPTIONS item=$viewoption}
													{capture name="Option_CUSTOM_LINKS"}
														{if $viewoption.value neq ''}
															<ul style='margin-left: 0;' id='customLinks'>
																{if $viewoption.optionAll eq '1'}
																	<li>
																		{* @nileio changed to use a div instead of a . a element does not behave correctly with bootstrap-select *}
																		<div class='customview_duplicate' role='button'
																			 data-action='index.php?module={$MODULE}&action=CustomView&duplicate=true&record={$viewoption.value}&parenttab={$CATEGORY}'>
																			<i class='vteicon md-sm pull-left'
																			   style='line-height: 2; padding-right: 10px'>content_copy</i><span
																					style='vertical-align: top;color: #000;'>{$APP.LNK_CV_DUPLICATE}</span>
																		</div>
																	</li>
																{else}
																	{if $viewoption.canedit eq 'yes'}
																		<li>
																			<div class='customview_edit' role='button'
																				 data-action='index.php?module={$MODULE}&action=CustomView&record={$viewoption.value}&parenttab={$CATEGORY}'>
																				<i class='vteicon md-sm pull-left'
																				   style='line-height: 2; padding-right: 10px'>edit</i><span
																						style='vertical-align: top;color: #000;'>{$APP.LNK_CV_EDIT}</span>
																			</div>
																		</li>
																	{/if}

																	<li>
																		<div class='customview_duplicate' role='button'
																			 data-action='index.php?module={$MODULE}&action=CustomView&duplicate=true&record={$viewoption.value}&parenttab={$CATEGORY}'>
																			<i class='vteicon md-sm pull-left'
																			   style='line-height: 2; padding-right: 10px'>content_copy</i><span
																					style='vertical-align: top;color: #000;'>{$APP.LNK_CV_DUPLICATE}</span>
																		</div>
																	</li>
																	{if $viewoption.candelete eq 'yes'}
																		<li>
																			<div class='customview_delete' role='button' data-action='index.php?module=CustomView&action=Delete&dmodule={$MODULE}&record={$viewoption.value}&parenttab={$CATEGORY}'>
																				<i class='vteicon md-sm pull-left'
																				   style='line-height: 2; padding-right: 10px'>delete</i><span
																						style='vertical-align: top;color: #000;'>{$APP.LNK_CV_DELETE}</span>
																			</div>
																		</li>
																	{/if}
																	{if $viewoption.statusdetails.ChangedStatus neq '' && $viewoption.statusdetails.Label neq ''}
																		<li>
																			<div class='customview_changestatus' role='button' id='customstatus_id' data-action={$viewoption.value},{$viewoption.statusdetails.Status},{$viewoption.statusdetails.ChangedStatus},{$MODULE},{$CATEGORY}>
																				<i class='vteicon md-sm pull-left'
																				   style='line-height: 2; padding-right: 10px'>{if $viewoption.statusdetails.Status eq '3'}thumb_down{else}thumb_up{/if}</i>
																				<span style='vertical-align: top;color: #000;'>{$viewoption.statusdetails.Label}</span>
																			</div>
																		</li>
																	{/if}
																{/if}
															</ul>
														{/if}
													{/capture}
													{$viewoption.text|replace:'CUSTOMLINKS_PLACEHOLDER':$smarty.capture.Option_CUSTOM_LINKS}
												{/foreach}
											{/capture}

										{/if}

									</td>
									{*@nileio <td>{$APP.LBL_VIEW}</td>*}
									<td style="padding-right:20px" nowrap>
										<a class="crmbutton only-icon" href="index.php?module={$MODULE}&amp;action=HomeView&amp;modhomeid={$MODHOMEID}&viewmode=ListView"><i class="vteicon" title="{'LBL_LIST'|getTranslatedString}" data-toggle="tooltip" data-placement="bottom">view_headline</i></a>
										<button type="button" class="crmbutton only-icon save" disabled><i class="vteicon" title="Kanban" data-toggle="tooltip" data-placement="bottom">view_column</i></button>
									</td>

									<td style="padding-left:5px;padding-right:5px">
										<div class="dvtCellInfo">
											<select name="viewname" id="viewname" class="detailedViewTextBox" onchange="showDefaultCustomView(this,'{$MODULE}','{$CATEGORY}','{$FOLDERID}','KanbanView','{$MODHOMEID}')">{$smarty.capture.CUSTOMVIEW_OPTIONS}
												{if $HIDE_CUSTOM_LINKS neq '1'}
												<option data-content="<div style='padding-right: 15px;'><i class='vteicon md-sm pull-left' style='vertical-align: bottom; padding-right: 4px; color: #078a0f;'>add</i><div id='customview_new' data-action='index.php?module={$MODULE}&action=CustomView&parenttab={$CATEGORY}' role='button' style='color: #078a0f;'>{$APP.LNK_CV_CREATEVIEW}</div></div>">
													{$APP.LNK_CV_CREATEVIEW}
												</option>
												{/if}
											</select> {* crmv@141557 *}
										</div>
									</td>

									{* crmv@7634 *}
									{if $OWNED_BY eq 0}
										<td style="padding-left:10px" nowrap>{$APP.LBL_ASSIGNED_TO}</td>
										<td style="padding-left:5px;"><div class="dvtCellInfo">{$LV_USER_PICKLIST}</div></td>
									{/if}
									{* crmv@7634e *}
								</tr></table>
						</td>
						<td align="right">
							{include file="Buttons_List_Contestual.tpl"}
						</td>

					</tr>
				</table>
			</div>

			<div id="KanbanViewContents" class="vte-card">
				{include file='KanbanGrid.tpl'}
			</div>
		</div>
	</div>
</div>