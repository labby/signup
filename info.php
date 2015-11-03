<?php

/**
 * @module          Signup
 * @author          cms-lab
 * @copyright       2014-2015 cms-lab
 * @link            http://www.cms-lab.com
 * @license         GNU GPL http://www.gnu.org/licenses/gpl-3.0.en.html
 * @license_terms   none
 *
 */

// include class.secure.php to protect this file and the whole CMS!
if (defined('LEPTON_PATH')) {	
	include(LEPTON_PATH.'/framework/class.secure.php'); 
} else {
	$oneback = "../";
	$root = $oneback;
	$level = 1;
	while (($level < 10) && (!file_exists($root.'/framework/class.secure.php'))) {
		$root .= $oneback;
		$level += 1;
	}
	if (file_exists($root.'/framework/class.secure.php')) { 
		include($root.'/framework/class.secure.php'); 
	} else {
		trigger_error(sprintf("[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER['SCRIPT_NAME']), E_USER_ERROR);
	}
}
// end include class.secure.php

$module_directory     = 'signup';
$module_name          = 'Signup';
$module_function      = 'tool';
$module_version       = '0.1.1';
$module_platform      = '2.x';
$module_author        = 'cms-lab';
$module_home          = 'http://www.cms-lab.com';
$module_guid          = '533a3e58-8193-4595-9bbc-92e713b48b58';
$module_license       = '<a href="http://www.gnu.org/licenses/gpl-3.0.en.html" target="_blank">GNU GPL</a>';
$module_license_terms = 'none';
$module_description   = 'User-management with frontend login and frontend register procedure';


?>