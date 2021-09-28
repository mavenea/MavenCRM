<?php
/*************************************
 * SPDX-FileCopyrightText: 2009-2020 Vtenext S.r.l. <info@vtenext.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 ************************************/

global $selected_optional_modules, $selected_beta_modules;

if (isset($_SESSION['installation_info']['admin_email'])) $admin_email = $_SESSION['installation_info']['admin_email'];
if (isset($_SESSION['installation_info']['admin_password'])) $admin_password = $_SESSION['installation_info']['admin_password'];
if (isset($_SESSION['installation_info']['currency_name'])) $currency_name = $_SESSION['installation_info']['currency_name'];
if (isset($_SESSION['installation_info']['currency_code'])) $currency_code = $_SESSION['installation_info']['currency_code'];
if (isset($_SESSION['installation_info']['currency_symbol'])) $currency_symbol = $_SESSION['installation_info']['currency_symbol'];
if (isset($_SESSION['installation_info']['selected_optional_modules'])) $selected_optional_modules = $_SESSION['installation_info']['selected_optional_modules'];
if (isset($_SESSION['installation_info']['selected_beta_modules'])) $selected_beta_modules = $_SESSION['installation_info']['selected_beta_modules']; // crmv@151405


//@nileio -- start installation / creation of tables
require_once('install/CreateTables.inc.php');
logProgress('✓', true);
require_once('vtlib/Vtecrm/Package.php');
global $metaLogs;
if ($metaLogs) $metaLogs->disable();

if (!empty($current_user)) {
    $userid= $current_user->id;
}
if (!isset($userid)) die('fatal error: current user is not set.');

// Install mandatory modules (already pre-installed)
// The order is important, to keep the compatibility
$mandatoryModules = array(
    'SLA', 'ModNotifications', 'Mobile', 'Ddt', 'FieldFormulas',
    'Touch', 'Sms', 'Services', 'Morphsuit', 'Timecards',
    'Assets', 'Charts', 'WSAPP', 'PDFMaker', 'Myfiles',
    'ProjectMilestone', 'ProjectTask', 'JobOrder', 'ProjectPlan', // crmv@194733
    'Conditionals', 'M', 'ModComments', 'Webforms',
    'MyNotes', 'PBXManager', 'Visitreport',
    'ServiceContracts', 'Targets', 'Newsletter',
    'Transitions', 'Fax', 'Geolocalization',
    'ChangeLog',
);
foreach ($mandatoryModules as $m) {
    if (shouldInstall($m)===true) {
        logProgress('Installing module ' . $m . ' ...');
        $package = new Vtecrm_Package();
        $package->importByManifest($m);
        logProgress('✓', true);
    }
}
//@nileio  - start
// Install Vtlib Compliant Modules
//@nileio actually this does not install any modules - no mandatory folder exist under packages - we can ignore this call
Common_Install_Wizard_Utils::installMandatoryModules();

if ($selected_optional_modules !=='') {
    logProgress('Installing Selected Optional Modules ...' . implode(',', explode(':', $selected_optional_modules)));
    Installation_Utils::installOptionalModules($selected_optional_modules);
    logProgress('✓', true);
}

if ($selected_beta_modules !=='') {
    logProgress('Installing Beta Modules ...' . implode(',', explode(':', $selected_beta_modules)));
    Installation_Utils::installBetaModules($selected_beta_modules); // crmv@151405
    logProgress('✓', true);
}

// crmv@97862 - hide the emails module
$emailsInst = Vtecrm_Module::getInstance('Emails');
if ($emailsInst) $emailsInst->hide(array('hide_report' => 1));
// crmv@97862e

//crmv@29079
if (shouldInstall('ModComments') ===true) {
    $modCommentsFocus = CRMEntity::getInstance('ModComments');
    $modCommentsFocus->addWidgetToAll();
}
//crmv@29079e

//crmv@29463

$leadsFocus = CRMEntity::getInstance('Leads');
$leadsFocus->updateConvertLead();

//crmv@29463e

//crmv@3083m
if (shouldInstall('MyNotes') ===true) {
    $myNotesFocus = CRMentity::getInstance('MyNotes');
    $myNotesFocus->addWidgetToAll();
}
//crmv@3083me

