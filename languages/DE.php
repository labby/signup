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

$MOD_SIGNUP = array(
	'LOGIN'	=> "Bitte einloggen",
	'OR'	=> "oder",
	'REGISTER'			=> "Registrieren",
	'CHOSE_CONTACT_TYPE'=> '<p>Bitte wählen Sie einen Kontakt-Typ<br />Achtung: Der Kontakt-Typ kann nach der Regsitrierung nicht mehr geändert werden!</p>',
	'CONTACT_TYPE'		=> "Kontakt Typ",
	'AGREE_TERMS'		=> "Ich stimme den Bedingungen zu",
	'PERSON'			=> "Person",
	'COMPANY'			=> "Firma",
	'RETYPE_PASSWORD'	=> "Passwort wiederholen",
	'PERS_SETTINGS'		=> "Persönliche Einstellungen",
);

$MOD_SIGNUP_MESSAGE = array(
	'terms'			=> "Sie müssen den Bedingungen zustimmen",
	'wrong_captcha'	=> "Bitte das korrekte Ergebnis eintragen!",		
	'already_signup'=> "Sie sind bereits registriert",
	'signup_subject'=> "Ihre Registrierung",
	'signup_text'	=> "Sie haben sich registriert.<br />Zur Verifizierung Ihrer Mail-Adresse klicken Sie bitte auf folgenden Link:<br /><a href='%s'>Email Verifizierung</a>",
	'signup_info'	=> "Danke für die Registrierung. <br /> Sie werden eine Email erhalten, um Ihre Email-Adresse zu verifizieren.<br />Bitte prüfen Sie auch ihr Spamverzeichnis.",
	'already_verfied' => "Sie haben Ihre Mailadresse bereits verifiziert.",
	'confirm_text'	=> "Sie haben sich auf unserer Seite angemeldet.<br />Bitte loggen Sie sich mit folgendem Passwort ein:<br />Passwort:<b>%s</b> <br/>Sie können das Passwort unter Einstellungen ändern.",	
	'signup_confirm_info' =>"Danke für die Verifizierung der Email-Adresse. Sie sind nun registriert und können sich mit dem Passwort einloggen, dass wir Ihnen zugesendet haben.",
	'error_info_admin'	=> "FEHLER,das sollte nicht sein.<br /> Bitte informieren Sie uns über diesen Fehler. <br /> Danke",
);
?>