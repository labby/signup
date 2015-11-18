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

// Checking Requirements

$PRECHECK['VERSION']        = array(
    'VERSION' => '2.1.0',
    'OPERATOR' => '>='
);

$PRECHECK['ADDONS']      = array(
    'lib_phpmailer' => array(
        'VERSION' => '5.2.13.2',
        'OPERATOR' => '>='
    ),
    'lib_semantic' => array(
        'VERSION' => '0.1',
        'OPERATOR' => '>='
    )	
);

?>