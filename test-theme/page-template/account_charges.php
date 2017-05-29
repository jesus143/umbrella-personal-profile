<?php
function creditCard(){
	require_once(get_stylesheet_directory() . '/stripe-php/init.php');
	$stripe = array(
	  "secret_key"      => "sk_test_E01Kh96YOzRtkhD5wItn8CDd",
	  "publishable_key" => "pk_test_u289X2Do4OavHR2STjbI2TsL"
	);
	\Stripe\Stripe::setApiKey($stripe['secret_key']);
	
	$tokenID		= isset( $_POST[ 'stripeToken' ] ) ? $_POST[ 'stripeToken' ] : '';
	$amountStripe	= isset( $_POST[ 'topupAmount' ] ) ? $_POST[ 'topupAmount' ] : '';
	$current_user 	= wp_get_current_user();
	$customAPIKEY  	= get_field('custom_api_key','option');// name of the admin
	$customAPIID  	= get_field('custom_api_id','option');// Email Title for the admin
	$postargs 		= "http://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'".$current_user->user_email."'&searchNotes=true";
	//$postargs 		= "http://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'testing@umbrellasupport.co.uk'&searchNotes=true";
	$request		= "";
	$session 		= curl_init();
	curl_setopt ( $session, CURLOPT_RETURNTRANSFER, true );
	curl_setopt ( $session, CURLOPT_URL, $postargs );
	//curl_setopt ($session, CURLOPT_HEADER, true);
	curl_setopt ( $session, CURLOPT_HTTPHEADER, array(
	  'Api-Appid:'.$customAPIID,
	  'Api-Key:'.$customAPIKEY
	));
	$response = curl_exec( $session );   
	curl_close( $session );
	//header("Content-Type: text");
	//echo "CODE: " . $response;
	$getName 		= json_decode( $response ); 
	//php functionalities here
	$creditID	=$getName->data[0]->id;
	$stripAmount=$getName->data[0]->f1547;
	if(empty($creditID)){
	   echo 'Please register this email in ontraport to be processed!';
	}else{
		$customer 	= \Stripe\Customer::create(array(
		  'email' 	=> $current_user->user_email,
		  'source'  => $tokenID
		));
		 //var_dump($amountStripe);
		  $charge = \Stripe\Charge::create(array(
			  'customer' => $customer->id,
			  'amount'   => $amountStripe.'00',
			  'currency' => 'GBP' 
		  )); 
		$charge->source->brand;	
		$charge->source->funding;	
		$charge->source->last4;	
		$charge->source->exp_month;	
		$charge->source->exp_year;	
		$API_URL 		= 'http://api.ontraport.com/1/objects?'; //URL OF THE ONTRAPORT
		//DATA TO FETCH				  
		$API_DATA 	= array(
			'objectID'   => 0,
			'performAll' => 'true',
			'sortDir'  	=> 'asc',
			'condition'  => "email='".$current_user->user_email."'",
			'searchNotes'=> 'true'
		);
		//API DETAILS
		$API_KEY  	= $customAPIKEY;
		$API_ID  	= $customAPIID;

		//API RESULT
		$API_RESULT 	= op_query($API_URL, 'GET', $API_DATA, $API_ID, $API_KEY);
		$getName  		= json_decode($API_RESULT); //GET THE RESULT AND CONVERT TO JSON FORMAT

		$API_RESULT 	= null; //REASSIGN VALUE OF API RESULT TO NULL
		//API DATA TO PUT/UPDATE. NOTE: ObjectID must be 0 [0 = Contacts]; and id must be set and must be already registered in OntraPort, otherwise the result is fail.
		$brand=$charge->source->brand;
		switch($brand){
			case 'Visa':			$card = 795;break;
			case 'MasterCard':		$card = 796;break;
			case 'American Express':$card=805;break;
			default:				$card = "";	break;
		}
		$stripAmount	= ltrim ($stripAmount,'£');
		$amountStripe	= ltrim ($amountStripe,'£');
		$totalStripe	= ($amountStripe)+($stripAmount);
		$API_DATA 		= array(
			'objectID'   			=> 0,
			'id'  	 				=> $creditID,
			'CreditDebi_497'  		=> $card,
			'ccExpirationMonth'  	=> $charge->source->exp_month,
			'ccExpirationYear'  	=> $charge->source->exp_year,
			'f1547'  				=> '£'.$totalStripe,
			'ccNumber' 				=> $charge->source->last4 
		);			  
		//GET PUT RESULT
		$API_RESULT 	= op_query( $API_URL, 'PUT', $API_DATA, $API_ID, $API_KEY );
		//IF OBJECT NOT FOUND, DISPLAY ERROR MESSAGE
		if( $API_RESULT == "Object not found" ){
		echo 'FAILED TO PUT/UPDATE DATA!';
		die();
		}
		echo 'Succesfully top up!';
	}	
	die(); // this is required to return a proper result
}
add_action( 'wp_ajax_creditCard', 'creditCard' );
add_action( 'wp_ajax_nopriv_creditCard', 'creditCard' );
?>	