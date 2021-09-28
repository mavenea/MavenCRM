<?php
/*
 * SmartOptimizer CSS Minifier
 */

use MatthiasMullie\Minify;
		
function minify_css($files, $mode = 0, $cacheFile = null) {
	global $settings;
	require_once('pathconverter/converterinterface.php');
	require_once('pathconverter/converter.php');
	require_once('pathconverter/noconverter.php');
	require_once('minify.php');
	require_once('cssmin.php');
		
	$minifier = new Minify\CSS();
	$minifier->setMaxImportSize($settings['embedMaxSize']);
	$minifier->add($files);
		
	#mode 0 no gzip & no save MINIFY NO SAVE
	#mode 1 gzip & no save GZIP NO SAVE
	#mode 2 no gzip & save MINIFY SAVE
	#mode 3 gzip & save GZIP SAVE
	switch ($mode) {
		case 0:
			$result = $minifier->minify();
			break;
		case 1:
			$result = $minifier->gzip();
			break;
		case 2:
			$result = $minifier->minify('../smartoptimizer/' . $cacheFile, $settings['compressionLevel']);
			break;
		case 3:
			$result = $minifier->gzip('../smartoptimizer/' . $cacheFile, $settings['compressionLevel']);
			break;
	}
	return $result;
}
