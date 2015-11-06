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

// delete signup/login installed templates
if (!function_exists("rm_full_dir")) require_once( dirname(__FILE__)."/function.rm_full_dir.php");
if(file_exists(LEPTON_PATH."/templates/".DEFAULT_TEMPLATE."/frontend/login/index.php")) {
	rm_full_dir(LEPTON_PATH."/templates/".DEFAULT_TEMPLATE."/frontend/login")
}

// see if current theme frontend_login was modified
if (!function_exists("rename_recursive_dirs")) require_once(LEPTON_PATH."/framework/functions/function.rename_recursive_dirs.php");
$directory = LEPTON_PATH."/templates/".DEFAULT_TEMPLATE."/frontend/login_standard";
$directory_new = LEPTON_PATH."/templates/".DEFAULT_TEMPLATE."/frontend/login";
if(file_exists(LEPTON_PATH."/templates/".DEFAULT_TEMPLATE."/frontend/login/index.php"))
{
	rename_recursive_dirs( $directory,$directory_new);
}

// alter standard user table
	$table = TABLE_PREFIX .'users'; 
	$database->query("ALTER TABLE `".$table."`  DROP COLUMN `contact_type` ");
	$database->query("ALTER TABLE `".$table."`  DROP COLUMN `registered` ");
	$database->query("ALTER TABLE `".$table."`  DROP COLUMN `hash` ");	
	$database->query("ALTER TABLE `".$table."`  DROP COLUMN `unix_time` ");	
	
?>