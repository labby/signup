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

/**	
 *	get the template-engine.
 */
global $parser, $loader, $MOD_SIGNUP, $MOD_SIGNUP_MESSAGE, $TEXT;
if (!is_object($parser)) require_once( LEPTON_PATH."/modules/lib_twig/library.php" );

// prependpath to make sure twig is looking in this module template folder first
$loader->prependPath( dirname(__FILE__)."/templates/" );

// load language file 
if (file_exists(LEPTON_PATH."/modules/signup/languages/".LANGUAGE.".php")) {
	require( LEPTON_PATH."/modules/signup/languages/".LANGUAGE.".php");
} else {
	require( LEPTON_PATH."/modules/signup/languages/EN.php" );
}

//verify all data
if (isset($_GET['hash']) ) {
require_once (LEPTON_PATH."/modules/signup/signup_check.php");
}

//	Get the captha
ob_start();
	call_captcha();
	$captcha = ob_get_clean();

//unset($_SESSION['result_message']);

// create timestamp for multiple use
$timestamp = date('Y-m-d H:i:s',time());
$unix = time();

// create hash for multiple use
$hash = md5(date('Y-m-d H:i:s',time()));

$_SESSION['submitted_when'] = $unix;

$data = array(
//	'print'=> (print_r($_SESSION["signup_error"])),
	'MOD_SIGNUP' 	=> $MOD_SIGNUP,
	'TEXT' 			=> $TEXT,
	'TEMPLATE_DIR'	=>	TEMPLATE_DIR,
	'SIGNUP_URL'	=>	LEPTON_URL."/modules/signup/signup.php",
	'CALL_CAPTCHA'	=>	$captcha,     
	'HASH'			=>	$hash, 
	'signup_message'=> (isset($_SESSION["signup_message"]) ? $_SESSION["signup_message"] : ''),
	'signup_error'	=> (isset($_SESSION["signup_error"]) ? $_SESSION["signup_error"] : ''),
	'submitted_when'=> $unix
	);
		
echo $parser->render( 
	"signup_form.lte",	//	template-filename
	$data				//	template-data
);

if (isset($_SESSION["signup_message"])) unset ($_SESSION["signup_message"]);
if (isset($_SESSION["signup_error"])) unset ($_SESSION["signup_error"]);

?>