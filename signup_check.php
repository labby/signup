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

// create timestamp for multiple use
$timestamp = date('Y-m-d H:i:s',time());
$unix = time();

// create a random password
if (!function_exists('password_hash')) {
	require_once (LEPTON_PATH.'/modules/lib_lepton/hash/password.php');
} 
require_once( LEPTON_PATH."/framework/functions/function.random_string.php" );
$generate_pass = random_string(10,'pass');
$password = password_hash($generate_pass, PASSWORD_DEFAULT );

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

// delete users without confirmation if signup is not confirmed within 48 hours = 172800 seconds while signup page is called 
	$database->execute_query(
		"DELETE FROM `".TABLE_PREFIX."users`WHERE '".$unix."' > `time_unix`+172800 AND `statusflags` = 10 "
	);	

// email verification via hash
if (isset($_GET['hash']) ) {
	$new_user = array();
	$database->execute_query(
		"SELECT * FROM `".TABLE_PREFIX."users` WHERE `hash` = '".$_GET['hash']."'",
		true,
		$new_user,
		false
	);
		// no hash in database
	if (count ($new_user)== 0){
		$new_user['hash'] = -1;
		$new_user['statusflags'] = -1;		
	}

	// prevent double verification 
	if( $new_user['active'] == 1 and $new_user['statusflags'] == 16) {
		$_SESSION["signup_error"] = $MOD_SIGNUP_MESSAGE['already_verfied'];
		header("Location: ".LEPTON_URL."/account/signup.php ");
		die();
		
	}

	//	update new user in database
	if( count (($new_user) == 1) and ($new_user['statusflags'] == 10)) {	
	
		//send mail	
		//Create a new PHPMailer instance
		$mail = new PHPMailer;
		$mail->CharSet = DEFAULT_CHARSET;	
		//Set who the message is to be sent from
		$mail->setFrom($email_settings['server_email'],$email_settings['wbmailer_default_sendername']);
		//Set an alternative reply-to address
		$mail->addReplyTo($email_settings['server_email'],$email_settings['wbmailer_default_sendername']);
		//Set who the message is to be sent to
		$mail->addAddress($new_user['email'], $new_user['display_name']);
		//Send bcc to admin
		$mail->addBCC($email_settings['server_email'],$email_settings['wbmailer_default_sendername']);
		//Set the subject line
		$mail->Subject = $MOD_SIGNUP_MESSAGE['signup_subject'];
		//Switch to TEXT messages
		$mail->IsHTML(true);
		$mail->Body = sprintf($MOD_SIGNUP_MESSAGE['confirm_text'],$generate_pass);	

		//send the message, check for errors
		if (!$mail->send()) {
				$_SESSION["signup_error"] = "Mailer Error: " . $mail->ErrorInfo;
				header("Location: ".LEPTON_URL."/account/signup.php ");
				die();
		}
		$fields = array(
			'registered' => $timestamp,
			'active'	=> '1',
			'statusflags' => '16',
			'password' => $password
		);
			
		$database->build_and_execute(
			'UPDATE',
			TABLE_PREFIX."users",
			$fields,
			"`hash`='".$new_user['hash']."'"
		);
			
		//	set up the success-message
		$_SESSION["signup_message"] = $MOD_SIGNUP_MESSAGE['signup_confirm_info'];
		header("Location: ".LEPTON_URL."/account/signup.php ");		
		die();
	}

} // end email verification via hash
else {
		$_SESSION["signup_error"] = $MOD_SIGNUP_MESSAGE['error_info_admin'];
		header("Location: ".LEPTON_URL."/account/signup.php ");
}	
?>