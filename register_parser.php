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

/**	*******************************
 *	Try to get the template-engine.
 *
 *	Make your basic settings for your module-backend interface(-s) here.
 *	Keep in mind, that the paths-settings belongs to the backend only!
 *
 */
global $parser, $loader;
if (!isset($parser))
{
	require_once( LEPTON_PATH."/modules/lib_twig/library.php" );
}

require(dirname(__FILE__)."/info.php");

$loader->prependPath( dirname(__FILE__)."/templates/frontend/", $module_directory );

$backend_template_path = LEPTON_PATH."/templates/".DEFAULT_THEME."/backend/".$module_directory."/"; // # Aldus: Achtung, Ordner heisst "backend"
$module_template_path = dirname(__FILE__)."/templates/frontend/";

require_once (LEPTON_PATH."/modules/lib_twig/classes/class.twig_utilities.php");
$twig_util = new twig_utilities( $parser, $loader, $module_template_path, $backend_template_path );
$twig_util->template_namespace = $module_directory;

$twig_modul_namespace = "@".$module_directory."/"

?>