<?php
/*
*Template Name:Billing
*/
get_header();

/** FETCHING PARAMATERS **/
/*
	--PARAMETER NAMES--
	add_charge
	account_number; 
	account_manager; 
	company_name; 

*/
	$current_user 	= wp_get_current_user();
	$customAPIKEY  = get_field('custom_api_key','option');// name of the admin
	$customAPIID  = get_field('custom_api_id','option');// Email Title for the admin
	//echo "Email Address: " . $current_user->user_email; 
	$postargs = "http://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'testing@umbrellasupport.co.uk'&searchNotes=true";
	//$postargs = "http://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'".$current_user->user_email."'&searchNotes=true";

	$session = curl_init();
	curl_setopt ($session, CURLOPT_RETURNTRANSFER, true);
	curl_setopt ($session, CURLOPT_URL, $postargs);
	//curl_setopt ($session, CURLOPT_HEADER, true);
	curl_setopt ($session, CURLOPT_HTTPHEADER, array(
	  'Api-Appid:'.$customAPIID,
	  'Api-Key:'.$customAPIKEY
	));
	$response = curl_exec($session); 
	curl_close($session);
	//header("Content-Type: text");
	//echo "CODE: " . $response;
	$getName = json_decode($response);  
	/*if( isset( $wp_query->query_vars['add_charge'] ) && 
		isset( $wp_query->query_vars['account_number'] ) &&
		isset( $wp_query->query_vars['account_manager'] ) &&
		isset( $wp_query->query_vars['company_name'] ) &&
		isset( $wp_query->query_vars['category_type'] ) 
		) { //Checks if these parameters are queried.
		
		$charge 	= urldecode($wp_query->query_vars['add_charge']);
		$account 	= urldecode($wp_query->query_vars['account_number']);
		$manager 	= urldecode($wp_query->query_vars['account_manager']);
		$company 	= urldecode($wp_query->query_vars['company_name']);
		$category 	= urldecode($wp_query->query_vars['category_type']);
		$billing_date= date('Y-m-d H:i:s');
	}*/
	if(	isset( $wp_query->query_vars['account_number'] )
		&& isset( $wp_query->query_vars['service_name'] ) 	
		&& isset( $wp_query->query_vars['category_type'] )
		&& isset( $wp_query->query_vars['add_charge'] )
		&& isset( $wp_query->query_vars['add_quantity'] )
 		&& isset( $wp_query->query_vars['description']) 
		){ //Checks if these parameters are queried.
		
		/*$charge 		= urldecode($wp_query->query_vars['add_charge']);
		$account 		= urldecode($wp_query->query_vars['account_number']);
		$manager 		= urldecode($wp_query->query_vars['account_manager']);
		$company 		= urldecode($wp_query->query_vars['company_name']);
		$category 		= urldecode($wp_query->query_vars['category_type']);*/
		$account 		= urldecode( $wp_query->query_vars['account_number'] );
		$service 		= urldecode( $wp_query->query_vars['service_name'] );
		$category 		= urldecode( $wp_query->query_vars['category_type'] );
		$charge 		= urldecode( $wp_query->query_vars['add_charge'] );
		$quantity 		= urldecode( $wp_query->query_vars['add_quantity'] );
		$description 	= urldecode( $wp_query->query_vars['description'] );
		$name			= $getName->data[0]->firstname.' '.$getName->data[0]->lastname;
		$company		= $getName->data[0]->company;
		$billing_date	= date('Y-m-d H:i:s');
	}
	
	?>
	<div id="page-content">
	
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function($){
				/*var fieldCharge = '<?php echo $charge;?>';
				var fieldAccount = '<?php echo $account;?>';
				var fieldManager = '<?php echo $manager;?>';
				var fieldCategory = '<?php echo $category;?>';
				var fieldCompany= '<?php echo $company;?>';
				var fieldDate= '<?php echo $billing_date;?>';*/
				var fieldName = '<?php 		  echo $name;?>';
				var fieldAccount = '<?php 	  echo $account;?>';
				var fieldService = '<?php 	  echo $service;?>';
				var fieldCategory = '<?php    echo $category;?>';				
				var fieldCharge = '<?php 	  echo $charge;?>';
				var fieldQuantity = '<?php 	  echo $quantity;?>';
				var fieldDescription = '<?php echo $description;?>';
				var fieldDate = '<?php		  echo $billing_date;?>';
				var fieldBalance = '<?php 	  echo $getName->data[0]->f1547; ?>';
				var fieldCompany = '<?php 	  echo $company;?>';
				var ajaxurl = '<?php 		  echo admin_url('admin-ajax.php'); ?>';
			    jQuery.ajax({
				   type: "POST", // HTTP method POST or GET
				   url:ajaxurl, //Where to make Ajax calls
				 //  dataType:"text", // Data type, HTML, json etc.
				   data:{
					   action: "addBilling",
					   fName:fieldName,
					   fAccount:fieldAccount,
					   fService:fieldService,
					   fCategory:fieldCategory,
					   fCharge:fieldCharge,
					   fQuantity:fieldQuantity,
					   fCompany:fieldCompany,
					   fDescription:fieldDescription,
					   fDate:fieldDate,
					   fBalance:fieldBalance
				   },
				   success:function(response){
					 //responseaction
					alert(response);
				   },
				   error:function (xhr, ajaxOptions, thrownError){
					alert(thrownError);
				   },
				});
		});	
	</script>
	
<?php get_footer();?>