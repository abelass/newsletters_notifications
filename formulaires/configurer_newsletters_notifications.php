
<?php



// Sécurité
if (!defined('_ECRIRE_INC_VERSION')) return;

function formulaires_configurer_newsletters_notifications_saisies_dist(){


	include_spip('inc/config');
	include_spip('inc/plugin');
	$config = lire_config('newsletters_notifications',array());


    //Les Lists disponibles
    $config_lists=lire_config('mailsubscribers/lists');
    $lists=array();
     foreach($config_lists AS $id_bak=>$titre){
         $lists[$id_bak]=$titre;
     }
     
	$choix_expediteurs = array(
			'webmaster' => _T('newsletters_notifications:notifications_expediteur_choix_webmaster'),
			'administrateur' => _T('newsletters_notifications:notifications_expediteur_choix_administrateur'),
			'email' => _T('newsletters_notifications:notifications_expediteur_choix_email')
	);
	
	if (defined('_DIR_PLUGIN_FACTEUR')){
		$choix_expediteurs['facteur'] = _T('newsletters_notifications:notifications_expediteur_choix_facteur');
	}

	return array(
		array(
			'saisie' => 'fieldset',
			'options' => array(
				'nom' => 'fieldset_parametres',
				'label' => _T('newsletters_notifications:choix_listes')
			),
            'saisies' => array(
                array(
                    'saisie' => 'selection_multiple',
                    'options' => array(
                        'nom' => 'lists',
                        'datas' => $lists,
                        'cacher_option_intro' => 'on',
                        'label' => _T('newsletters_notifications:label_choix_listes'),   
                        'explication' => _T('newsletters_notifications:explication_choix_listes'),                                                
                    )
                ),                                             
            )            
		),
		array(
			'saisie' => 'fieldset',
			'options' => array(
				'nom' => 'fieldset_notifications_parametres',
				'label' => _T('newsletters_notifications:notifications_parametres'),
			),
			'saisies' => array(			
				array(
					'saisie' => 'selection',
					'options' => array(
						'nom' => 'expediteur',
						'label' => _T('newsletters_notifications:notifications_expediteur_label'),
						'explication' => _T('newsletters_notifications:notifications_expediteur_explication'),
						'cacher_option_intro' => 'on',
						'defaut' => $config['expediteur'],
						'datas' => $choix_expediteurs
					)
				),
				
				array(
					'saisie' => 'auteurs',
					'options' => array(
						'nom' => 'expediteur_webmaster',
						'label' => _T('newsletters_notifications:notifications_expediteur_webmaster_label'),
						'statut' => '0minirezo',
						'cacher_option_intro' => "on",
						'webmestre' => 'oui',
						'defaut' => $config['expediteur_webmaster'],
						'afficher_si' => '@expediteur@ == "webmaster"',
					)
				),
				array(
					'saisie' => 'auteurs',
					'options' => array(
						'nom' => 'expediteur_administrateur',
						'label' => _T('newsletters_notifications:notifications_expediteur_administrateur_label'),
						'statut' => '0minirezo',
						'cacher_option_intro' => "on",
						'defaut' => $config['expediteur_administrateur'],
						'afficher_si' => '@expediteur@ == "administrateur"',
					)
				),
				array(
					'saisie' => 'input',
					'options' => array(
						'nom' => 'expediteur_email',
						'label' => _T('newsletters_notifications:notifications_expediteur_email_label'),
						'defaut' => $config['expediteur_email'],
						'afficher_si' => '@expediteur@ == "email"',
					)
				),
				array(
					'saisie' => 'selection',
					'options' => array(
						'nom' => 'vendeur',
						'label' => _T('reservation:notifications_vendeur_label'),
						'explication' => _T('reservation:notifications_vendeur_explication'),
						'cacher_option_intro' => 'on',
						'defaut' => $config['vendeur'],
						'datas' => array(
							'webmaster' => _T('reservation:notifications_vendeur_choix_webmaster'),
							'administrateur' => _T('reservation:notifications_vendeur_choix_administrateur'),
							'email' => _T('reservation:notifications_vendeur_choix_email')
						)
					)
				),
				array(
					'saisie' => 'auteurs',
					'options' => array(
						'nom' => 'vendeur_webmaster',
						'label' => _T('reservation:notifications_vendeur_webmaster_label'),
						'statut' => '0minirezo',
						'cacher_option_intro' => "on",
						'webmestre' => 'oui',
						'multiple' => 'oui',
						'defaut' => $config['vendeur_webmaster'],
						'afficher_si' => '@vendeur@ == "webmaster"',
					)
				),
				array(
					'saisie' => 'auteurs',
					'options' => array(
						'nom' => 'vendeur_administrateur',
						'label' => _T('reservation:notifications_vendeur_administrateur_label'),
						'statut' => '0minirezo',
						'multiple' => 'oui',
						'cacher_option_intro' => "on",
						'defaut' => $config['vendeur_administrateur'],
						'afficher_si' => '@vendeur@ == "administrateur"',
					)
				),
				
				array(
					'saisie' => 'input',
					'options' => array(
						'nom' => 'vendeur_email',
						'label' => _T('reservation:notifications_vendeur_email_label'),
						'explication' => _T('reservation:notifications_vendeur_email_explication'),
						'defaut' => $config['vendeur_email'],
						'afficher_si' => '@vendeur@ == "email"',
					)
				),
				array(
					'saisie' => 'oui_non',
					'options' => array(
						'nom' => 'client',
						'label' => _T('reservation:notifications_client_label'),
						'explication' => _T('reservation:notifications_client_explication'),
						'defaut' => $config['client'],
					)
				),
				array(
					'saisie' => 'selection_multiple',
					'options' => array(
						'nom' => 'envoi_separe',
						'label' => _T('reservation:notifications_envoi_separe'),
						'explication' => _T('reservation:notifications_envoi_separe_explication'),
						'cacher_option_intro' => 'on',
						'datas' => $statuts_selectionnees,
						'defaut' => $config['envoi_separe']
					)
				)															
			)
		)
	);
}

?>