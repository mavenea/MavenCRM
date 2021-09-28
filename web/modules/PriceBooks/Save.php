<?php
/*************************************
 * SPDX-FileCopyrightText: 2009-2020 Vtenext S.r.l. <info@vtenext.com> 
 * SPDX-License-Identifier: AGPL-3.0-only  
 ************************************/

require_once('modules/PriceBooks/PriceBooks.php');

global $currentModule;

$focus = CRMEntity::getInstance('PriceBooks');

//added to fix 4600
$search = vtlib_purify($_REQUEST['search_url']);

setObjectValuesFromRequest($focus);

if($_REQUEST['assigntype'] == 'U')  {
	$focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_user_id'];
} elseif($_REQUEST['assigntype'] == 'T') {
	$focus->column_fields['assigned_user_id'] = $_REQUEST['assigned_group_id'];
}
$focus->save("PriceBooks");
$return_id = $focus->id;

$parenttab = getParentTab();
if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = vtlib_purify($_REQUEST['return_module']);
else $return_module = "PriceBooks";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = vtlib_purify($_REQUEST['return_action']);
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = vtlib_purify($_REQUEST['return_id']);

//crmv@54375
if($_REQUEST['return2detail'] == 'yes') {
    $return_action = 'DetailView';
    $return_module = $currentModule;
	$return_id = $focus->id;
}
//crmv@54375e

$url = "index.php?action={$return_action}&module={$return_module}&parenttab={$parenttab}&record={$return_id}&start=".vtlib_purify($_REQUEST['pagenumber']).$search;

$from_module = vtlib_purify($_REQUEST['module']);
if (!empty($from_module)) $url .= "&from_module={$from_module}";

header("Location: {$url}");