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

// delete subscribers without confirmation if newsletter page is called if subscription is not confirmed within 48 hours = 172800 seconds
	$database->execute_query(
		"DELETE FROM `".TABLE_PREFIX."mod_law_newsletter_subscription`WHERE '".$unix."' > `sub_unix`+172800 AND `sub_signin_conf` = '0000-00-00 00:00:00' "
	);	

//Get all settings	
$settings = array();
$database->execute_query(
"SELECT * FROM ".TABLE_PREFIX."mod_law_newsletter_settings",
TRUE,
$settings, FALSE
);	
	
// email verification via hash
if (isset($_GET['hash']) ) {
	$newsletter = array();
	$database->execute_query(
		"SELECT * FROM `".TABLE_PREFIX."mod_law_newsletter_subscription` WHERE `sub_hash` = '".$_GET['hash']."'",
		true,
		$newsletter,
		false
	);

	// no hash in database
	if (count ($newsletter)== 0){
		$newsletter['sub_hash']	= -1;
		$newsletter['sub_active']	= -1;		
	}
	
// prevent double verification subscriber
	if( $newsletter['sub_hash'] == $_GET['hash'] and $newsletter['sub_active'] == 1 and $newsletter['sub_signin_conf'] != '0000-00-00 00:00:00' ) {
		$_SESSION["nl_error"] = $MOD_NEWSLETTER_MESSAGE['already_subscribed'];
	}		
// prevent double verification unsubscriber
	elseif( $newsletter['sub_hash'] == $_GET['hash'] and $newsletter['sub_active'] != 1 and $newsletter['sub_signout_conf'] != '0000-00-00 00:00:00' ) {
		$_SESSION["nl_error"] = $MOD_NEWSLETTER_MESSAGE['already_unsubscribed'];
	}	
//	elseif( $newsletter['sub_hash'] == $_GET['hash'] and $newsletter['sub_active'] == 1) {
	elseif( count (($newsletter) == 1) and ($newsletter['sub_active'] == 1)) {
		//	[1] Good place for testing something e.g. time from the submit
		
		//	[2] save current time into database
		$fields = array(
			'sub_signin_conf' => $timestamp
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