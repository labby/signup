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

if (!function_exists("rename_recursive_dirs")) require_once(LEPTON_PATH."/framework/functions/function.rename_recursive_dirs.php");

// see if current theme has frontend_login
$directory = LEPTON_PATH."/templates/".DEFAULT_TEMPLATE."/frontend/login";
$directory_new = LEPTON_PATH."/templates/".DEFAULT_TEMPLATE."/frontend/login_standard";
if(file_exists(LEPTON_PATH."/templates/".DEFAULT_TEMPLATE."/frontend/login/index.php")) {
	rename_recursive_dirs( $directory,$directory_new);
	}

// move new module files to current theme
$directory = LEPTON_PATH.'/modules/signup/install/frontend/login';
$directory_new = LEPTON_PATH."/templates/".DEFAULT_TEMPLATE."/frontend/login";
{
	rename_recursive_dirs( $directory,$directory_new);
}


// extend standard user table
 	$table = TABLE_PREFIX .'users'; 
	$database->query("ALTER TABLE `".$table."`  ADD `contact_type` VARCHAR(32) NOT NULL ");
	$database->query("ALTER TABLE `".$table."`  ADD `registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ");
	$database->query("ALTER TABLE `".$table."`  ADD `hash` VARCHAR(64) NOT NULL ");	
	$database->query("ALTER TABLE `".$table."`  ADD `unix_time` VARCHAR(14) NOT NULL ");	

	

//	create new user group
  	$table = TABLE_PREFIX .'groups'; 
	$database->query("INSERT INTO `".$table."` (`group_id`, `name`, `system_permissions`, `module_permissions`, `template_permissions`) VALUES
	('', 'Auto-Signup', 'pages,pages_view','','')
	");


//	modify settings table
	$group_id = $database->get_one("SELECT LAST_INSERT_ID()");
	
	$database->query("UPDATE `" . TABLE_PREFIX ."settings` SET `value` ='".$group_id."' WHERE `name` ='frontend_signup'");
	$database->query("UPDATE `" . TABLE_PREFIX ."settings` SET `value` ='true' WHERE `name` ='frontend_login'");

?>	