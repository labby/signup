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
if ( defined( 'LEPTON_PATH' ) )
{
    include( LEPTON_PATH . '/framework/class.secure.php' );
} 
else
{
    $oneback = "../";
    $root    = $oneback;
    $level   = 1;
    while ( ( $level < 10 ) && ( !file_exists( $root . '/framework/class.secure.php' ) ) )
    {
        $root .= $oneback;
        $level += 1;
    } 
    if ( file_exists( $root . '/framework/class.secure.php' ) )
    {
        include( $root . '/framework/class.secure.php' );
    } 
    else
    {
        trigger_error( sprintf( "[ <b>%s</b> ] Can't include class.secure.php!", $_SERVER[ 'SCRIPT_NAME' ] ), E_USER_ERROR );
    }
}
// end include class.secure.php

//initialize twig template engine
	global $parser;		// twig parser
	global $loader;		// twig file manager
	global $MOD_SIGNUP;
	global $TEXT;
	if (!is_object($parser)) require_once( LEPTON_PATH."/modules/lib_twig/library.php" );

	// prependpath to make sure twig is looking in this module template folder first
	$loader->prependPath( dirname(__FILE__)."/templates/" );

	
if (file_exists(LEPTON_PATH."/modules/signup/languages/".LANGUAGE.".php")) {
	require( LEPTON_PATH."/modules/signup/languages/".LANGUAGE.".php");
} else {
	require( LEPTON_PATH."/modules/signup/languages/EN.php" );
}
	

/**
 *
 *
 */
$hash = sha1( microtime().$_SERVER['HTTP_USER_AGENT'] );
$_SESSION['wb_apf_hash'] = $hash;

$redirect_url = (isset($_SESSION['HTTP_REFERER']) ? $_SESSION['HTTP_REFERER'] : "");



unset($_SESSION['result_message']);

		$data = array(
	'MOD_REGISTER' => $MOD_SIGNUP,
	'TEXT' => $TEXT,		
	'TEXT_FORGOT'		=>	$MENU['FORGOT'], 
	'MESSAGE_COLOR'		=>	$message_color,
	'MESSAGE'		    =>	$message,
	'FORGOT_URL'		=>	FORGOT_URL,  
	'URL'			    =>	$redirect_url,
	'TEXT_EMAIL'		=>	$TEXT['EMAIL'],
	'DISPLAY_EMAIL'		=>	$email,   
	'TEXT_SEND_DETAILS'	=>	$TEXT['SEND_DETAILS'],
	'TEXT_LOGOUT'		=>	$MENU['LOGOUT'],
	'TEXT_RESET'		=>	$TEXT['RESET'],
	'HASH'				=>	$hash,
	'TEXT_FORGOTTEN_DETAILS' => $TEXT['FORGOTTEN_DETAILS']
		);
		
		echo $parser->render( 
			"forgot_form.lte",	//	template-filename
			$data			//	template-data
		);

?>