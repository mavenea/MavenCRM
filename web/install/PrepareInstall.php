<?php
/*************************************
 * Author: @nileio
 * Description: starts the installation process in a separate process and returns an endpoint to the
 * calling client to check on installation progress.
 * License: AGPL-3.0-only
 ************************************/
/**
 * @author @nileio
 */

//@nileio we must differentiate between the http session and the $_SESSION
ob_start();
session_start();

$auth_key = $_REQUEST['auth_key'];

//if (empty($_SESSION['authentication_key']) && file_exists('install/_install_request.inc.php'))
//{
    //will only be here if user retry of the installation job - restore the session variables
    //@nileio currently retrying installation is disabled and cannot be done
    //it requires a number of manual steps including restoring files such as hash_version.txt and dropping database tables
    //not worth working on.
   // require_once ('install/_install_request.inc.php');
  //  $_SESSION=$SAVED_SESSION;
//}
if($_SESSION['authentication_key'] != $auth_key) {
    die($installationStrings['ERR_NOT_AUTHORIZED_TO_PERFORM_THE_OPERATION']);
}

if(isset($_REQUEST['selected_optional_modules'])) {
    $_SESSION['installation_info']['selected_optional_modules'] = $_REQUEST['selected_optional_modules'] ;
}
if(isset($_REQUEST['selected_beta_modules'])) {
    $_SESSION['installation_info']['selected_beta_modules'] = $_REQUEST['selected_beta_modules'] ;
}
$mandatory= implode(":",Installation_Utils::getMandatoryCoreModules());
if(isset($_REQUEST['selected_core_modules'])) {
    $_SESSION['installation_info']['selected_core_modules'] = $_REQUEST['selected_core_modules'].':'.$mandatory ;
}



session_write_close();

if ($fhandle=fopen('install/_install_request.inc.php','w')){
    fwrite($fhandle,'<?php'. PHP_EOL . '$SAVED_SESSION='. var_export($_SESSION,true) .';');
} else
    die('couldnot create install request file.');


if (!fopen('install/_install_progress.txt','w'))
    die('couldnot create install progress file.');


if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}


$execFile='install/RunInstall.php';
// the following command changes directory to the root directory returned by getcwd() - it will always return root directory - we could also use root_directory from available session info
// then runs php to install with nohup directing only the error output to the file - test if stderr & stdout are the same in this case direct everthing because
//>/dev/null 2>&1 - this will
$cmd = 'cd ' .getcwd(). ' && nohup php -q -f '.$execFile.' -- authkey='.$auth_key.' >install/install_errlog.txt 2>&1 &';
//execute the script using php cli
$nok = shell_exec($cmd);

?>
<tr><td class="cell-vcenter">
        <?php if ($nok==null): echo date('d/m/Y, H:i:s')?>
           - Installation Job Created successfully.
        <?php else: ?>
          -  Failed to run installation job. Make sure php cli is in the path and server can execute php commands.
        <?php endif; ?>
</td></tr>
