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
 
global $lepton_filemanager;
if (!is_object($lepton_filemanager)) require_once( "../../framework/class.lepton.filemanager.php" );

$files_to_register = array(

	'/modules/signup/signup.php',		
	'/modules/signup/headers.inc.php'
);

$lepton_filemanager->register( $files_to_register );

?>