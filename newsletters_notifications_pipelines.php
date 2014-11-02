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

if (!defined('_ECRIRE_INC_VERSION'))
	return;

function newsletters_notifications_formulaire_traiter($flux) {
	// envoyer un notification de l'inscription
	$form=$flux['args']['form'];
	if ($form == 'newsletter_subscribe' OR $form='newsletter_unubscribe') {

		if (isset($flux['data']['message_ok'])) {
			$type=($form == 'newsletter_subscribe')?'subscribe':'unsubscribe';
			// Notifications
			if ($notifications = charger_fonction('notifications', 'inc', true)) {
				// To do choix des listes selon la config
				$email_inscription=_request('session_email')?_request('session_email'):_request('email_unsubscribe');
				include_spip('inc/config');
				$options = array('config' => lire_config('newsletters_notifications'), 'email_inscription' => $email_inscription,'type'=>$type);
				$listes = $flux['args']['args'][0];
				if ($listes AND is_string($listes))
					$listes = explode(',', $listes);

				// Envoyer aux admins
				$notifications('inscription_newsletter', $listes, $options);
			}
		}
	}
	return $flux;
}