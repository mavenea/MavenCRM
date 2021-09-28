<?php
/*************************************
 * SPDX-FileCopyrightText: 2009-2020 Vtenext S.r.l. <info@vtenext.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 ************************************/
session_start();
$configFileUtils = new ConfigFile_Utils($_SESSION['config_file_info']);

if (!$configFileUtils->createConfigFile()) {
	die("<strong class='big'><font color='red'>{$installationStrings['ERR_CANNOT_WRITE_CONFIG_FILE']}</font></strong>");
}


require_once('include/utils/utils.php');  // Required - Especially to create adb instance in global scope.


$prev_file_name = 'SetInstallationConfig.php';
$file_name = 'InstallationReady.php';

$optionalModules = Installation_Utils::getInstallableOptionalModules();
$betaModules = Installation_Utils::getInstallableBetaModules(); // crmv@151405
$coreModules = Installation_Utils::getInstallableCoreModules();

$selectedCoreModuleNames = array();
$selectedOptionalModuleNames = array();
$selectedBetaModuleNames = array();


$title = $enterprise_mode. ' - ' . $installationStrings['LBL_CONFIG_WIZARD']. ' - ' . $installationStrings['LBL_MODULES'];
$sectionTitle = $installationStrings['LBL_MODULES'];

include_once "install/templates/overall/header.php";

?>
							
<div id="config" class="col-xs-12">
	<div id="config-inner" class="col-xs-12">
	
		<div class="spacer-20"></div>
        <strong class="big"><?php echo $installationStrings['MSG_CONFIG_FILE_CREATED']; ?>.</strong>
		<div class="spacer-30"></div>
        <div id="core_modules">
            <strong class="big"><?php echo $installationStrings['LBL_SELECT_CORE_MODULES_TO_install']; ?> :</strong>
            <div class="spacer-20"></div>
            <div>
                <table>
                    <tr>
                        <td>
                            <div class="checkbox">
                                <label for="coreModulesSelectAll">
                                    <input type="checkbox" id="coreModulesSelectAll" name="coreModulesSelectAll"
                                        <?php echo "checked"; ?>
                                    >
                                    <b>Select All</b>
                                </label>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-xs-12 nopadding" style="overflow: auto;max-height: 300px;">
                <table class="table">

                    <?php if (count($coreModules) > 0) {
                        foreach ($coreModules as $coremodule) {
                            if ($coremodule != null ) { /* crmv@167234 */
                                $selectedCoreModuleNames[] = $coremodule;
                                ?>
                                <tr>
                                    <td>
                                        <div id="coremodules" class="checkbox">
                                            <label for="<?php echo $coremodule; ?>">
                                                <input type="checkbox" id="<?php echo $coremodule; ?>" name="<?php echo $coremodule; ?>" value="<?php echo $coremodule; ?>"
                                                    <?php echo "checked"; ?>
                                                       onChange='coreModuleSelected("<?php echo $coremodule; ?>");' >
                                                <b><?php echo $coremodule; ?></b>
                                            </label>
                                        </div>
                                    </td>

                                </tr>
                                <?php
                            }
                        }
                    } ?>

                </table>

            </div>
        </div>

        <div id="optional_beta_modules">

		    <div class="col-xs-12 nopadding">
                <div class="spacer-30"></div>
			    <table class="table">
				<?php if (count($optionalModules) > 0) {
					foreach ($optionalModules as $option => $modules) {
						if ($modules != null && count($modules) > 0) { /* crmv@167234 */ ?>
			    			<tr>
				    			<td colspan="3">
				    				<strong><?php echo $installationStrings['LBL_SELECT_OPTIONAL_MODULES_TO_'.$option]; ?> :</strong>
				    			</td>
			    			</tr>
							<?php foreach ($modules as $moduleName => $moduleDetails) { 
								$moduleDescription = $moduleDetails['description'];
								$moduleSelected = $moduleDetails['selected'];
								$moduleEnabled = $moduleDetails['enabled'];
								if ($moduleSelected == true) $selectedOptionalModuleNames[] = $moduleName;
							?>
							<tr>
								<td>
									<div class="checkbox">
										<label for="<?php echo $moduleName; ?>">
        									<input type="checkbox" id="<?php echo $moduleName; ?>" name="<?php echo $moduleName; ?>" value="<?php echo $moduleName; ?>" 
        									<?php if ($moduleSelected == true) echo "checked"; ?> 
        									<?php if ($moduleEnabled == false || $option == 'update') echo "disabled"; ?>
        									onChange='optionalModuleSelected("<?php echo $moduleName; ?>");' />&nbsp;
											<b><?php echo $moduleName; ?></b>
										</label>
									</div>
								</td>
								<td class="cell-vcenter"><i><?php echo $moduleDescription; ?></i></td>
							</tr>
							<?php
							}
						}
					} 
				} else {
				?>
				<tr><td>
					<div class="fixedSmallHeight textCenter fontBold">
						<div style="padding-top:50px;width:100%;">
							<span class="genHeaderBig"><?php echo $installationStrings['LBL_NO_OPTIONAL_MODULES_FOUND']; ?> !</span>
						</div>
					</div>
				</td></tr>
			<?php } ?>
			</table>
			    <table class="table">
				<?php if (count($betaModules) > 0) {
					foreach ($betaModules as $option => $modules) {
						if ($modules != null && count($modules) > 0) { /* crmv@167234 */ ?>
			    			<tr>
				    			<td colspan="3">
				    				<strong><?php echo $installationStrings['LBL_SELECT_BETA_MODULES_TO_'.$option]; ?> :</strong>
				    			</td>
			    			</tr>
							<?php foreach ($modules as $moduleName => $moduleDetails) { 
								$moduleDescription = $moduleDetails['description'];
								$moduleSelected = $moduleDetails['selected'];
								$moduleEnabled = $moduleDetails['enabled'];
								if ($moduleSelected == true) $selectedBetaModuleNames[] = $moduleName;
							?>
							<tr>
								<td>
									<div class="checkbox">
										<label for="<?php echo $moduleName; ?>">
        									<input type="checkbox" id="<?php echo $moduleName; ?>" name="<?php echo $moduleName; ?>" value="<?php echo $moduleName; ?>" 
        									<?php if ($moduleSelected == true) echo "checked"; ?> 
        									<?php if ($moduleEnabled == false || $option == 'update') echo "disabled"; ?>
        									onChange='betaModuleSelected("<?php echo $moduleName; ?>");' />&nbsp;
											<b><?php echo $moduleName; ?></b>
										</label>
									</div>
								</td>
								<td class="cell-vcenter"><i><?php echo $moduleDescription; ?></i></td>
							</tr>
							<?php
							}
						}
					} 
				} else {
				?>
				<tr><td>
					<div class="fixedSmallHeight textCenter fontBold">
						<div style="padding-top:50px;width:100%;">
							<span class="genHeaderBig"><?php echo $installationStrings['LBL_NO_BETA_MODULES_FOUND']; ?> !</span>
						</div>
					</div>
				</td></tr>
			<?php } ?>
			</table>
		    </div>
        </div>
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
			<form action="install.php" method="post" name="form" id="form">
                <input type="hidden" name="file" value="<?php echo $file_name; ?>" />
				<input type="hidden" value="<?php echo implode(":",$selectedOptionalModuleNames)?>" id='selected_optional_modules' name='selected_optional_modules' />
				<input type="hidden" value="<?php echo implode(":",$selectedBetaModuleNames)?>" id='selected_beta_modules' name='selected_beta_modules' />
                <input type="hidden" value="<?php echo implode(":",$selectedCoreModuleNames)?>" id='selected_core_modules' name='selected_core_modules' />
				<input type="hidden" name="auth_key" value="<?php echo $_SESSION['authentication_key']; ?>" />
				<button type="button" class="crmbutton small edit btn-arrow-right" onClick="submit();"><?php echo $installationStrings['LBL_NEXT']; ?></button>
			</form>
		</div>
	</div>
