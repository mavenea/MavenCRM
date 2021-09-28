<?php

//@nileio
//now a self-contained script which should be involved
//by prepareInstallation.php
//this script must be run from the root directory of the application

error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE);
ini_set('display_errors','stderr');
//@nileio from index.php
if (version_compare(phpversion(), '7.0') < 0) { // crmv@180737
    require_once('errorpages/phpversionfail.php'); // crmv@138188
    die();
}
if (PHP_MAJOR_VERSION >= 7) {
    set_error_handler(function ($errno, $errstr) {
        return (strpos($errstr, 'Declaration of') === 0);
    }, E_WARNING);
}

//@nileio this should start a fake session - do we need it ?
ob_start();
session_start();


require_once('config.php');
global $enterprise_mode;

require_once('include/utils/utils.php');
require_once('include/install/language/en_us.lang.php');
require_once('include/install/resources/utils.php');
global $installationStrings, $vte_legacy_version;

//list($scriptPath)=get_included_files();
//$progressfilePath=dirname($scriptPath).'/_install_progress.txt';

// crmv@37463 check permissions
$oldinstall = glob('*install.php.txt');
if (is_array($oldinstall) && count($oldinstall) > 0) {
    logProgress('Fatal: old install detected. Cannot continue installation.');
    die('Unauthorized!');
}
// crmv@37463e

//@nileio restore installation_request
if (!file_exists('install/_install_request.inc.php')){
    logProgress('Fatal: old install detected. Cannot continue installation.');
    die('Unauthorized!');
}

require_once('install/_install_request.inc.php');
if (!empty($SAVED_SESSION)) {
    $_SESSION=$SAVED_SESSION;
} else{
    logProgress('Fatal: Cannot restore installation session.');
    die('cannot restore installation session.');
}

if(!isset($_SESSION['authentication_key'])) {
    logProgress('Fatal: session is invalid. authentication key does not exist.');
    die('session is invalid. authentication key does not exist.');
}

global $vtconfig;
if (!isset($dbconfig['db_hostname']) || $dbconfig['db_status']=='_DB_STAT_') {
    logProgress('Fatal: config.inc.php does not exist.');
    exit();
}

if (isset($_SESSION['installation_info']['selected_core_modules']))
    $selected_core_modules = explode(':',$_SESSION['installation_info']['selected_core_modules']); // @nileio

//---start---
logProgress(date('d-m-Y H:i:s'). ' - Installation Job started ✓');

include('adodb/adodb.inc.php');
require_once('vteversion.php'); // crmv@181168

global $table_prefix;
if (empty($table_prefix)) {
    $table_prefix = 'vte';
}

logProgress('Creating database tables ...');
include('CreateTables.php');
if (isset($_SESSION['installation_info']['db_populate']))
    $db_populate = $_SESSION['installation_info']['db_populate'];

if (isset($db_populate) && $db_populate ==true) {
    logProgress('Populating demo data ...');
    include('PopulateSeedData.php');
    logProgress('✓', true);
}
//@nileio-end
// Unset all of the session variables.
//$_SESSION = array();

// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
//if (isset($_COOKIE[session_name()])) {
//    setcookie(session_name(), '', time()-42000, '/');
//}

// Finally, destroy the session.
//session_destroy();
session_write_close();
logProgress(date('d-m-Y H:i:s').' - Installation Successfully Completed.');


function logProgress(string $msg, bool $updatePrevious=false) {
    Installation_Utils::logInstallationProgress($msg,$updatePrevious);
}
function shouldInstall(string $moduleName=''): bool
{
    global $selected_core_modules;

    if (isset($selected_core_modules)) {
        if (in_array($moduleName, $selected_core_modules, true)) $install = true;
        else $install = false;
    } else {
        // in case the var is not set for some reason - log the info!
        logProgress('Note: selected core modules is not set! All modules will be installed.');
        $install = true;
    }
    return $install;
}