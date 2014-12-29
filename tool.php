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

echo "Backend - at this time there is nothing to edit here.";

/**
 *	Try to laod the correct language-file
 */
$lang = (dirname(__FILE__))."/languages/". LANGUAGE .".php";
require_once ( !file_exists($lang) ? (dirname(__FILE__))."/languages/EN.php" : $lang );

/**
 *	Try to get the parser
 */
global $parser, $loader;
if (!isset($parser))
{
	require_once( LEPTON_PATH."/modules/lib_twig/library.php" );
}

require(dirname(__FILE__)."/info.php");

$loader->prependPath( dirname(__FILE__)."/templates/backend/", $module_directory );

/**
 *	OverView
 */
$template_values = array(
	'overview'	=> "Übersicht"
);

echo $parser->render(
	"@".$module_directory.'/overview.lte',
	$template_values
);


?>