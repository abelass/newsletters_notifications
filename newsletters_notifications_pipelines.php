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


function newsletters_notifications_post_edition($flux) {
	if ($flux['args']['table'] == 'spip_mailsubscribers') {
		$statut_ancien=$flux['args']['statut_ancien'];
		$statut=$flux['data']['statut'];
		
		$statuts_noninscrits=array('prepa','refuse');
		
		if (
			(in_array($statut_ancien, $statuts_noninscrits) AND $statut='valide') OR
			(in_array($statut, $statuts_noninscrits) AND $statut_ancien='valide')
			){
			$notifications = charger_fonction('notifications', 'inc');
							// To do choix des listes selon la config
			$email_inscription=_request('session_email')?_request('session_email'):_request('email_unsubscribe');
			
			if($statut=='valide')$type='subscribe';
			elseif(in_array($statut,$statuts_noninscrits))$type='unsubscribe';
			
			include_spip('inc/config');
			$options = array('config' => lire_config('newsletters_notifications'), 'email_inscription' => $email_inscription,'type'=>$type);


			// Envoyer aux admins
			$notifications('inscription_newsletter', $flux['args']['id_objet'], $options);
			}
		}
	return $flux;
	}