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
 
$debug = true;
if (true === $debug) {
	ini_set('display_errors', 1);
	error_reporting(-1);
} 

/**
 *	load the correct language-file
 */
$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );

global $MOD_SIGNUP, $MOD_SIGNUP_MESSAGE, $MESSAGE, $TEXT;

// check $_POST for maschines
if(isset($_POST['full_name']) and ($_POST['full_name'] != '')) {
		header("Location: ".$_SERVER['HTTP_REFERER']." ");
		die();
} 

// check $_POST
if(!isset($_POST['username']) OR!is_numeric($_POST['submitted_when'])) {
		header("Location: ".$_SERVER['HTTP_REFERER']." ");
	exit(0);
} else {
	$user = $_POST['username'];
}


if ($_SESSION['captcha'] != $_POST['captcha']) {
	$_SESSION["signup_error"] = $MOD_SIGNUP_MESSAGE['wrong_captcha'];
	header("Location: ".$_SERVER['HTTP_REFERER']." ");
	die();
}

//Get all settings 
$settings = array();
$database->execute_query(
"SELECT * FROM ".TABLE_PREFIX."settings",
TRUE,
$settings, TRUE
);

// get needed values from settings
$email_settings= array(
'wbmailer_default_sendername' => '0',
'server_email' => '0',
'default_language' => '0',
'default_timezone_string' =>'0'
);

foreach ($email_settings as $name=>$value) {
	foreach ($settings as $temp) {
		if ($name == $temp['name'] ) $email_settings[$name] = $temp['value'];
	}
}



//Get group_id from inserted group
$signup_group = array();
$database->execute_query(
"SELECT `group_id` FROM ".TABLE_PREFIX."groups WHERE `name` = 'Auto-Signup'  ",
TRUE,
$signup_group, FALSE
);


//Get all users and check double for multiple use
$existing_users = array();
$database->execute_query(
"SELECT * FROM ".TABLE_PREFIX."users",
TRUE,
$existing_users, TRUE
);

$double_mail = false;
foreach ($existing_users as &$ref) if($ref['email'] == $_POST['email']){
	$double_mail = true;
	break;
}

$double_username = false;
foreach ($existing_users as &$ref) if($ref['username'] == $_POST['username']){
	$double_username = true;
	break;
}


// create timestamp for multiple use
$timestamp = date('Y-m-d H:i:s',time());
$unix = time();

// create hash for multiple use
$hash = md5(date('Y-m-d H:i:s',time()));

// create confirmation link
$confirm_signup = $_SERVER['HTTP_REFERER'].'?hash='.$hash;

// include phpmailer
require_once (LEPTON_PATH.'/modules/lib_phpmailer/library.php');

// prevent double signup email
if ($double_mail == true){
	$_SESSION["signup_error"] = $MOD_SIGNUP_MESSAGE['already_signup'];
	header("Location: ".$_SERVER['HTTP_REFERER']." ");
	die();
}

// prevent double signup username
if ($double_username == true){
	$_SESSION["signup_error"] = $MESSAGE['USERS_USERNAME_TAKEN'];
	header("Location: ".$_SERVER['HTTP_REFERER']." ");
	die();
}

//send mail	
//Create a new PHPMailer instance
$mail = new PHPMailer;
$mail->CharSet = DEFAULT_CHARSET;	
//Set who the message is to be sent from
$mail->setFrom($email_settings['server_email'],$email_settings['wbmailer_default_sendername']);
//Set an alternative reply-to address
$mail->addReplyTo($email_settings['server_email'],$email_settings['wbmailer_default_sendername']);
//Set who the message is to be sent to
$mail->addAddress($_POST['email'], $_POST['display_name']);
//Send bcc to admin
//$mail->addBCC($email_settings['server_email'],$email_settings['wbmailer_default_sendername']);
//Set the subject line
$mail->Subject = $MOD_SIGNUP_MESSAGE['signup_subject'];
//Switch to TEXT messages
$mail->IsHTML(true);
$mail->Body = sprintf($MOD_SIGNUP_MESSAGE['signup_text'],$confirm_signup);	

//send the message, check for errors
if (!$mail->send()) {
		$_SESSION["signup_error"] = "Mailer Error: " . $mail->ErrorInfo;
		header("Location: ".$_SERVER['HTTP_REFERER']." ");
		die();
} else {
	//save into database
	$fields = array(
	'group_id'	=>	'2',
	'groups_id'	=>	'2',
	'active'	=>	'0',	
	'statusflags'	=>	'16',
	'username'	=>	$_POST['username'],		
	'password'	=>	$unix,
	'display_name'	=>	$_POST['display_name'],
	'email'		=>	$_POST['email'],
	'timezone_string'	=> $email_settings['default_timezone_string'],
	'language'	=> $email_settings['default_language'],
	'contact_type'	=> $_POST['type'],
	'registered'	=> $timestamp,			
	'hash'	=> $hash,
	'unix_time'	=> $unix
		
	);
	$database->build_and_execute('INSERT', TABLE_PREFIX.'users', $fields);
		if ($database->is_error()) die($database->get_error());
		
	$_SESSION["signup_message"] = $MOD_SIGNUP_MESSAGE['signup_info'];
	header("Location: ".$_SERVER['HTTP_REFERER']." ");

}

?>