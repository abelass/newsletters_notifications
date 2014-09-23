<?php
/**
 * Utilisations de pipelines par Notifications pour Newsletters
 *
 * @plugin     Notifications pour Newsletters
 * @copyright  2014
 * @author     Rainer
 * @licence    GNU/GPL
 * @package    SPIP\Newsletters_notifications\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) return;
	

function newsletters_notifications_formulaire_traiter($flux){
	// envoyer un notification de l'inscription
	if ($flux['args']['form'] == 'newsletter_subscribe') {
		echo serialize($flux);
		if(isset($flux['data']['message_ok']))echo 'ok;'
	}
	return $flux;
}



?>