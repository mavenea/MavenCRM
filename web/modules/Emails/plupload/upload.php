<?php
//crmv@22123
/**
 * upload.php
 *
 * Copyright 2009, Moxiecode Systems AB
 * Released under GPL License.
 *
 * License: http://www.plupload.com/license
 * Contributing: http://www.plupload.com/contributing
 */

//crmv@228766
if(!isset($root_directory)){
	require('../../../config.inc.php');
}
chdir($root_directory);
require_once('include/utils/utils.php');
VteSession::start();
if(!VteSession::hasKey('authenticated_user_id')) die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Not Authorized."}, "id" : "id"}');
// crmv@228766e

// Settings
$targetDir = 'storage/uploads_emails_'.str_replace('../', '', $_REQUEST['dir']);//crmv@2963m crmv@228766

// crmv@228766
$tempRootDir = rtrim($root_directory, "/");
// Create target dir
if (!file_exists($targetDir)){
	@mkdir($targetDir);
	checkIfPathIsInStorage($targetDir, $tempRootDir);

	@file_put_contents($targetDir."/index.html", "<html></html>\n"); // crmv@195947
} else {
	checkIfPathIsInStorage($targetDir, $tempRootDir);
}

function checkIfPathIsInStorage($path, $rootPath){
	if (strpos(dirname(realpath($path)), $rootPath.'/storage') !== 0){
		die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Bad dir."}, "id" : "id"}');
	}
}
// crmv@228766e

//crmv@81704
if ($_REQUEST['ckeditor'] == 'true'){
	$response_arr = Array(
		'uploaded'=>0,
		'fileName'=>'',
		'url'=>'',
		'error'=>Array('message'=>getTranslatedString('LBL_UPLOAD_FAILED')),
	);
	if(isset($_FILES['upload'])){
		// crmv@185799
		$allowed_ext = array('jpg', 'jpeg', 'png', 'gif', 'bmp', 'tif', 'tiff');
		$origExt = pathinfo($_FILES['upload']['name'],PATHINFO_EXTENSION);
		if (!in_array(strtolower($origExt), $allowed_ext)) {
			echo Zend_Json::encode($response_arr);
			exit;
		}
		// crmv@185799e
		$response_arr['fileName'] = $_FILES['upload']['name'];
		$response_arr['url'] = $targetDir."/".$response_arr['fileName'];
		if (is_file($response_arr['url'])){
			$filename = pathinfo($response_arr['url'],PATHINFO_FILENAME);
			$extension = pathinfo($response_arr['url'],PATHINFO_EXTENSION);
			$full_filename = pathinfo($response_arr['url'],PATHINFO_BASENAME);
			$cnt = 1;
			while(is_file($response_arr['url'])){
				$response_arr['fileName'] = $filename."({$cnt}).".$extension;
				$response_arr['url'] = $targetDir."/".$response_arr['fileName'];
				$cnt++;
			}
		}
		$res = @move_uploaded_file($_FILES['upload']['tmp_name'], $response_arr['url']);
		if ($res){
			$response_arr['uploaded'] = 1;
			unset($response_arr['error']);
		} else {
			// here we don't even try to save in the db, since we won't have a valid url to use then
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Unable to move temporary file."}, "id" : "id"}'); // crmv@205309
		}
	}
	echo Zend_Json::encode($response_arr);
	exit;

}
//crmv@81704 e

// HTTP headers for no cache etc
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//$cleanupTargetDir = false; // Remove old files
//$maxFileAge = 60 * 60; // Temp file age in seconds

// 5 minutes execution time
@set_time_limit(5 * 60);

// Uncomment this one to fake upload time
// usleep(5000);

// Get parameters
$chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
$chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

// crmv@228766
$ext = pathinfo($fileName, PATHINFO_EXTENSION);
if (is_array($upload_badext) && in_array($ext, $upload_badext)){
	die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "File type not supported."}, "id" : "id"}');
}
// crmv@228766e

// Clean the fileName for security reasons
$fileName = preg_replace('/[^\w\._]+/', '', $fileName);

// Make sure the fileName is unique but only if chunking is disabled
if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
	$ext = strrpos($fileName, '.');
	$fileName_a = substr($fileName, 0, $ext);
	$fileName_b = substr($fileName, $ext);

	$count = 1;
	while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
		$count++;

	$fileName = $fileName_a . '_' . $count . $fileName_b;
}

// Remove old temp files
/* this doesn't really work by now

if (is_dir($targetDir) && ($dir = opendir($targetDir))) {
	while (($file = readdir($dir)) !== false) {
		$filePath = $targetDir . DIRECTORY_SEPARATOR . $file;

		// Remove temp files if they are older than the max age
		if (preg_match('/\\.tmp$/', $file) && (filemtime($filePath) < time() - $maxFileAge))
			@unlink($filePath);
	}

	closedir($dir);
} else
	die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
*/

// Look for the content type header
if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
	$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

if (isset($_SERVER["CONTENT_TYPE"]))
	$contentType = $_SERVER["CONTENT_TYPE"];

// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
if (strpos($contentType, "multipart") !== false) {
	if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
		// Open temp file
		$out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
		if ($out) {
			// Read binary input stream and append it to temp file
			$in = fopen($_FILES['file']['tmp_name'], "rb");

			if ($in) {
				while ($buff = fread($in, 4096))
					fwrite($out, $buff);
				// crmv@205309
			} else {
				header("HTTP/1.0 500 Internal Server Error");
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			}
			// crmv@205309e
			fclose($in);
			fclose($out);
			@unlink($_FILES['file']['tmp_name']);
		} else {
			// crmv@205309
			// try to save in database
			global $adb, $table_prefix;
			$id = $adb->getUniqueId($table_prefix.'_crmentity');
			$FSDB = FileStorageDB::getInstance();
			$res = $FSDB->saveFile($_FILES['file']['tmp_name'], $targetDir . DIRECTORY_SEPARATOR . $fileName, $id);
			if (!$res) {
				header("HTTP/1.0 500 Internal Server Error");
				die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
			}
			// crmv@205309e
		}

		// crmv@205309
	} else {
		header("HTTP/1.0 500 Internal Server Error");
		die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
	}
	// crmv@205309e
} else {
	// Open temp file
	$out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
	if ($out) {
		// Read binary input stream and append it to temp file
		$in = fopen("php://input", "rb");

		if ($in) {
			while ($buff = fread($in, 4096))
				fwrite($out, $buff);
			// crmv@205309
		} else {
			header("HTTP/1.0 500 Internal Server Error");
			die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
		}
		// crmv@205309e

		fclose($in);
		fclose($out);

	} else {
		// crmv@205309
		// try to save in database
		global $adb, $table_prefix;
		$id = $adb->getUniqueId($table_prefix.'_crmentity');
		$FSDB = FileStorageDB::getInstance();
		$res = $FSDB->saveFileData(file_get_contents('php://input'), $targetDir . DIRECTORY_SEPARATOR . $fileName, $id);
		if (!$res) {
			header("HTTP/1.0 500 Internal Server Error");
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		}
		// crmv@205309e
	}
}

// Return JSON-RPC response
die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');
//crmv@22123e