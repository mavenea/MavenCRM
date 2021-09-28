<?php
/*************************************
 * SPDX-FileCopyrightText: 2009-2020 Vtenext S.r.l. <info@vtenext.com> 
 * SPDX-License-Identifier: AGPL-3.0-only  
 ************************************/
	
global $adb, $table_prefix;

$record = vtlib_purify($_REQUEST['record']);
$pricebook_id = vtlib_purify($_REQUEST['pricebook_id']);
$product_id = vtlib_purify($_REQUEST['product_id']);
$listprice = vtlib_purify($_REQUEST['list_price']);

$query = "update ".$table_prefix."_pricebookproductrel set listprice=? where pricebookid=? and productid=?";
$adb->pquery($query, array($listprice, $pricebook_id, $product_id)); 