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
	global $MENU;
	global $HEADING;
	if (!is_object($parser)) require_once( LEPTON_PATH."/modules/lib_twig/library.php" );

	// prependpath to make sure twig is looking in this module template folder first
	$loader->prependPath( dirname(__FILE__)."/templates/" );


// building a secure-hash
$hash = sha1( microtime().$_SERVER['HTTP_USER_AGENT'] );
$_SESSION['wb_apf_hash'] = $hash;

// use correct language
if (file_exists(LEPTON_PATH."/modules/signup/languages/".LANGUAGE.".php")) {
	require( LEPTON_PATH."/modules/signup/languages/".LANGUAGE.".php");
} else {
	require( LEPTON_PATH."/modules/signup/languages/EN.php" );
}	
	

/**	*********
 *	languages
 *
 */
$query = "SELECT `directory`,`name` from `".TABLE_PREFIX."addons` where `type`='language'";
$result = $database->query( $query );
if (!$result) die ($database->get_error());

$language = array();
while( false != ($data = $result->fetchRow( MYSQL_ASSOC ) ) ) {

	$language[] = array(
		'LANG_CODE' 	=>	$data['directory'],
		'LANG_NAME'		=>	$data['name'],
		'LANG_SELECTED'	=> (LANGUAGE == $data['directory']) ? " selected='selected'" : ""
	);
}


/**	****************
 *	default timezone
 *
 */
global $timezone_table;

$timezone = array();
foreach ($timezone_table as $title)
{
	$timezone[] = array(
		'TIMEZONE_NAME' => $title,
		'TIMEZONE_SELECTED' => ($wb->get_timezone_string() == $title) ? ' selected="selected"' : ''
	);
}

/**	***********
 *	date format
 *
 */

$date_format;
$user_time = true;
include (LEPTON_PATH.'/framework/var.date_formats.php');
foreach($DATE_FORMATS AS $format => $title) {

	$format = str_replace('|', ' ', $format); // Add's white-spaces (not able to be stored in array key)
	
	$value = ($format != 'system_default') ? $format : "";

	if(DATE_FORMAT == $format AND !isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) {
		$sel = "selected='selected'";
	} elseif($format == 'system_default' AND isset($_SESSION['USE_DEFAULT_DATE_FORMAT'])) {
		$sel = "selected='selected'";
	} else {
		$sel = '';	
	}			
	$date_format[] = array(
		'DATE_FORMAT_VALUE'	=>	$value,
		'DATE_FORMAT_TITLE'	=>	$title,
		'DATE_FORMAT_SELECTED' => $sel
	);

}

/**	***********
 *	time format
 *
 */
$time_format = array();

include(LEPTON_PATH.'/framework/var.time_formats.php');
foreach($TIME_FORMATS AS $format => $title) {
	$format = str_replace('|', ' ', $format); // Add's white-spaces (not able to be stored in array key)

	$value = ($format != 'system_default') ? $format : "";

	if(TIME_FORMAT == $format AND !isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) {
		$sel = "selected='selected'";	
	} elseif($format == 'system_default' AND isset($_SESSION['USE_DEFAULT_TIME_FORMAT'])) {
		$sel = "selected='selected'";
	} else {
		$sel = '';
	}			
	$time_format[] = array(
		'TIME_FORMAT_VALUE'	=>	$value,
		'TIME_FORMAT_TITLE'	=>	$title,
		'TIME_FORMAT_SELECTED' => $sel
	);
}

/**
 *
 *
 */
$hash = sha1( microtime().$_SERVER['HTTP_USER_AGENT'] );
$_SESSION['wb_apf_hash'] = $hash;


#unset($_SESSION['result_message']);

$data = array(
	'TEXT' => $TEXT,		// Komplettes "TEXT" Array
	'HEADING' => $HEADING,
	'MENU' => $MENU,
	'MOD_SIGNUP' => $MOD_SIGNUP,
	'TEMPLATE_DIR' 				=>	TEMPLATE_DIR,
	'PREFERENCES_URL'			=>	PREFERENCES_URL,
	'DISPLAY_NAME'				=>	$wb->get_display_name(),
	'GET_EMAIL'					=>	$wb->get_email(),
	'USER_ID'					=>	(isset($_SESSION['USER_ID']) ? $_SESSION['USER_ID'] : '-1'),
	'r_time'					=>	TIME(),
	'HASH'						=>	$hash,
	'RESULT_MESSAGE'			=> (isset($_SESSION['result_message'])) ? $_SESSION['result_message'] : "(keine Session) [Aldus]",
	'AUTH_MIN_LOGIN_LENGTH'		=> AUTH_MIN_LOGIN_LENGTH,
	'language'	=> $language,
	'timezone'	=> $timezone,
	'date_format' => $date_format,
	'time_format' => $time_format	
);
		
echo $parser->render( 
	"preferences_form.lte",	//	template-filename
	$data			//	template-data
);

?>