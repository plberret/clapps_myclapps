<?php

require_once '../api/mailchimp/MCAPI.class.php';
require_once '../api/mailchimp/config.inc.php'; //contains apikey

$api = new MCAPI($apikey);
$email=stripslashes($_POST['EMAIL']); 

$retval = $api->listSubscribe( $listId, $email, $merge_vars, $email_type='html', $double_optin=false, $update_existing=false, $replace_interests=true, $send_welcome=true );

if ($api->errorCode){
	switch ($api->errorCode) {
	    case 214:
	        echo "Cet email est déjà enregistré dans notre base.";
	        break;
	    default:
	       echo "Une erreur est survenue, veuillez réessayer. Merci de nous contacter si le problème persiste.";
	}
} else {
	displayMessage('good');
}

function displayMessage($response){
	echo json_encode($response);
}