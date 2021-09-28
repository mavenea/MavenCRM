<ul id="List_Other_Buttons_Contextual" class="vteUlTable">
{*{if $MODULE neq 'Home' && $MODULE neq 'Messages' && $MODULE neq 'Reports' && ($REQUEST_ACTION eq 'index' || $REQUEST_ACTION eq 'ListView' || $REQUEST_ACTION eq 'index' || empty($REQUEST_ACTION)) && !$DISABLE_CAL_CONTESTUAL_BUTTON}*}
    <li>
        <div class="dropdown otherButton listview-dropdown mav-ripple">
            {* @nileio modified to only-icon *}
            <button type="button" class="" style="border: none;background-color: transparent;" data-toggle="dropdown">
                {* @nileio changed the icon and removed label *}
                <i class="vteicon valign-middle">more_vert</i>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-autoclose crmvDiv listview-menu-dropdown">
                {if !empty($BUTTONS)}
                    {foreach key=button_check item=button_label from=$BUTTONS}
                        {if $button_check eq 'del'}
                            <button type="button" class="crmbutton delete crmbutton-turbolift" onclick="return massDelete('{$MODULE}')">
                                <i class="vteicon pull-left md-text">delete</i>
                                <div>{$button_label}</div>
                            </button>
                        {elseif $button_check eq 's_mail'}
                            <button type="button" class="crmbutton edit crmbutton-turbolift" onclick="return eMail('{$MODULE}',this)">
                                <i class="vteicon pull-left md-text">email</i>
                                <div>{$button_label}</div>
                            </button>
                        {elseif $button_check eq 's_fax'}
                            <button type="button" class="crmbutton edit crmbutton-turbolift" onclick="return Fax('{$MODULE}',this)">
                                <i class="vteicon pull-left md-text">print</i>
                                <div>{$button_label}</div>
                            </button>
                        {elseif $button_check eq 's_sms'}
                            <button type="button" class="crmbutton edit crmbutton-turbolift" onclick="return Sms('{$MODULE}',this)">
                                <i class="vteicon pull-left md-text">sms</i>
                                <div>{$button_label}</div>
                            </button>
                        {elseif $button_check eq 's_cmail'}
                            <button type="button" class="crmbutton edit crmbutton-turbolift" onclick="return massMail('{$MODULE})"> {* crmv@192040 *}
                                <i class="vteicon pull-left md-text">mail</i>
                                <div>{$button_label}</div>
                            </button>
                        {elseif $button_check eq 'c_status'}
                            <button type="button" class="crmbutton edit crmbutton-turbolift" onclick="return change(this,'changestatus')">
                                <i class="vteicon pull-left md-text">update</i>
                                <div>{$button_label}</div>
                            </button>
                        {elseif $button_check eq 'mass_edit'}
                            <button type="button" class="crmbutton edit crmbutton-turbolift" onclick="return mass_edit(this, 'massedit', '{$MODULE}', '{$CATEGORY}')">
                                <i class="vteicon pull-left md-text">edit</i>
                                <div>{$button_label}</div>
                            </button>
                        {/if}
                    {/foreach}

                    <button type="button" class="crmbutton edit crmbutton-turbolift" onclick="selectAllIds();">
                        {if ($ALL_IDS eq 1)}
                            <i id="select_all_button_i" class="vteicon pull-left md-text">check_box_outline_blank</i>
                            <div id="select_all_button">{$APP.LBL_UNSELECT_ALL_IDS}</div>
                            <input type="hidden" id="select_all_button_top" value="{$APP.LBL_UNSELECT_ALL_IDS}" />
                        {else}
                            <i id="select_all_button_i" class="vteicon pull-left md-text">check_box</i>
                            <div id="select_all_button">{$APP.LBL_SELECT_ALL_IDS}</div>
                            <input type="hidden" id="select_all_button_top" value="{$APP.LBL_SELECT_ALL_IDS}" />
                        {/if}
                    </button>
                {/if}

                {* vtlib customization: Custom link buttons on the List view basic buttons *}
                {if $CUSTOM_LINKS && $CUSTOM_LINKS.LISTVIEWBASIC}
                    {foreach item=CUSTOMLINK from=$CUSTOM_LINKS.LISTVIEWBASIC}
                        {assign var="customlink_href" value=$CUSTOMLINK->linkurl}
                        {assign var="customlink_label" value=$CUSTOMLINK->linklabel}
                        {if $customlink_label eq ''}
                            {assign var="customlink_label" value=$customlink_href}
                        {else}
                            {* Pickup the translated label provided by the module *}
                            {assign var="customlink_label" value=$customlink_label|@getTranslatedString:$CUSTOMLINK->module()}
                        {/if}
                        <button type="button" class="crmbutton edit crmbutton-turbolift" onclick="{$customlink_href}">
                            <div>{$customlink_label}</div>
                        </button>
                    {/foreach}
                {/if}

                {* vtlib customization: Custom link buttons on the List view *}
                {if $CUSTOM_LINKS && !empty($CUSTOM_LINKS.LISTVIEW)}
                    {foreach item=CUSTOMLINK from=$CUSTOM_LINKS.LISTVIEW}
                        {assign var="customlink_href" value=$CUSTOMLINK->linkurl}
                        {assign var="customlink_label" value=$CUSTOMLINK->linklabel}
                        {if $customlink_label eq ''}
                            {assign var="customlink_label" value=$customlink_href}
                        {else}
                            {* Pickup the translated label provided by the module *}
                            {assign var="customlink_label" value=$customlink_label|@getTranslatedString:$CUSTOMLINK->module()}
                        {/if}
                        <button type="button" class="crmbutton edit crmbutton-turbolift" onclick="{$customlink_href}">
                            <div>{$customlink_label}</div>
                        </button>
                    {/foreach}
                {/if}

                {* vtlib customization: Hook to enable import/export button for custom modules. Added CUSTOM_MODULE *}
                {if $MODULE eq 'Assets' || $MODULE eq 'ServiceContracts' || $MODULE eq 'Vendors' || $MODULE eq 'HelpDesk' || $MODULE eq 'Contacts' || $MODULE eq 'Leads' || $MODULE eq 'Accounts' || $MODULE eq 'Potentials' || $MODULE eq 'Products' || $MODULE eq 'Services' || $MODULE eq 'Calendar' || $CUSTOM_MODULE eq 'true'}
                    {if $CHECK.Import eq 'yes' && $MODULE neq 'Calendar'}
                        <button type="button" class="crmbutton with-icon edit crmbutton-turbolift" onclick="location.href='index.php?module={$MODULE}&action=Import&step=1&return_module={$MODULE}&return_action=index&parenttab={$CATEGORY}'">
                            <i class="vteicon md-text">file_download</i>
                            <span>{$APP.LBL_IMPORT}</span>
                        </button>
                    {elseif  $CHECK.Import eq 'yes' && $MODULE eq 'Calendar' && $REQUEST_ACTION eq 'ListView'}
                        <button type="button" class="crmbutton with-icon edit crmbutton-turbolift" onclick="showFloatingDiv('CalImport', this);">
                            <i class="vteicon md-text">file_download</i>
                            <span>{$APP.LBL_IMPORT}</span>
                        </button>
                    {/if}
                    {if $CHECK.Export eq 'yes' && $MODULE neq 'Calendar'}
                        <button type="button" class="crmbutton with-icon edit crmbutton-turbolift" onclick="return selectedRecords('{$MODULE}','{$CATEGORY}')">
                            <i class="vteicon md-text">file_upload</i>
                            <span>{$APP.LBL_EXPORT}</span>
                        </button>
                    {elseif  $CHECK.Export eq 'yes' && $MODULE eq 'Calendar' && $REQUEST_ACTION eq 'ListView'}
                        <button type="button" class="crmbutton with-icon edit crmbutton-turbolift" onclick="showFloatingDiv('CalExport', this);">
                            <i class="vteicon md-text">file_upload</i>
                            <span>{$APP.LBL_EXPORT}</span>
                        </button>
                    {/if}
                {elseif $MODULE eq 'Documents' && $CHECK.Export eq 'yes' && $REQUEST_ACTION eq 'ListView'}
                    <button type="button" class="crmbutton with-icon edit crmbutton-turbolift" onclick="return selectedRecords('{$MODULE}','{$CATEGORY}')">
                        <i class="vteicon md-text">file_upload</i>
                        <span>{$APP.LBL_EXPORT}</span>
                    </button>
                {/if}
                {if $MODULE eq 'Contacts' || $MODULE eq 'Leads' || $MODULE eq 'Accounts'|| $MODULE eq 'Products'|| $MODULE eq 'Potentials'|| $MODULE eq 'HelpDesk'|| $MODULE eq 'Vendors' || $MODULE eq 'Services' || $CUSTOM_MODULE eq 'true'} {* crmv@206281 *}
                    {if $CHECK.DuplicatesHandling eq 'yes'}
                        <button type="button" class="crmbutton with-icon edit crmbutton-turbolift" onclick="MergeFieldsAjax()">
                            <i class="vteicon pull-left md-text">search</i>
                            <div>{$APP.LBL_FIND_DUPLICATES}</div>
                        </button>
                    {/if}
                {/if}
            </div>
        </div>
    </li>
{* {/if} *}
</ul>