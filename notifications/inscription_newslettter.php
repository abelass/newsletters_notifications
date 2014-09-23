<?php
if (!defined("_ECRIRE_INC_VERSION")) return;


function notifications_inscription_newslettter_dist($quoi,$id_list, $options) {
    include_spip('inc/config');
    $config = lire_config('newsletters_notifications');

    $envoyer_mail = charger_fonction('envoyer_mail','inc');
    
    $options['$id_list']=$id_list; 
//    $options['qui']='vendeur';  
    $dest=(isset($config['vendeur_'.$config['vendeur']]) AND intval($config['vendeur_'.$config['vendeur']])) ?$config['vendeur_'.$config['vendeur']]:1;
    
    $sql=sql_select('email','spip_auteurs','id_auteur IN ('.implode(',',$dest).')');
    $email=array();
    while($data=sql_fetch($sql)){
        $email[]=$data['email'];        
        }

    $subject=_T('newsletters_notifications:une_inscription_sur',array('nom'=>$GLOBALS['meta']['nom_site']));   
  

    $message=recuperer_fond('notifications/contenu_inscription_newslettter_mail',$options);
     
    //
    // Envoyer les emails
    //
    //
    //

    $envoyer_mail($email,$subject,array(
        'html'=>$message)
       );
       
    // Si présent -  l'api de notifications_archive 
     if ($archiver = charger_fonction('archiver_notification','inc',true)) {
                $envoi='reussi';
                if(!$envoyer_mail)$envoi='echec';

            $o=array(
                'recipients'=>implode(',',$email),                         
                'sujet'=>$subject,
                'texte'=>$message,
                'html'=>'oui',
                'id_objet'=>$id_reservation,
                'objet'=>'reservation',
                'envoi'=>$envoi,
                'type'=>$quoi);
                     
        $archiver ($o);
    }

}

?>
