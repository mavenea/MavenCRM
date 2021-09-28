<?php
/*************************************
 * SPDX-FileCopyrightText: 2009-2020 Vtenext S.r.l. <info@vtenext.com> 
 * SPDX-License-Identifier: AGPL-3.0-only  
 ************************************/

$optionalModuleStrings = array(
	'CustomerPortal_description'=>'Management interface to control the behavior of Customer Portal Plugin',
	'FieldFormulas_description'=>'Setup rules for custom fields to update value on record save',
	'RecycleBin_description'=>'Module to manage deleted records, provides ability to restore or remove it completely',
	'Tooltip_description'=>'Configure tooltip to be shown for a field, which can be combination of other fields',
	'Webforms_description'=>'Server side support to allow building client webforms to capture the information easily',
	'SMSNotifier_description'=>'Sends sms messages to Accounts, contacts and Leads',
	'Assets_description'=>'Assets represent ownership of value that can be converted into cash',
	'ModComments_description' => 'Ability to add comments to any of the related modules',
	'Projects_description' => 'Adds the ability to manage Projects',
	'Dutch_description' => 'Dutch Language pack',
	'French_description' => 'French Language pack',
	'Hungarian_description' => 'Hungarian Language pack',
	'Spanish_description' => 'Spanish Language pack',
	'Deutsch_description' => 'German Language pack',
	'PTBrasil_description' => 'Brazilian Language pack',
	'Polish_description' => 'Polish Language pack',
	'Russian_description' => 'Russian Language pack',
);

