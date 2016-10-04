<?php

/**
 * @module          Signup
 * @author          cms-lab
 * @copyright       2014-2016 cms-lab
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

$MOD_SIGNUP = array(
	'LOGIN'	=> "Please login",
	'OR'	=> "or",
	'REGISTER'			=> "sign up",
	'CHOSE_CONTACT_TYPE'=> '<p>Please chose a sign-up type  <br />Notice: the type cannot be changed after registration!</p>',
	'CONTACT_TYPE'		=> "Sign-Up Type",
	'AGREE_TERMS'		=> "I agree to the terms and conditions",
	'PERSON'			=> "Person",
	'COMPANY'			=> "Company",
	'RETYPE_PASSWORD'	=> "Retype Password",
	'PERS_SETTINGS'		=> "Personal Settings",
);

$MOD_SIGNUP_MESSAGE = array(
	'terms'				=> "You have to agree to the terms and conditions",
	'wrong_captcha'		=> "Please enter a valid result",	
	'already_signup'	=> "You have already signed up",
	'signup_subject'	=> "Your Signup",
	'signup_text'		=> "You have signed up.<br />To verify your email you have to click following link:<br /><a href='%s'>Verify my email</a>",
	'signup_info'		=> "Thanks for signing up. <br /> You will receive an email to verify your account.<br />Please check also your spamfolder.",	
	'already_verfied' 	=> "You have already verified your email.",
	'confirm_text'		=> "You have signed up.<br />To enter a passwor plaease click the following link:<br /><a href='%s'>Enter password</a>",
	'signup_confirm_info' =>"Thank you for verifying your email. You will get an mail to enter your password.",
	'error_info_admin'	=> "ERROR, this should not happen.<br /> Please inform us about this error. <br /> Thanks",
);



?>