logProgress('Preparing to install Other Modules.');
//crmv@2963m
// install modules by folder and manifest (put xml file in modules/MODULENAME/manifest.xml)
$othermodules_to_install = array('Messages', 'ProductLines', 'Processes', 'Employees', 'VteSync', 'ConfProducts'); // crmv@44323 crmv@83576 crmv@161021 crmv@176547 crmv@198024
foreach ($othermodules_to_install as $m) {
    if (shouldInstall($m) ===true) {
        //@nileio disable Employees module for now
        logProgress('Installing module ' . $m . ' ...');
        $package = new Vtecrm_Package();
        $package->importByManifest($m);
        logProgress('✓', true);
    }

}
//crmv@2963me

// install not entity modules
$notEntityModules = array('Popup', 'Area');
foreach ($notEntityModules as $module) {
    if (shouldInstall($module) ===true) {
        logProgress('Installing module ' . $module . ' ...');
        $Mod = Vtecrm_Module::getInstance($module);
        if (empty($Mod)) {
            $Mod = new Vtecrm_Module();
            $Mod->name = $module;
            $Mod->isentitytype = false;
            $Mod->save();
            $Mod->hide(array('hide_module_manager' => 1, 'hide_profile' => 1));
            $adb->pquery("UPDATE {$table_prefix}_tab SET customized=0 WHERE name=?", array($module));

            require_once("modules/$module/$module.php");
            $instance = new $module();
            if ($instance) {
                $instance->vtlib_handler($module, Vtecrm_Module::EVENT_MODULE_POSTINSTALL);
            }
        }
        logProgress('✓', true);
    }
}
//--@nileio in here we can use a configuration about the modules not to install

//crmv@3085m
require_once('include/utils/DetailViewWidgets.php');
$focusDetailViewWidgets = new DetailViewWidgets();
$widgets = array('AccountsHierarchy');
foreach ($widgets as $widget) {
    $widgetObj = $focusDetailViewWidgets->getWidget($widget);
    $widgetObj->install();
}
$focusDetailViewWidgets->reorder();
//crmv@3085me
if (shouldInstall('CalendarTracking') ===true) {
        logProgress('Installing CalendarTracking module ...');
        include('modules/SDK/src/CalendarTracking/install.php');    // crmv@62394 - install CalendarTracking
        logProgress('✓', true);
}
//crmv@94084
require_once('include/utils/VTEProperties.php');
$VTEProperties = VTEProperties::getInstance();
$VTEProperties->initDefaultProperties(false); // crmv@148789
$VTEProperties->rebuildCache();
//crmv@94084e

//crmv@102334
require_once('include/utils/ModuleHomeView.php');
logProgress('Installing HomeView module ...');
$MHW = ModuleHomeView::install();
logProgress('✓', true);
//crmv@102334e

// crmv@104782 - install MailScanner
if (shouldInstall('MailScanner') ===true) {
    logProgress('Installing MailScanner module ...');
    require('modules/Settings/MailScanner/Install.php');
    logProgress('✓', true);
// crmv@104782e
}
//crmv@150751
require_once('include/utils/UserInfoUtil.php');
$UIUtils = UserInfoUtils::getInstance();
$UIUtils->initSystemVersions();
//crmv@150751e

// crmv@168297
logProgress('Installing SOAP WebServices ...');
require_once('soap/SOAPWebservices.php');
SOAPWebservices::installWS();
logProgress('✓', true);
// crmv@168297e

//crmv@161554

if (shouldInstall('PrivacyPolicy') ===true) {
    require_once('include/utils/PrivacyPolicyUtils.php');
    logProgress('Installing PrivacyPolicy Utils ...');
    $PPU = PrivacyPolicyUtils::getInstance();
    $PPU->install();
    logProgress('✓', true);
}

if (shouldInstall('GDPR') ===true) {
    require_once('include/utils/GDPRWS/GDPRWS.php');
    logProgress('Installing GDPR module ...');
    $GDPRWS = GDPRWS::getInstance();
    $GDPRWS->install();
    logProgress('✓', true);
//crmv@161554e
}
// crmv@144893
// create the resources cache, inserting at least one resource
// so the other ones will be appended when requested
logProgress('Updating Resources ...');
require_once('include/utils/ResourceVersion.php');
// force-enable the cache, to create the file
$cache = Cache::getInstance('cacheResources');
if ($cache) $cache->enable();
// now create the resource cache
$RV = ResourceVersion::getInstance();
$RV->enableCacheWrite();
$RV->createResource('include/js/general.js');
$RV->updateResources();
logProgress('✓', true);
// crmv@144893e

//---end of creating tables / installation