$installationStrings = array(
	'LBL_VTE_CRM' => $enterprise_mode,
	'LBL_CONFIG_WIZARD' => 'Configuration Wizard',
	'LBL_WELCOME' => 'Welcome',
	'LBL_WELCOME_CONFIG_WIZARD' => 'Welcome to Configuration Wizard',
	'LBL_ABOUT_CONFIG_WIZARD' => 'This configuration wizard helps you install ',
	'LBL_ABOUT_VTE' => $enterprise_mode.' is now ready to install! <br><br>
	The most complete CRM system for businesses of all sizes. All the core CRM functionality includes sales force automation, 
	marketing email campaigns, helpdesk and customer portal, project management, 
	advanced calendaring and much more.<br> Built in PHP, supports MySQL, SQL Server and Oracle.<br>
	Plus it is the only Open Source solution with a BPM (Business Process Management) Engine, to drill the Silo Mentality and connect all business ares. This feature allows you to design and automate your own business processes.',
	'LBL_INSTALL' => 'Install',
	'LBL_MIGRATE' => 'Migrate',
	'ERR_RESTRICTED_FILE_ACCESS' => 'Sorry! Attempt to access restricted file',
	'LBL_INSTALLATION_CHECK' => 'Installation Check',
	'LBL_BACK' => 'Back',
	'LBL_NEXT' => 'Next',
    'LBL_STARTINSTALLATION' => 'Start',
	'LBL_AGREE' => 'I agree',
	'LBL_NOT_AGREE' => 'I do not agree',
	'LBL_SYSTEM_CONFIGURATION'=> 'System Configuration',
	'LBL_INSTALLATION_CHECK' => 'Installation Check',
	'LBL_PRE_INSTALLATION_CHECK' => 'Pre Installation Check',
	'LBL_CHECK_AGAIN' => 'Check Again',
	'LBL_CONFIRM_SETTINGS' => 'Confirm Settings',
	'LBL_CONFIRM_CONFIG_SETTINGS' => 'Confirm Configuration Settings',
	'LBL_CONFIG_FILE_CREATION' => 'Config File Creation',
    'LBL_MODULES' => 'Modules to Install',
	'LBL_OPTIONAL_MODULES' => 'Optional Modules',
    'LBL_CORE_MODULES' => 'Core Modules',
    'LBL_READY_TO_INSTALL' => 'Ready to Install',
    'LBL_SELECT_CORE_MODULES_TO_install' => 'Select Core Modules to Install',
	'LBL_SELECT_OPTIONAL_MODULES_TO_install' => 'Select Optional Modules to Install',
	'LBL_SELECT_OPTIONAL_MODULES_TO_update' => 'Select Optional Modules to Update',
	'LBL_SELECT_OPTIONAL_MODULES_TO_copy' => 'Select Optional Modules to Copy',
	// crmv@151405
	'LBL_SELECT_BETA_MODULES_TO_install' => 'Select BETA Modules to Install',
	'LBL_SELECT_BETA_MODULES_TO_update' => 'Select BETA Modules to Update',
	'LBL_SELECT_BETA_MODULES_TO_copy' => 'Select BETA Modules to Copy',
	// crmv@151405e
	'MSG_CONFIG_FILE_CREATED' => 'Configuration file (config.inc.php) was successfully created',
	'LBL_FINISH' => 'Finish',
	'LBL_CONFIG_COMPLETED' => 'Configuration Completed',
	'LBL_PHP_VERSION_GT_5' => 'PHP Version >= 7.0', // crmv@146653
    'LBL_PHPCLI' => 'PHP executable in PATH',
    'LBL_PHPCLI_NOTAVAILABLE' => 'PHP CLI is not available. Make sure PHP CLI is available in your system PATH. Without PHP CLI installation cannot continue.',
	'LBL_YES' => 'Yes',
	'LBL_NO' => 'No',
	'LBL_NOT_CONFIGURED' => 'Not Configured',
	'LBL_IMAP_SUPPORT' => 'IMAP Support',
	'LBL_ZLIB_SUPPORT' => 'Zlib Support',
	'LBL_GD_LIBRARY' => 'GD graphics library',
	'LBL_RECOMMENDED_PHP_SETTINGS' => 'Recommended PHP Settings',
	'LBL_DIRECTIVE' => 'Directive',
	'LBL_RECOMMENDED' => 'Recommended',
	'LBL_PHP_INI_VALUE' => 'PHP.ini value',
	'LBL_READ_WRITE_ACCESS' => 'Read/Write Access',
	'LBL_NOT_RECOMMENDED' => 'Not Recommended',
	'LBL_PHP_DIRECTIVES_HAVE_RECOMMENDED_VALUES' => 'Your PHP directives have the Recommended values',
	'MSG_PROVIDE_READ_WRITE_ACCESS_TO_PROCEED' => 'Provide Read/Write access to the files and directories listed to Proceed',
	'WARNING_PHP_DIRECTIVES_NOT_RECOMMENDED_STILL_WANT_TO_PROCEED' => 'Some of the PHP Settings do not meet the recommended values. This might affect some of the features of VTE CRM. Are you sure, you want to proceed?',
	'LBL_CHANGE' => 'Change',
	'LBL_DATABASE_INFORMATION' => 'Database Information',
	'LBL_CRM_CONFIGURATION' => 'CRM Configuration',
	'LBL_USER_CONFIGURATION' => 'User Configuration',
	'LBL_DATABASE_TYPE' => 'Database Type',
	'LBL_NO_DATABASE_SUPPORT' => 'No Database Support Detected',
	'LBL_HOST_NAME' => 'Host Name',
	'LBL_DATABASE_PORT' => 'Server listen Port',
	'LBL_USER_NAME' => 'User Name',
	'LBL_PASSWORD' => 'Password',
	'LBL_DATABASE_NAME' => 'Database Name',
	'LBL_CREATE_DATABASE' => 'Create Database',
	'LBL_DROP_IF_EXISTS' => 'Will drop if the database exists',
	'LBL_ROOT' => 'Root',
	'LBL_UTF8_SUPPORT' => 'UTF-8 Support',
	'LBL_URL' => 'URL',
	'LBL_CURRENCY_NAME' => 'Currency Name',
	'LBL_USERNAME' => 'Username',
	'LBL_EMAIL' => 'Email',
	'LBL_POPULATE_DEMO_DATA' => 'Populate database with demo data',
	'LBL_DATABASE' => 'Database',
	'LBL_SITE_URL' => 'Site Url',
	'LBL_PATH' => 'Path',
	'LBL_MISSING_REQUIRED_FIELDS' => 'Missing required fields',
	'ERR_ADMIN_EMAIL_INVALID' => 'The email id in the admin email field is invalid',
	'ERR_STANDARDUSER_EMAIL_INVALID' => 'The email id in the standard user email field is invalid',
	'WARNING_LOCALHOST_IN_SITE_URL' => 'Specify the exact host name instead of \"localhost\" in Site URL field, otherwise you will experience some issues while working with VTE plug-ins. Do you wish to Continue?',
	'LBL_DATABASE_CONFIGURATION' => 'Database Configuration',
	'LBL_ENABLED' => 'Enabled',
	'LBL_NOT_ENABLED' => 'Not Enabled',
	'LBL_SITE_CONFIGURATION' => 'Site Configuration',
	'LBL_DEFAULT_CHARSET' => 'Default Charset',
	'ERR_DATABASE_CONNECTION_FAILED' => 'Unable to connect to database Server',
	'ERR_INVALID_MYSQL_PARAMETERS' => 'Invalid SQL Connection Parameters specified',
	'MSG_LIST_REASONS' => 'This may be due to the following reasons',
	'MSG_DB_PARAMETERS_INVALID' => 'specified database user, password, hostname, database type, or port is invalid',
	'MSG_DB_USER_NOT_AUTHORIZED' => 'specified database user does not have access to connect to the database server from the host',
	'LBL_MORE_INFORMATION' => 'More Information',
	'ERR_INVALID_MYSQL_VERSION' => 'MySQL version is not supported, kindly connect to MySQL 4.1.x or above',
	'ERR_UNABLE_CREATE_DATABASE' => 'Unable to Create database',
	'MSG_DB_ROOT_USER_NOT_AUTHORIZED' => 'Message: Specified database Root User doesn\'t have permission to Create database or the Database name has special characters. Try changing the Database settings',
	'ERR_DB_NOT_FOUND' => 'This Database is not found.Try changing the Database settings',
	'LBL_SUCCESSFULLY_INSTALLED' => 'Successfully Installed',
	'LBL_DEMO_DATA_IN_PROGRESS' => 'Populating demo data is in progress',
	'LBL_PLEASE_WAIT' => 'Please Wait',
	'LBL_ALL_SET_TO_GO' => 'is all set to go!',
	'LBL_INSTALL_PHP_FILE_RENAMED' => 'Your install.php file has been renamed to',
	'LBL_MIGRATE_PHP_FILE_RENAMED' => 'Your migrate.php file has been renamed to',
	'LBL_INSTALL_DIRECTORY_RENAMED' => 'Your install folder too has been renamed to',
	'WARNING_RENAME_INSTALL_PHP_FILE' => 'We strongly suggest you to rename the install.php file',
    'LBL_INSTALL_REQUEST_REMOVED' => 'Successfully deleted install job request and log file',
    'WARNING_INSTALL_REQUEST_REMOVED' => 'Could not delete install_request.inc.php and/or _install_progress.txt. We strongly suggest you to delete those files.',
    'LBL_CRONSLEEP_STARTED' => 'NOTE: CronSleep.sh is successfully running.',
    'WARNING_CRONSLEEP_NOTSTARTED' =>'Could not run script CronSleep.sh . You need to manually start CronSleep.sh or use crontab to run cron jobs.',
	'WARNING_RENAME_MIGRATE_PHP_FILE' => 'We strongly suggest you to rename the migrate.php file',
	'WARNING_RENAME_INSTALL_DIRECTORY' => 'We strongly suggest you to rename the install directory',
	'LBL_LOGIN_USING_ADMIN' => 'Please log in using the "admin" user name and the password you entered in step 3/4',
	'LBL_SET_OUTGOING_EMAIL_SERVER' => 'Do not forget to set the outgoing emailserver, setup accessible from Other settings -&gt; Mail server',
	'LBL_RENAME_HTACCESS_FILE' => 'Rename htaccess.txt file to .htaccess to control public file access',
	'MSG_HTACCESS_DETAILS' => 'This .htaccess file will work if "<b>AllowOverride All</b>" is set on Apache server configuration file (httpd.conf) for the DocumentRoot or for the current VTE path.<br>
			   				If this AllowOverride is set as None ie., "<b>AllowOverride None</b>" then .htaccess file will not take into effect.<br>
			   				If AllowOverride is None then add the following configuration in the apache server configuration file (httpd.conf) <br>
			   				<b>&lt;Directory "%s"&gt;<br>Options -Indexes<br>&lt;/Directory&gt;</b><br>
			   				So that without .htaccess file we can restrict the directory listing',
	'LBL_YOU_ARE_IMPORTANT' => 'You are very important to us!',
	'LBL_PRIDE_BEING_ASSOCIATED' => 'We take pride in being associated with you',
	'LBL_TALK_TO_US_AT_FORUMS' => 'Talk to us at <a href="https://forum.vtenext.com" target="_blank">forums</a>',
	'LBL_DISCUSS_WITH_US_AT_BLOGS' => 'Discuss with us at <a href="http://www.vtenext.org" target="_blank">blogs</a>',
	'LBL_WE_AIM_TO_BE_BEST' => 'We aim to be - simply the best',
	'LBL_SPACE_FOR_YOU' => 'Come on over, there is space for you too!',	
	'LBL_NO_OPTIONAL_MODULES_FOUND' => 'No Optional Modules found',
	'LBL_NO_BETA_MODULES_FOUND' => 'No Beta Modules found', // crmv@151405
	'LBL_PREVIOUS_INSTALLATION_INFORMATION' => 'Previous Installation Information',
	'LBL_PREVIOUS_INSTALLATION_PATH' => 'Previous Installation Path',
	'LBL_PREVIOUS_INSTALLATION_VERSION' => 'Previous Installation Version',
	'LBL_IMPORTANT_NOTE' => 'Important Note',
	'MSG_TAKE_DB_BACKUP' => 'Make sure to take <b>backup (dump) of database</b> before proceeding further',
    'MSG_INSTALLATION_READY'=> 'Installation might take few minutes to complete. Once started, you may close the browser window and come back anytime to monitor the progress.',
	'QUESTION_MIGRATE_USING_NEW_DB' => 'Migrate using new database',
	'MSG_CREATE_DB_WITH_UTF8_SUPPORT' => 'Create the database first with UTF8 charset support',
	'LBL_EG' => 'eg',
	'MSG_COPY_DATA_FROM_OLD_DB' => '<b>Copy the data (dump)</b> from earlier database into this new one',
	'LBL_SELECT_PREVIOUS_INSTALLATION_VERSION' => 'Please Select Previous Installation Version',
	'LBL_SOURCE_CONFIGURATION' => 'Source Configuration',
	'LBL_OLD' => 'Old',
	'LBL_NEW' => 'New',
	'LBL_INNODB_ENGINE_CHECK' => 'InnoDB Engine Check',
	'LBL_FIXED' => 'Fixed',
	'LBL_NOT_FIXED' => 'Not Fixed',
	'LBL_NEW_INSTALLATION_PATH' => 'New Installation Path',
	'ERR_CANNOT_WRITE_CONFIG_FILE' => 'Failed to write to configuration file (config.inc.php ). Check permissions and restart installation',
	'ERR_DATABASE_NOT_FOUND' => 'ERR : This Database is not found. Provide the correct database name',
	'ERR_NO_CONFIG_FILE' => 'The Source you have specified doesn\'t have a config file. Please provide a proper Source',
	'ERR_NO_USER_PRIV_DIR' => 'The Source specified doesn\'t have a user privileges directory. Please provide a proper Source',
	'ERR_NO_STORAGE_DIR' => 'The Source specified doesn\'t have a Storage directory. Please provide a proper Source',
	'ERR_NO_SOURCE_DIR' => 'The Source specified doesn\'t seem to be existing. Please provide a proper Source',
	'ERR_NOT_VALID_USER' => 'Not a valid user. Please provide an Admin user, login details',
	'ERR_NOT_AUTHORIZED_TO_PERFORM_THE_OPERATION' => 'Not Authorized to perform this operation',
	'LBL_DATABASE_CHECK' => 'Database Check',
	'MSG_TABLES_IN_INNODB' => 'Required tables were detected to be in proper Engine type (InnoDB)',
	'LBL_RECOMMENDATION_FOR_PROPERLY_WORKING_CRM' => 'For proper functionality of VTE CRM, we recommend the following',
	'LBL_TABLES_SHOULD_BE_INNODB' => 'Tables to have InnoDB engine type',
	'QUESTION_WHAT_IS_INNODB' => 'What is InnoDB',
	'LBL_TABLES_CHARSET_TO_BE_UTF8' => 'To get complete UTF-8 support, tables should have default charset UTF8',
	'LBL_FIX_ENGINE_FOR_ALL_TABLES' => 'Fix Engine For All Tables',
	'LBL_TABLE' => 'Table',
	'LBL_TYPE' => 'Type',
	'LBL_CHARACTER_SET' => 'Character Set',
	'LBL_CORRECT_ENGINE_TYPE' => 'Correct Engine Type',
	'LBL_FIX_NOW' => 'Fix Now',
	'LBL_CLOSE' => 'Close',
	'ERR_TABLES_NOT_INNODB' => 'Your database table engine is not the recommended engine "Innodb"',
	'LBL_VIEW_REPORT' => 'View Report',
	'LBL_IMPORTANT' => 'Important',
	'LBL_DATABASE_BACKUP' => 'Database Backup',
	'LBL_DATABASE_COPY' => 'Database Copy',
	'LBL_DB_DUMP_DOWNLOAD' => 'DB Dump Download',
	'LBL_DB_COPY' => 'DB Copy',
	'QUESTION_NOT_TAKEN_BACKUP_YET' => 'Have not taken the database backup yet',
	'LBL_CLICK_FOR_DUMP_AND_SAVE' => '<b>&#171; Click</b> on the left icon to start the dump and <b>Save</b> the copy of output',
	'LBL_NOTE' => 'Note',
	'LBL_RECOMMENDED' => 'Recommended',
	'MSG_PROCESS_TAKES_LONGER_TIME_BASED_ON_DB_SIZE' => 'This process may take longer time depending on the database size',
	'QUESTION_MIGRATING_TO_NEW_DB' => 'Are you migrating to new database',
	'LBL_CLICK_FOR_NEW_DATABASE' => '<b>&#171; Click</b> on the left icon to proceed if you have not setup new database with earlier data',
	'MSG_USE_OTHER_TOOLS_FOR_DB_COPY' => 'Use tools to setup new database with data',
	'LBL_IF_DATABASE_EXISTS_WILL_RECREATE' => 'If database exists it will be recreated',
	'LBL_SHOULD_BE_PRIVILEGED_USER' => 'Should have privilege to CREATE DATABASE',
	'ERR_FAILED_TO_FIX_TABLE_TYPES' => 'Failed to fix the table types',
	'ERR_SPECIFY_NEW_DATABASE_NAME' => 'Please specify new database name',
	'ERR_SPECIFY_ROOT_USER_NAME' => 'Please specify root user name',
	'ERR_DATABASE_COPY_FAILED' => '<span class="redColor">Failed</span> to create database copy, please do it manually',
	'MSG_DATABASE_COPY_SUCCEDED' => 'Database copy was successfully created.<br />Click Next &#187; to proceed',
	'MSG_SUCCESSFULLY_FIXED_TABLE_TYPES' => 'Successfully changed tables to InnoDB engine',
	'LBL_SOURCE_VERSION_NOT_SET' => 'Source Version is not set. Please check vteversion.php and continue the Patch Process', // crmv@181168
	'LBL_GOING_TO_APPLY_DB_CHANGES' => 'Going to apply the Database Changes',
	'LBL_DATABASE_CHANGES' => 'Database changes',
	'LBL_STARTS' => 'Starts',
	'LBL_ENDS' => 'Ends',
	'LBL_SUCCESS' => 'SUCCESS',
	'LBL_FAILURE' => 'FAILURE',
	'LBL_OLD_VERSION_IS_AT' => 'Your older version is available at : ',
	'LBL_CURRENT_SOURCE_PATH_IS' => 'Your current source path is : ',
	'LBL_DATABASE_EXTENSION' =>'Database Extension',
	'LBL_DOCUMENTATION_TEXT' => 'Documentation including User Manual can be found',
	'LBL_USER_PASSWORD_CHANGE_NOTE' => 'password of all users will be reset to user name. Kindly notify users and change passwords',
	'LBL_PASSWORD_FIELD_CHANGE_FAILURE' => "changing user's password field failed",
	'LBL_OPENSSL_SUPPORT' => 'OpenSSL Support',
	'LBL_OPTIONAL_MORE_LANGUAGE_PACK' => 'Addition language packs are available at',
	'LBL_GETTING_STARTED' => 'Getting Started:',
	'LBL_GETTING_STARTED_TEXT' => 'You can start using your CRM now.',
	'LBL_YOUR_LOGIN_PAGE' => 'Your login page:',
	'LBL_ADD_USERS' => 'To add more users, please visit the Settings page.',
	'LBL_SETUP_BACKUP' => "Configure a backup: you need to archive the installaion folder and a dump of the database periodically, or in a virtualized environment make periodically snapshot of the virtual machine.",
	'LBL_RECOMMENDED_STEPS' => 'Recommended Steps:',
	'LBL_RECOMMENDED_STEPS_TEXT' => 'It is important that you complete the following steps',
	'LBL_DOCUMENTATION_TUTORIAL' => 'Documentation And Tutorial',
	'LBL_WELCOME_FEEDBACK' => 'We welcome your feedback',
	'LBL_TUTORIAL_TEXT' => 'Video Tutorials are available at',
	'LBL_DROP_A_MAIL' => 'Drop us an email to',
	'LBL_LOGIN_PAGE' => 'Your login page: ',
	'LBL_MOD_REWRITE_INSTRUCTIONS' => 'In order to enable <b>SmartOptimizer</b> or <b>RestApi</b>, you have to set <b>AllowOverride</b> to "all" in the apache configuration file (ex. /etc/apache2/sites-available/default)',	//crmv@24713m
	'LBL_CURL_LIBRARY' => 'cURL library',
	'LBL_CURL_LIBRARY_ERROR' => 'cURL library not installed. Without cURL library you can\'t activate the free version!',

	//crmv@28327
	'LBL_SAFETY_PASSWORD_ERROR'=>'Password Error',
	'LBL_NOT_SAFETY_PASSWORD'=>'The password doesn\'t satisfy the safety criteria: at least %s characters, no reference to User Name, Name or Last name.',
	'LBL_SAFETY_PASSWORD_CRITERIA'=>'The password must be satisfy the following safety criteria: at least %s characters, no reference to User Name, Name or Last name.',
	'LBL_CONFIRM_PASSWORD' => 'Confirm Password',
	'ERR_REENTER_PASSWORDS' => 'Password and Confirm Password do not match.',
	//crmv@28327e
	'LBL_IMAGICK_LIBRARY'=>'Imagick extension',	//crmv@91321
	'LBL_LICENSE_TERMS' => 'License Agreement',
	'LBL_CONFIGURATION_WIZARD' => 'Configuration Wizard',
	'LBL_SIMPLEXML_LIBRARY' => 'SimpleXML extension', // crmv@146653
	'LBL_MBSTRING_LIBRARY' => 'Multibyte String extension', // crmv@146653
);
?>