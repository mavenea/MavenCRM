<?php

session_start();

//require_once('include/utils/utils.php');  // Required - Especially to create adb instance in global scope.


$prev_file_name = 'SelectModules.php';


$selectedCoreModuleNames = $_REQUEST['selected_core_modules'];
$selectedOptionalModuleNames = $_REQUEST['selected_optional_modules'];
$selectedBetaModuleNames = $_REQUEST['selected_beta_modules'];

$title = $enterprise_mode. ' - ' . $installationStrings['LBL_CONFIG_WIZARD']. ' - ' . $installationStrings['LBL_READY_TO_INSTALL'];
$sectionTitle = $installationStrings['LBL_READY_TO_INSTALL'];

include_once "install/templates/overall/header.php";
?>

<div id="config" class="col-xs-12">
    <div id="config-inner" class="col-xs-12">
        <div class="spacer-20"><strong class="big">Note: </strong><?php echo $installationStrings['MSG_INSTALLATION_READY']; ?></div>
        <div class="spacer-30"></div>
    </div>
</div>
<div id="nav-bar" class="col-xs-12 nopadding">
    <div id="nav-bar-inner" class="col-xs-12">
        <div class="col-xs-6 text-left">
            <form action="install.php" method="post" name="backform" id="backform">
                <input type="hidden" name="file" value="<?php echo $prev_file_name; ?>">
                <button class="crmbutton small edit btn-arrow-left"><?php echo $installationStrings['LBL_BACK']; ?></button>
            </form>
        </div>

        <div class="col-xs-6 text-right">
            <form action="install.php" method="post" name="finishform" id="finishform">
                <input type="hidden" value="<?php echo $selectedOptionalModuleNames?>" id='selected_optional_modules' name='selected_optional_modules' />
                <input type="hidden" value="<?php echo $selectedBetaModuleNames?>" id='selected_beta_modules' name='selected_beta_modules' />
                <input type="hidden" value="<?php echo $selectedCoreModuleNames?>" id='selected_core_modules' name='selected_core_modules' />
                <input type="hidden" name="file" value="InstallationComplete.php">
                <input type="hidden" name="auth_key" value="<?php echo $_SESSION['authentication_key']; ?>">
                <button type="button" class="crmbutton small edit btn-arrow-right" onClick="startInstall('<?php echo $_SESSION['authentication_key']; ?>');"><?php echo $installationStrings['LBL_STARTINSTALLATION']; ?></button>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery.ajax({
        url: '/install/_install_progress.txt',
        method: 'GET',
        statusCode: {
            200: function(htmldata) {
             installationJobMonitor();
            }
        }
    })
</script>
<?php include_once "install/templates/overall/footer.php"; ?>