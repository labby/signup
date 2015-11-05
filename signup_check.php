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

// create timestamp for multiple use
$timestamp = date('Y-m-d H:i:s',time());
$unix = time();

// delete users without confirmation if signup is not confirmed within 48 hours = 172800 seconds while signup page is called 
	$database->execute_query(
		"DELETE FROM `".TABLE_PREFIX."users`WHERE '".$unix."' > `time_unix`+172800 AND `statusflags` = 16 "
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
	if( $new_user['hash'] == $_GET['hash'] and $new_user ['statusflags'] == 32 {
		$_SESSION["signup_error"] = $MOD_SIGNUP_MESSAGE['already_verfied'];
	}		

	elseif( count (($new_user) == 1) and ($new_user['statusflags'] == 16)) {	
		//	[2] save current time into database
		$fields = array(
			'registered' => $timestamp,
			'active'	=> '1',
			'statusflags' => '32',
		);
		
		$database->build_and_execute(
			'UPDATE',
			TABLE_PREFIX."mod_law_newsletter_subscription",
			$fields,
			"`sub_hash`='".$newsletter['sub_hash']."'"
		);
		
		//	[3] set up the success-message
		//  use not standard text or forwarding
		if($settings['use_standard_text'] != '1'){
			header("Location: ".$settings['forward_url_signin_conf']." ");			
		}
		else {
				$_SESSION["nl_message"] = $MOD_NEWSLETTER_MESSAGE['nl_subscribe_confirm_info'];
				header("Location: ".$_SERVER['HTTP_REFERER']." ");
				die();
		}
		
	}
//	elseif( $newsletter['sub_hash'] == $_GET['hash'] and $newsletter['sub_active'] != 1) {
	elseif( count (($newsletter) == 1) and ($newsletter['sub_active'] != 1)) {	
			//	[1] Good place for testing something e.g. time from the submit
			
			//	[2] save current time into database
		$fields = array(
			'sub_signout_conf' => $timestamp,
			'sub_active' => 0
			);
			
		$database->build_and_execute(
			'UPDATE',
			TABLE_PREFIX."mod_law_newsletter_subscription",
			$fields,
			"`sub_hash`='".$newsletter['sub_hash']."'"
		);
			
		//	[3] set up the success-message
		//  use not standard text or forwarding
		if($settings['use_standard_text'] != '1'){
			header("Location: ".$settings['forward_url_signout_conf']." ");			
		}
		else {
				$_SESSION["nl_message"] = $MOD_NEWSLETTER_MESSAGE['nl_unsubscribe_confirm_info'];
				header("Location: ".$_SERVER['HTTP_REFERER']." ");
				die();
		}			

	}
	else {
		$_SESSION["nl_error"] = $MOD_NEWSLETTER_MESSAGE['error_info_admin'];
	}
}
?>