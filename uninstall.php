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

// alter standard user table
	$table = TABLE_PREFIX .'users'; 
	$database->query("ALTER TABLE `".$table."`  DROP `reg_code`, `contact_type`, `timestamp`  ");

/**
 *	remove module tables from database
 */ 	
$tables = array (
	TABLE_PREFIX."mod_2contact_contacts",
	TABLE_PREFIX."mod_2contact_country"
);

$all_jobs = array();


//	Delete the tables
$query = "DROP TABLE IF EXISTS `".$tables[0]."`,`".$tables[1]."`";

$all_jobs[] = $query;

foreach( $all_jobs as $q ) {
	
	$database->query($q);
	
	if ( $database->is_error() ) 
		$admin->print_error($database->get_error(), $js_back);

}
	
	

?>