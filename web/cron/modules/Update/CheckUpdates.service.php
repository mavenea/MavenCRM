<?php
/*************************************
 * SPDX-FileCopyrightText: 2009-2020 Vtenext S.r.l. <info@vtenext.com> 
 * SPDX-License-Identifier: AGPL-3.0-only  
 ************************************/

/* crmv@181161 */

if (vtlib_isModuleActive('Update')) {
	$VP = VTEProperties::getInstance();
	
	//@nileio this property doesnt exist so I think this cron service doesnt work at all.
    //i think reason is to manage updates manually by running the shell script rather than use autoupdate
	$docheck = $VP->get('update.check_updates');
	if ($docheck == 1) {
		require_once('modules/Update/AutoUpdater.php');
		$class = new AutoUpdater();
		$class->statusHandler();
	}
}