</div>
							
<script type="text/javascript">
	var selected_optional_modules = '<?php echo implode(":",$selectedOptionalModuleNames)?>';
	var selected_beta_modules = '<?php echo implode(":",$selectedBetaModuleNames)?>';
    var selected_core_modules = '<?php echo implode(":",$selectedCoreModuleNames)?>';

	function moduleSelected(module, allvalues) {
		var moduleCheckbox = jQuery('#'+module);
		var selected = allvalues;
		
		if (moduleCheckbox.prop("checked")) {
			if (selected == '') {
				selected = selected+moduleCheckbox.val();
			} else {
				selected = selected+":"+moduleCheckbox.val();
			}
		} else {
			if (selected.indexOf(":"+module+":") > -1) {
				selected = selected.replace(":"+module+":", ":");
			} else if (selected.indexOf(module+":") > -1) {
				selected = selected.replace(module+":", "");
			} else if (selected.indexOf(":"+module) > -1) {
				selected = selected.replace(":"+module, "");
			} else {
				selected = selected.replace(module, "");
			}
		}
		
		return selected;
	}

	function optionalModuleSelected(module){
		selected_optional_modules = moduleSelected(module, selected_optional_modules);
		jQuery('#selected_optional_modules').val(selected_optional_modules);
	}

	function betaModuleSelected(module){
		selected_beta_modules = moduleSelected(module, selected_beta_modules);
		jQuery('#selected_beta_modules').val(selected_beta_modules);
	}
    function coreModuleSelected(module){
        selected_core_modules = moduleSelected(module, selected_core_modules);
        jQuery('#selected_core_modules').val(selected_core_modules);
    }
    jQuery("#coreModulesSelectAll").change(function(){
        var status = this.checked; // "select all" checked status
        jQuery('#coremodules input').each(function(){ //iterate all listed checkbox items
                this.checked = status; //change ".checkbox" checked status
                jQuery(this).trigger("change");
        });
    });
    jQuery('#coremodules input').change(function(){ //".checkbox" change
        //uncheck "select all", if one of the listed checkbox item is unchecked
        if(this.checked == false){ //if this item is unchecked
            jQuery("#coreModulesSelectAll")[0].checked = false; //change "select all" checked status to false
        }

        //check "select all" if all checkbox items are checked
        if (jQuery('#coremodules input:checked').length == jQuery('#coremodules input').length ){
            jQuery("#coreModulesSelectAll")[0].checked = true; //change "select all" checked status to true
        }
    });
</script>

<?php include_once "install/templates/overall/footer.php"; ?>
