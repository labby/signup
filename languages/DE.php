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

global $MOD_SIGNUP;
$MOD_SIGNUP = array(
	'LOGIN'	=> "Please login",
	'OR'	=> "or",
	'REGISTER'	=> "sign up",
	'CHOSE_CONTACT_TYPE'	=> '<p>Please chose a sign-up type  <br />Notice: the type cannot be changed after registration!</p>',
	'CONTACT_TYPE'	=> "Sign-Up Type",
	'AGREE_TERMS'	=> "I agree to the terms and conditions",
	'PERSON'	=> "Person",
	'COMPANY'	=> "Company",
	'RETYPE_PASSWORD'	=> "Retype Password",
	'PERS_SETTINGS'	=> "Personal Settings",
);

?>