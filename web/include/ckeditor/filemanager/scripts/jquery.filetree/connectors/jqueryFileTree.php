<?php
//
// jQuery File Tree PHP Connector
//
// Version 1.01
//
// Cory S.N. LaViska
// A Beautiful Site (http://abeautifulsite.net/)
// 24 March 2008
//
// History:
//
// 1.01 - updated to work with foreign characters in directory/file names (12 April 2008)
// 1.00 - released (24 March 2008)
//
// Output a list of files for jQuery File Tree
//
$_POST['dir'] = urldecode($_POST['dir']);
require_once('../../../../../../config.php');
global $root_directory;
if( file_exists($root_directory . $_POST['dir']) ) {
	$files = scandir($root_directory . $_POST['dir']);
	natcasesort($files);
	if( count($files) > 2 ) { /* The 2 accounts for . and .. */
		echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
		// All dirs
		foreach( $files as $file ) {
			if( file_exists($root_directory . $_POST['dir'] . $file) && $file != '.' && $file != '..' && $file[0] != '.' && is_dir($root_directory . $_POST['dir'] . $file) ) { // crmv@193430
				echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "/\">" . htmlentities($file) . "</a></li>";
			}
		}
		// All files
		foreach( $files as $file ) {
			if( file_exists($root_directory . $_POST['dir'] . $file) && $file != '.' && $file != '..' && $file[0] != '.' && !is_dir($root_directory . $_POST['dir'] . $file) ) { // crmv@193430
				$ext = preg_replace('/^.*\./', '', $file);
				echo "<li class=\"file ext_$ext\"><a href=\"#\" rel=\"" . htmlentities($_POST['dir'] . $file) . "\">" . htmlentities($file) . "</a></li>";
			}
		}
		echo "</ul>";	
	}
}

?>
