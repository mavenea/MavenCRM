<?php
/* Let's include some handy stuff */
include_once('../../../config.inc.php');
chdir($root_directory);
require_once('include/utils/utils.php');
include_once('vtlib/Vtecrm/Module.php');
$vtlib_Utils_Log = true;

/* Start the session, in order to allow SDK to update values stored in 
 * $_SESSION array. SDK uses the session to store values when updating 
 * to speed up queries.
 * If session has not started, you need to log out and login every time a 
 * SDK method is called. */
VteSession::start();

/* Retrieve instance of SDK module */
$SDKdir = 'modules/SDK/';
$moduleInstance = Vtecrm_Module::getInstance('SDK');
if (empty($moduleInstance)) {
    die('Modulo SDK non inizializzato');
}

/* Clears previous SDK values in the session array */
SDK::clearSessionValues();

/* create hooks for various examples */
$exdir = 'modules/SDK/examples/';
$module = "Accounts";
$accountsdir = $exdir . 'Accounts/';
//SDK::setHomeIframe(2, 'https://www.meteoblue.com/en/weather/widget/daily/melbourne_australia_2158177?geoloc=fixed&days=5&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&precipunit=MILLIMETER&coloured=coloured&pictoicon=0&pictoicon=1&maxtemperature=0&maxtemperature=1&mintemperature=0&mintemperature=1&windspeed=0&windspeed=1&windgust=0&winddirection=0&winddirection=1&uv=0&humidity=0&precipitation=0&precipitation=1&precipitationprobability=0&precipitationprobability=1&spot=0&spot=1&pressure=0&layout=light', 'Melbourne Weather', null, true);
SDK::setHomeGlobalIframe(2, 'https://www.meteoblue.com/en/weather/widget/daily/melbourne_australia_2158177?geoloc=fixed&days=5&tempunit=CELSIUS&windunit=KILOMETER_PER_HOUR&precipunit=MILLIMETER&coloured=coloured&pictoicon=0&pictoicon=1&maxtemperature=0&maxtemperature=1&mintemperature', 'Melbourne Weather');
