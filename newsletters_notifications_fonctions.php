<?php
/**
 * Utilisations de pipelines par Notifications pour Newsletters
 *
 * @plugin     Notifications pour Newsletters
 * @copyright  2014
 * @author     Rainer
 * @licence    GNU/GPL
 * @package    SPIP\Newsletters_notifications\Fonctions
 */

if (!defined('_ECRIRE_INC_VERSION'))
	return;

if (!defined('_ECRIRE_INC_VERSION')) return;

/**
 * Des-inscrire un email deja en base
 * (mise a jour du statut en refuse)
 *
 * @param string $email
 */
 
 function action_unsubscribe_mailsubscriber($email=null, $double_optin=true){
	include_spip('mailsubscribers_fonctions');
	if (is_null($email)){
		$email = _request('email');
		$arg = _request('arg');
		if (is_null($arg) AND strpos($_SERVER["QUERY_STRING"],"arg%")!==false){
			$query = str_replace("arg%","arg=",$_SERVER["QUERY_STRING"]);
			parse_str($query,$args);
			$arg = strtolower($args['arg']);
			if (strlen($arg)>40)
				$arg = substr($arg,-40);
		}
		$row = sql_fetsel('id_mailsubscriber,email,jeton,lang,statut','spip_mailsubscribers','email='.sql_quote($email));
		if (!$row)
			spip_log("unsubscribe_mailsubscriber : email $email pas dans la base spip_mailsubscribers","mailsubscribers");
		else {
			$cle = mailsubscriber_cle_action("unsubscribe",$row['email'],$row['jeton']);
			if ($arg!==$cle){
				spip_log("unsubscribe_mailsubscriber : cle $arg incorrecte pour email $email","mailsubscribers");
				$row = false;
			}
		}
	}
	else {
		$double_optin = false;
		$row = sql_fetsel('id_mailsubscriber,email,jeton,statut','spip_mailsubscribers','email='.sql_quote($email));
	}
	if (!$row){
		include_spip('inc/minipres');
		echo minipres();
		exit;
	}

	include_spip("inc/lang");
	changer_langue($row['lang']);
	include_spip("inc/autoriser");

	if (!$row['statut']=='valide'){
		$titre = _T('mailsubscriber:unsubscribe_deja_texte',array('email'=>$row['email']));
	}
	else {
		if ($double_optin){
			include_spip("inc/filtres");
			$titre = _T('mailsubscriber:unsubscribe_texte_confirmer_email_1',array('email'=>$row['email']));
			$titre .= "<br /><br />".bouton_action(_T('newsletter:bouton_unsubscribe'),generer_action_auteur('confirm_unsubscribe_mailsubscriber',"$email-$arg"));
		}
		else {
			autoriser_exception("modifier","mailsubscriber",$row['id_mailsubscriber']);
			autoriser_exception("instituer","mailsubscriber",$row['id_mailsubscriber']);
			// OK l'email est connu et valide
			include_spip("action/editer_objet");
			objet_modifier("mailsubscriber",$row['id_mailsubscriber'],array('statut'=>'refuse'));
			$titre = _T('mailsubscriber:unsubscribe_texte_email_1',array('email'=>$row['email']));
			autoriser_exception("modifier","mailsubscriber",$row['id_mailsubscriber'],false);
			autoriser_exception("instituer","mailsubscriber",$row['id_mailsubscriber'],false);
		}
		
		//Notification de la désinscription
		if ($notifications = charger_fonction('notifications', 'inc', true)) {
				// To do choix des listes selon la config
				include_spip('inc/config');
				$options = array('config' => lire_config('newsletters_notifications'), 'email_inscription' => $row['email'],'type'=>'unsubscribe');
				$listes = $flux['args']['args'][0];
				if ($listes AND is_string($listes))
					$listes = explode(',', $listes);

				// Envoyer aux admins
				$notifications('inscription_newsletter', $listes, $options);
			}
		
	}

	// Dans tous les cas on finit sur un minipres qui dit si ok ou echec
	include_spip('inc/minipres');
	echo minipres($titre,"","",true);

}