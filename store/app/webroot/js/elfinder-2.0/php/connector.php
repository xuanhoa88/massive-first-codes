<?php

error_reporting(E_ALL); // Set E_ALL for debuging
$here = dirname(__FILE__).DIRECTORY_SEPARATOR;
include_once $here . 'elFinderConnector.class.php';
include_once $here . 'elFinder.class.php';
include_once $here . 'elFinderVolumeDriver.class.php';
include_once $here . 'elFinderVolumeLocalFileSystem.class.php';
// Required for MySQL storage connector
// include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeMySQL.class.php';

/**
 * Simple function to demonstrate how to control file access using "accessControl" callback.
 * This method will disable accessing files/folders starting from  '.' (dot)
 *
 * @param  string  $attr  attribute name (read|write|locked|hidden)
 * @param  string  $path  file path relative to volume root directory started with directory separator
 * @param $data
 * @param $volume
 * @return bool
 *
 */
function access($attr, $path, $data, $volume) {
	return strpos(basename($path), '.') === 0   // if file/folder begins with '.' (dot)
		? !($attr == 'read' || $attr == 'write')  // set read+write to false, other (locked+hidden) set to true
		: ($attr == 'read' || $attr == 'write');  // else set read+write to true, locked+hidden to false
}

$opts = array(
    'debug' => true,
    'roots' => array(
        array(
            'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
            'path'          => '../../../uploads/userfiles',         // path to files (REQUIRED)
            'URL'           => dirname($_SERVER['PHP_SELF']) . '/../../../uploads/userfiles', // URL to files (REQUIRED)
            'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
        )
    )
);

// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();

