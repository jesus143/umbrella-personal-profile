<?php
/*-----------------------------------------------------------------------------------*/
/* Options Framework Functions
/*-----------------------------------------------------------------------------------*/
/* Set the file path based on whether the Options Framework is in a parent theme or child theme */
	if ( STYLESHEETPATH == TEMPLATEPATH ) {
		define('OF_FILEPATH', TEMPLATEPATH);
		define('OF_DIRECTORY', get_bloginfo('template_directory'));
	} else {
		define('OF_FILEPATH', STYLESHEETPATH);
		define('OF_DIRECTORY', get_bloginfo('stylesheet_directory'));
	}
	
	/* These files build out the options interface.  Likely won't need to edit these. */
	require_once (OF_FILEPATH . '/admin/admin-functions.php');		// Custom functions and plugins
	require_once (OF_FILEPATH . '/admin/admin-interface.php');		// Admin Interfaces (options,framework, seo)
	include_once( OF_FILEPATH .'/facebook/registration.php' ); 
	include_once( OF_FILEPATH .'/facebook/src/Facebook/autoload.php' ); 
	//include_once( 'facebook/registration.php' ); 
	/* These files build out the theme specific options and associated functions. */
	require_once (OF_FILEPATH . '/admin/theme-options.php'); 		// Options panel settings and custom settings
	require_once (OF_FILEPATH . '/admin/theme-functions.php'); 	// Theme actions based on options settings

	if (function_exists('register_nav_menus')) {
		register_nav_menus(array('primary' => 'Header Navigation'));
	}
	
	wp_enqueue_script('jquery');
	
	function wp_new_excerpt($text)
	{
		if ($text == '')
		{
			$text = get_the_content('');
			$text = strip_shortcodes( $text );
			$text = apply_filters('the_content', $text);
			$text = str_replace(' ]]>', ' ]]>', $text);
			$text = strip_tags($text);
			$text = nl2br($text);
			$excerpt_length = apply_filters('excerpt_length', 55);
			$words = explode(' ', $text, $excerpt_length + 1);
			if (count($words) > $excerpt_length) {
				array_pop($words);
				array_push($words, '...');
				$text = implode(' ', $words);
			}
		}
		return $text;
	}
	remove_filter('get_the_excerpt', 'wp_trim_excerpt');
	add_filter('get_the_excerpt', 'wp_new_excerpt');

	//add support for featured images
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size(180);
	// API CALLS FOR ONTRAPORT
	function set_html_content_type( ) {
		return 'text/html';
	}
	function boot_session() {
	  session_start();
	}
	add_action('wp_loaded','boot_session');
	//Include your custom functions..
	include 'functions_lead.php';
	include 'chat-pages/functions_chat.php';
	function op_query($url, $method, $data, $appID, $appKey){
		$ch = curl_init( );
		switch ($method){
			case 'POST': {break;}
			case 'PUT': {
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen(json_encode($data)), 'Api-Appid:' . $appID, 'Api-Key:' . $appKey));
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);				
				break;
			}
			case 'GET': {
				$finalURL = $url . urldecode(http_build_query($data));
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt ($ch, CURLOPT_URL, $finalURL);
				curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Api-Appid:' . $appID, 'Api-Key:' . $appKey));
				break;
			}
		}
		$response  = curl_exec($ch);
		curl_close($ch);
		
		return $response;
	} 
	//GET DOMAIN NAME
	
	function get_domain($url)
	{
	  $pieces = parse_url($url);
	  $domain = isset($pieces[ 'host' ]) ? $pieces[ 'host' ] : '';
	  if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
		return $regs[ 'domain' ];
	  }
	  return false;
	}
// CUSTOM PARAMETER VARIABLE
	function add_query_vars($aVars) {
		$aVars[] = "accountid"; // represents the name of the product category as shown in the URL
		$aVars[] = "siteurl"; // represents the name of the product category as shown in the URL
		$aVars[] = "add_charge"; 
		$aVars[] = "account_number"; 
		$aVars[] = "account_manager"; 
		$aVars[] = "company_name"; 
		$aVars[] = "category_type"; 
		$aVars[] = "service_name"; 
		$aVars[] = "add_quantity"; 
		$aVars[] = "description"; 
		$aVars[] = "pdf_year";
		$aVars[] = "pdf_month";
		$aVars[] = "pdf_accounts";
		$aVars[] = "pdf_file";
		$aVars[] = "pdf_md5";
		$aVars[] = "partner_id";
		$aVars[] = "chat_id";
		$aVars[] = "hash_id";
		$aVars[] = "session_hash";
		$aVars[] = "file_type";
		$aVars[] = "partnerid";
		$aVars[] = "leadowner";
		$aVars[] = "tags";
		$aVars[] = "transactionid";
		$aVars[] = "agent";
		return $aVars;
	}
	// hook add_query_vars function into query_vars
	add_filter('query_vars', 'add_query_vars');
	function add_rewrite_rules($aRules) {
		$aNewRules = array(
						'widget-api/([^/]+)/?$' => 'index.php?pagename=widget-api&accountid=$matches[1]',
						'bill/([^/]+)/([^/]+)/([^/]+)/([^/]+)/([^/]+)/?$' => 'index.php?pagename=bill&add_charge=$matches[1]&account_number=$matches[2]&account_manager=$matches[3]&company_name=$matches[4]&category_type=$matches[5]',
						'bill/([^/]+)/([^/]+)/([^/]+)/([^/]+)/([^/]+)/([^/]+)/?$' => 'index.php?pagename=bill&account_number=$matches[1]&service_name=$matches[2]&category_type=$matches[3]&add_charge=$matches[4]&add_quantity=$matches[5]&description=$matches[6]',
						'create-newpdf/([^/]+)/([^/]+)/([^/]+)-([^/]+)/([^/]+)/?$' => 'index.php?pagename=create-newpdf&pdf_year=$matches[1]&pdf_month=$matches[2]&pdf_accounts=$matches[3]&pdf_md5=$matches[4]&pdf_file=$matches[5]',
						'chatpdf/([^/]+)/([^/]+)/([^/]+)/([^/]+)/([^/]+)/?$' => 'index.php?pagename=chatpdf&chat_id=$matches[1]&partner_id=$matches[2]&hash_id=$matches[3]&session_hash=$matches[4]&file_type=$matches[5]',
						'leads/([^/]+)/([^/]+)/([^/]+)/?$' => 'index.php?pagename=leads&partnerid=$matches[1]&leadowner=$matches[2]&agent=$matches[3]&tags=$matches[4]',
						'my-leads/([^/]+)/?$' => 'index.php?pagename=my-leads&transactionid=$matches[1]'						
					); 
		$aRules = $aNewRules + $aRules; 
		return $aRules;
	}
	// hook add_rewrite_rules function into rewrite_rules_array
	add_filter('rewrite_rules_array', 'add_rewrite_rules');
	/*function demo($mimes) {
		if ( function_exists( 'current_user_can' ) )
			$unfiltered = $user ? user_can( $user, 'unfiltered_html' ) : current_user_can( 'unfiltered_html' );
		if ( ! empty( $unfiltered ) ) {
			$mimes[ 'swf' ] = 'application/x-shockwave-flash';
		}
		return $mimes; 
	}
	add_filter( 'upload_mimes','demo' );*/ 
	function addBilling( ){
		global $wpdb; // this is how you get access to the database
		$fName		     = isset( $_POST[ 'fName' ] ) ? $_POST[ 'fName' ] : '';
		$fAccount 		 = isset( $_POST[ 'fAccount' ] ) ? $_POST[ 'fAccount' ] : '';
		$fService 		 = isset( $_POST[ 'fService' ] ) ? $_POST[ 'fService' ] : '';
		$fCategory 		 = isset( $_POST[ 'fCategory' ] ) ? $_POST[ 'fCategory' ] : '';	
		$fCharge 		 = isset( $_POST[ 'fCharge' ] ) ? $_POST[ 'fCharge' ] : '';
		$fQuantity 		 = isset( $_POST[ 'fQuantity' ] ) ? $_POST[ 'fQuantity' ] : '';
		$fDescription	 = isset( $_POST[ 'fDescription' ] ) ? $_POST[ 'fDescription' ] : '';
		$fAccountBalance = isset( $_POST[ 'fBalance' ] ) ? $_POST[ 'fBalance' ] : '';
		$fCompany 		 = isset( $_POST[ 'fCompany' ] ) ? $_POST[ 'fCompany' ] : '';
		$fDate 			 = isset( $_POST[ 'fDate' ] ) ? $_POST[ 'fDate' ] : '';
		$currency		 = $fCharge[0];
		switch( $currency ){
			case '£': $symbol = "POUND"; break;
			case '$': $symbol = "USD";	break;
			default : $symbol = "POUND"; break;
		}
		switch( $fService ){
			case 'Collection Sale' 				: $serviceSold="823"; break;
			case 'Ressubmission Charge' 		: $serviceSold="820"; break;
			case 'Standard Passport Package' 	: $serviceSold="711"; break;
			case 'Passport 6 Week Form' 		: $serviceSold="715"; break;
			case 'LS01 Passport Form' 			: $serviceSold="714"; break;
			case 'Done4You UK' 					: $serviceSold="712"; break;
			case 'Done4You International' 		: $serviceSold="713"; break;
			case 'Authorization Letter' 		: $serviceSold="220"; break;
			case 'Already Lodged Appointment' 	: $serviceSold="716"; break;
			default 							: $serviceSold=""; break;
		}
		$fCharge		= ltrim ( $fCharge,'£' );
		$fCharge		= ltrim ( $fCharge,'$' );
		$fCategory		= strtolower( $fCategory );
		$fAccountBalance= ltrim ( $fAccountBalance,'£' );
		$fDate			= strtotime( $fDate );
		if(
			empty($fCharge)
			|| empty( $fAccount )
			|| empty( $fService)
			|| empty( $fQuantity ) 
			|| empty( $fCategory ) 
			|| empty( $fCharge)
			|| empty( $fDescription )
		){
			echo 'Please give the right url!';
		}
		elseif( ! is_numeric( $fCharge ) ){
			echo 'Please give the amount credit/charges!';
		}
		elseif( ! is_numeric( $fQuantity ) ){
			echo 'Please give the quantity should not be string!';
		}
		else{
			if( $fCategory == 'credit' ){
				$totalBill=$fCharge*$fQuantity; 
				if( $wpdb->insert( 
					$wpdb->prefix.'billings',array(
					'tbl_account_number' 		=> $fAccount,
					'tbl_billing_name' 			=> $fName,
					'tbl_services'				=> $fService ,
					'tbl_payment_type'			=> $fCategory,				
					'tbl_company'				=> $fCompany,
					'tbl_credit'		 		=> $fCharge,
					'tbl_charge'		 		=> 0,
					'tbl_quantity'		 		=> $fQuantity ,
					'tbl_billing_description'	=> $fDescription ,
					'tbl_currency'		 		=> $symbol,
					'tbl_total'		 			=> $totalBill,
					'tbl_account_balance'		=> $fAccountBalance+$fCharge,
					'tbl_billing_date'	 		=> $fDate
					//'tbl_currency'
					),array(
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%.2f',
					'%.2f',
					'%.2f',
					'%s',
					'%s',
					'%.2f',
					'%.2f',
					'%s')
					) ===FALSE ){
						echo $return->get_error_message( );
					}
				else {
					  echo ' Successfully saved!';
					  $API_URL 		= 'http://api.ontraport.com/1/objects?'; //URL OF THE ONTRAPORT
					  //DATA TO FETCH				  
					  $API_DATA 	= array(
					   'objectID'   => 0,
					   'performAll' => 'true',
					   'sortDir'  	=> 'asc',
					  // 'condition'  => "email='".$current_user->user_email."'",
					   'condition'  => "email='testing@umbrellasupport.co.uk'",
					   'searchNotes'=> 'true'
					  );
					  //API DETAILS
					  $API_KEY  = 'Kiok5B2tzM00Oqf';
					  $API_ID  	= '2_7818_ubHppKG8C';
					  
					  //API RESULT
					  $API_RESULT 	= op_query($API_URL, 'GET', $API_DATA, $API_ID, $API_KEY);
					  $getName  	= json_decode($API_RESULT); //GET THE RESULT AND CONVERT TO JSON FORMAT
					  
					  $API_RESULT 	= null; //REASSIGN VALUE OF API RESULT TO NULL
					  
					  //API DATA TO PUT/UPDATE. NOTE: ObjectID must be 0 [0 = Contacts]; and id must be set and must be already registered in OntraPort, otherwise the result is fail.
					  $API_DATA 		= array(
					   'objectID'   	=> 0,
					   'id'  	 		=> $fAccount,
					   'f1547' 			=> '£'.($fAccountBalance+$totalBill),
					   'ServiceAge_499' => $serviceSold
					  // 'firstname'  => 'Marvin' //Set firstname to new value. You can add more fields by adding more here. Not just firstname but all fields except the id.
					  );				  
					  //GET PUT RESULT
					  $API_RESULT 	= op_query( $API_URL, 'PUT', $API_DATA, $API_ID, $API_KEY );				  
					  //IF OBJECT NOT FOUND, DISPLAY ERROR MESSAGE
					  if( $API_RESULT == "Object not found" ){
					   echo 'FAILED TO PUT/UPDATE DATA!';
					   die( );
					  }
				  
				}
			}
			else{
				$totalBill=$fCharge*$fQuantity;
				if( $wpdb->insert(
					$wpdb->prefix.'billings',array(
					'tbl_account_number' 		=> $fAccount,
					'tbl_billing_name' 			=> $fName,
					'tbl_services'				=> $fService ,
					'tbl_payment_type'			=> $fCategory,				
					'tbl_company'				=> $fCompany,
					'tbl_credit'		 		=> 0,
					'tbl_charge'		 		=> $fCharge,
					'tbl_quantity'		 		=> $fQuantity ,
					'tbl_billing_description'	=> $fDescription ,
					'tbl_currency'		 		=> $symbol,
					'tbl_total'		 			=> $totalBill,
					'tbl_account_balance'		=> $fAccountBalance-$fCharge,
					'tbl_billing_date'			=> $fDate
					//'tbl_currency'
					),array(
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%.2f',
					'%.2f',
					'%.2f',
					'%s',
					'%s',
					'%.2f',
					'%.2f',
					'%s')
					) ===FALSE ){
					  echo $return->get_error_message( );
				}
				else {
				   $totalBill=$fCharge*$fQuantity;
				   echo ' Successfully saved!';
					  $API_URL 		= 'http://api.ontraport.com/1/objects?'; //URL OF THE ONTRAPORT
					  //DATA TO FETCH				  
					  $API_DATA 	= array(
					   'objectID'   => 0,
					   'performAll' => 'true',
					   'sortDir'    => 'asc',
					  // 'condition'  => "email='".$current_user->user_email."'",
					   'condition'  => "email='testing@umbrellasupport.co.uk'",
					   'searchNotes'=> 'true'
					  );
					  //API DETAILS
					  $API_KEY 	 	= 'Kiok5B2tzM00Oqf';
					  $API_ID  		= '2_7818_ubHppKG8C';
					  //API RESULT
					  $API_RESULT 	= op_query( $API_URL, 'GET', $API_DATA, $API_ID, $API_KEY );
					  $getName  	= json_decode( $API_RESULT ); //GET THE RESULT AND CONVERT TO JSON FORMAT
					  
					  $API_RESULT 	= null; //REASSIGN VALUE OF API RESULT TO NULL
					  
					  //API DATA TO PUT/UPDATE. NOTE: ObjectID must be 0 [0 = Contacts]; and id must be set and must be already registered in OntraPort, otherwise the result is fail.
					  $API_DATA 	= array(
					   'objectID'   	=> 0,
					   'id'  	 		=> $fAccount,
					   'f1547' 			=> '£'.($fAccountBalance-$totalBill),
					   'ServiceAge_499' => $serviceSold 
					  // 'firstname'  => 'Marvin' //Set firstname to new value. You can add more fields by adding more here. Not just firstname but all fields except the id.
					  );				  
					  //GET PUT RESULT
					  $API_RESULT 	= op_query($API_URL, 'PUT', $API_DATA, $API_ID, $API_KEY);				  
					  //IF OBJECT NOT FOUND, DISPLAY ERROR MESSAGE
					  if( $API_RESULT == "Object not found" ){
					   echo 'FAILED TO PUT/UPDATE DATA!';
					   die( );
					  }
				}
			}
		}
		die( ); // this is required to return a proper result
	}
	add_action( 'wp_ajax_addBilling', 'addBilling' );
	/**
	 * Add cutom field to registration form
	 */
	add_action( 'show_user_profile', 'extra_user_profile_fields' );
	add_action( 'edit_user_profile', 'extra_user_profile_fields' );
	/*function extra_user_profile_fields( $user ) { ?>
		<h3><?php _e("Level", "blank"); ?></h3>
		<table class="form-table">
		<tr>
		<th><label for="level"><?php _e("User Level"); ?></label></th>
		<td>
		<input type="text" name="level" id="level" placeholder="Please enter level" value="<?php echo esc_attr( get_the_author_meta( 'level', $user->ID ) ); ?>" class="regular-text" /><br />
		<span class="description"><?php _e("Please enter level 1-5."); ?></span>
		</td>
		</tr>
		</table>
	<?php }*/
	add_action( 'personal_options_update', 'save_extra_user_profile_fields' );
	add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields' );
	function save_extra_user_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }
	update_user_meta( $user_id, 'level', $_POST[ 'level' ] );
	}

	add_action('wp_footer', 'customized_script');

	function customized_script( ) {
		wp_enqueue_script('ajax-scripts',get_stylesheet_directory_uri( ).'/js/ajax.js', array('jquery'));
	}

	if( function_exists( 'acf_add_options_page' ) ) {
		
		$parentCustomSettings = acf_add_options_page(array(
			'page_title' 	=> 'General Custom Settings',
			'menu_title' 	=> 'Custom Settings',
			'icon_url' 		=> 'dashicons-admin-settings',
			'redirect' 		=> false
		));
		
		acf_add_options_sub_page(array(
			'page_title' 	=> 'Live 121 Chat Settings',
			'menu_title' 	=> 'Live Chat',
			'parent_slug' 	=> $parentCustomSettings['menu_slug']
		));
		
		acf_add_options_sub_page(array(
			'page_title' 	=> 'OntraPort API Settings',
			'menu_title' 	=> 'Ontraport API Keys',
			'parent_slug' 	=> $parentCustomSettings['menu_slug']
		));
		
		$parentCustomContent = acf_add_options_page(array(
			'page_title' 	=> 'General Custom Contents',
			'menu_title' 	=> 'Custom Contents',
			'icon_url' 		=> 'dashicons-align-center',
			'redirect' 		=> false
		));
		
		acf_add_options_sub_page(array(
			'page_title' 	=> 'Top-up Page',
			'menu_title' 	=> 'Top-up Page',
			'parent_slug' 	=> $parentCustomContent['menu_slug']
		));
		
		acf_add_options_sub_page(array(
			'page_title' 	=> 'Transcripts Page',
			'menu_title' 	=> 'Transcripts Page',
			'parent_slug' 	=> $parentCustomContent['menu_slug']
		));
		
		acf_add_options_sub_page(array(
			'page_title' 	=> 'Transcripts Page',
			'menu_title' 	=> 'Transcripts Page',
			'parent_slug' 	=> $parentCustomContent['menu_slug']
		));
		
		acf_add_options_sub_page(array(
			'page_title' 	=> 'Live 121 Chat Email Contents',
			'menu_title' 	=> 'Chat Email Script',
			'parent_slug' 	=> $parentCustomContent['menu_slug']
		));
	}
	add_filter( 'acf/fields/wysiwyg/toolbars' , 'my_toolbars'  );
	function my_toolbars( $toolbars ) {
		array_unshift( $toolbars[ 'Full' ][1], 'forecolor' );
		return $toolbars;
	}
	function user_name( ){
		ob_start( );
		global $FULLNAME;
		//php functionalities here
		echo $FULLNAME;
		return ob_get_clean( );
	}
	add_shortcode('NAME_USER','user_name');// shorcode to be add
	
	
	
	function generated_scripts( ){
		ob_start( );
		//php functionalities here
		global $FILECONTS;
		echo $FILECONTS;
		return ob_get_clean( );
	}
	add_shortcode('EMBED_SCRIPT','generated_scripts');// shorcode to be add
	add_action( 'init', 'register_posts_types' );
	/*Register post type -----------------------------------
	--------------------------------------------------------*/
	function register_posts_types( ) {
		global $theme_domain;
		// register posttype for dentist
		register_post_type( 'client',
			array(
				'labels' => array(
					'name' 					=> __( 'Client Profile' ),
					'singular_name' 		=> __( 'Profile' ),
					'add_new' 				=> __( 'Add New' ),
					'add_new_item' 			=> __( 'Add New Profile' ),
					'edit_item' 			=> __( 'Edit Profile' ),
					'new_item' 				=> __( 'Add New Profile' ),
					'view_item' 			=> __( 'View Profile' ),
					'search_items' 			=> __( 'Search Profile' ),
					'not_found' 			=> __( 'No Profile found' ),
					'not_found_in_trash' 	=> __( 'No Profile found in trash' )
				),
				'public' 				=> true,
				'supports' 				=> array('thumbnail' ),
				'hierarchical' 			=> true,
				'rewrite' 				=> array( "slug" => "client" ),
				'menu_position' 		=> 20,
				'menu_icon'				=> 'dashicons-groups',
			)
		);
	}
	function registrationRequest( ){
		global $wpdb,$password; // this is how you get access to the database
		$host  		= "db640728737.db.1and1.com";
		$database   = "db640728737";
		$user  		= "dbo640728737";
		$password   = "1qazxsw2!QAZXSW@";
		try{
		$WP_CON	= new PDO('mysql:host='.$host.';dbname='.$database.';', $user, $password);
		$WP_CON->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $ERR){
			echo $ERR->getMessage();
			exit();
		}
		$email		= isset( $_POST[ 'email' ] ) ? $_POST[ 'email' ] : '';
		$password   = isset( $_POST[ 'password' ] ) ? $_POST[ 'password' ] : '';
		$customAPIKEY  = get_field('custom_api_key','option');// name of the admin
		$customAPIID  = get_field('custom_api_id','option');// Email Title for the admin
		$postargs 	= "http://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'".$email."'&searchNotes=true";
		//$postargs 		= "http://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'testing@umbrellasupport.co.uk'&searchNotes=true";
		$request		= "";
		$session 		= curl_init( );
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
		$acount_id		=$getName->data[0]->id;
		$sendID  	 	= $getName->data[0]->id;
		$sendName 	 	= $getName->data[0]->firstname.' '.$getName->data[0]->lastname;
		$sendEmail   	= $getName->data[0]->email;
		$sendLevel   	= $getName->data[0]->f1549;
		$sendAmount  	= $getName->data[0]->f1547;
		$sendCity    	= $getName->data[0]->city;
		$sendAddress 	= $getName->data[0]->address;
		$sendCountry    = $getName->data[0]->County_456;
		$sendTown    	= $getName->data[0]->Town_340;
		$sendZip     	= $getName->data[0]->zip;
		$sendBemail  	= $getName->data[0]->f1556;
		$sendCphone  	= $getName->data[0]->cell_phone;
		$sendHphone  	= $getName->data[0]->home_phone;
		$sendCountry  	= $getName->data[0]->country;
		$sendAddress2   = $getName->data[0]->address2;
		$sendOphone     = $getName->data[0]->office_phone;
		$sendState      = $getName->data[0]->state;
		$sendCompany    = $getName->data[0]->company;
		$sendCompanynum = $getName->data[0]->f1564;
		$sendwebsite	= $getName->data[0]->website;
		$sendAgentID    = $getName->data[0]->CallAgent_462;
		$sendManager 	= $sendName;
		$packagelower   = $getName->data[0]->f1548;
		$firstUpper		= strtolower($packagelower);
		$sendpackage	= ucfirst($firstUpper);
		if(!empty($sendpackage)){
			$sendPackagedata=$sendpackage;
		}
		else{
			$sendPackagedata='Standard';
		}
		$sendAmounts  = substr($sendAmount, 1);
		switch($sendAgentID){
			case 941: $sendAgent="Paul Diu"; break;
			case 791: $sendAgent="Not Known"; break;
			case 818: $sendAgent="Edward Pink"; break;
			case 817: $sendAgent="Dave Knowles"; break;
			case 790: $sendAgent="Katie Smith"; break;
			case 773: $sendAgent="Jeff Ramsay"; break;
			case 741: $sendAgent="Arthur Orin"; break;
			case 740: $sendAgent="Franz Kafka"; break;
			case 816: $sendAgent="Sabrina Ali"; break;
			default: $sendAgent="";break;
		}
		if(empty($acount_id)){
			echo '<p class="error-message"><b>✘</b>Email not found in ontraport!</p>';
		}
		elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			echo '<p class="error-message"><b>✘</b>Please enter the right email!</p>';
		}
		elseif(empty($password)){
			echo '<p class="error-message"><b>✘</b>Please enter a password!</p>';
		}
		else{
			if(email_exists($email)){
				global  $user_id;
				$login =$email;
				if(is_email( $login )){
					if( email_exists( $login )) {
					  $userID__ = email_exists($login);
					  $user_info = get_userdata($userID__);
					  $user_id  = $user_info->ID;
					 // var_dump($login);
					}
					$user = get_user_by( 'login', $email );
					if ( $user && wp_check_password( $password, $user->data->user_pass, $user->ID) ){
						$creds = array( );
						$creds[ 'user_login' ] = $email ;
						$creds[ 'user_password' ] = $password;
					   // $creds[ 'remember' ] = true;
						$user = wp_signon( $creds, false );
						if ( is_wp_error($user) ){
							echo $user->get_error_message( );
							}
							$update_post = array(
							'post_title'    => $sendName,
							'post_status'   => 'publish',          
							'post_type'     => 'client' 
							);
							$postId = wp_update_post($update_post);
							switch($sendLevel){
							  case 1		: $level='Level 1'; break;
							  case 2		: $level='Level 2'; break;
							  case 3		: $level='Level 3'; break;
							  case 4		: $level='Level 4'; break;
							  case 5		: $level='Level 5'; break;
							  default		: $level='Level 1'; break;
							}
							update_post_meta( $postId, 'full_name', $sendName);
							update_post_meta( $postId, 'address_line_1', $sendAddress);
							update_post_meta( $postId, 'city', $sendCity);
							update_post_meta( $postId, 'postcode', $sendZip);
							update_post_meta( $postId, 'town', $sendTown );
							update_post_meta( $postId, 'country', $sendCountry );
							update_post_meta( $postId, 'home_phone', $sendHphone);
							update_post_meta( $postId, 'mobile_phone', $sendCphone);
							update_post_meta( $postId, 'company_address_line_1', $sendAddress);
							update_post_meta( $postId, 'company_address_line_2', $sendAddress); 
							update_post_meta( $postId, 'company_city', $sendCity );				
							update_post_meta( $postId, 'company_town', $sendTown);							
							update_post_meta( $postId, 'company_postcode', $sendZip);
							update_post_meta( $postId, 'company_name', $sendCompany);
							update_post_meta( $postId, 'company_number', $sendCompanynum);
							update_post_meta( $postId, 'company_website', $sendwebsite);
							update_post_meta( $postId, 'company_office_phone', $sendOphone);
							update_post_meta( $postId, 'company_mobile_phone', $sendCphone);
							update_post_meta( $postId, 'package_level', $level);
							update_post_meta( $postId, 'account_manager', $sendAgent);
							update_post_meta( $postId, 'account_balance', $sendAmount);
							update_post_meta( $postId, 'company_business_email', $sendBemail);
							update_post_meta( $postId, 'package', $sendPackagedata);
							$sql = $WP_CON->prepare('SELECT COUNT(*) AS total FROM wp_user_profiles_mirror WHERE partner_id = :partnerID');
							$sql->execute(array(':partnerID' => $acount_id));
							$result = $sql->fetchObject();
							if($result->total > 0) {
								//$status=0;
							   // echo 'Hello';
								try {
								$sql = "UPDATE wp_user_profiles_mirror   
										   SET full_name  = :fullName,  
										       home_phone = :homePhone, 
										       mobile_phone = :mobilePhone, 
										       postcode = :postCode, 
										       company_name = :companyName 
										 WHERE partner_id = :user_id
									  ";
										
								 $statement = $WP_CON->prepare($sql);
								 $statement->bindValue(":user_id", $acount_id);
								 $statement->bindValue(":fullName", $sendName);
								 $statement->bindValue(":homePhone", $sendHphone);
								 $statement->bindValue(":mobilePhone", $sendCphone);
								 $statement->bindValue(":postCode", $sendZip);
								 $statement->bindValue(":companyName", $sendCompany);
								 //$statement->bindValue(":status", $status);
								 $count = $statement->execute();

								 // $conn = null;        // Disconnect
								  //echo 'Updated';
								}
								catch(PDOException $e) {
								  echo $e->getMessage();
								}
							}
							try{
							$sqlImage='SELECT COUNT(*) AS totalImage FROM wp_user_imguploads WHERE uid_PartnerID = :imageUp';
							$sqlImageup = $WP_CON->prepare($sqlImage);
							$sqlImageup->execute(array(':imageUp' => $acount_id));
							$resultImage = $sqlImageup->fetchObject();
							}catch(PDOException $ERR){
								echo $ERR->getMessage();
								exit();
							}
							if($resultImage->totalImage >0){
							}else{
								$statusImage=0;
								$urlMerror=get_home_url();
								$urlImage=get_stylesheet_directory_uri().'/images/default-logo.jpg';
								try {
									$sql = "INSERT INTO wp_user_imguploads(ui_HOST,
												ui_PATH,
												ui_URL,
												uid_PartnerID,
												ui_DATEUPLOAD) VALUES (
												:uiHOST, 
												:uiPATH, 
												:uiURL, 
												:uidPartnerID,
												:uiDATEUPLOAD)";
																			 
									$stmt = $WP_CON->prepare($sql);			 
									$stmt->bindParam(':uiHOST', $urlMerror, PDO::PARAM_STR);		
									$stmt->bindParam(':uiPATH', $urlMerror, PDO::PARAM_STR);		
									$stmt->bindParam(':uiURL', $urlImage, PDO::PARAM_STR);	
									$stmt->bindParam(':uidPartnerID', $acount_id, PDO::PARAM_STR);
									$stmt->bindParam(':uiDATEUPLOAD', date ("Y-m-d H:i:s"), PDO::PARAM_STR);
									// use PARAM_STR although a number										 
									$stmt->execute();
									}catch(PDOException $err){
									echo "Error: " . $err->getMessage();
									}
								//$WP_CON = null;
							}
							try{
							$sqlUser='SELECT COUNT(*) AS totalUser FROM users WHERE customer_id = :userUp';
							$sqlUserup = $WP_CON->prepare($sqlUser);
							$sqlUserup->execute(array(':userUp' => $acount_id));
							$resultUser = $sqlUserup->fetchObject();
							}catch(PDOException $ERR){
								echo $ERR->getMessage();
								exit();
							}
							if($resultUser->totalUser >0){
							}else{
								$merchant="Merchant";
								$userAllow = 'Yes';
								try {
									$saveUser = "INSERT INTO users(email,
												password,
												user_type,
												customer_id,
												allow_remarketing) VALUES (
												:userEmail, 
												:userPassword, 
												:userType, 
												:userID, 
												:userAllow)";							 
									$stmtUser = $WP_CON->prepare($saveUser);			 
									$stmtUser->bindParam(':userEmail', $sendEmail, PDO::PARAM_STR);		
									$stmtUser->bindParam(':userPassword', md5($password), PDO::PARAM_STR);		
									$stmtUser->bindParam(':userType', $merchant, PDO::PARAM_STR);	
									$stmtUser->bindParam(':userID', $acount_id, PDO::PARAM_STR);
									$stmtUser->bindParam(':userAllow', $userAllow, PDO::PARAM_STR);
									// use PARAM_STR although a number										 
									$stmtUser->execute();			
								}catch(PDOException $err){
									echo "Error: " . $err->getMessage();
								}
								//$WP_CON = null;
							}
							echo '<div class="message-success">';
							echo '<div style="position:relative;">';
							echo '<div style="text-align:left" class="success-message-right">';
							echo '<img src="'.get_template_directory_uri( ).'/images/Loading-Circle-Large-Red.gif">';	
							echo '</div>';
							echo '<div class="success-message-left">';
							echo '<h2><img alt="umbrella support centre" src="'.get_template_directory_uri().'/images/Umbrella-logo.png"></h2>';
							echo '<h3 >You have Successfully logged into Your Account.</h3>';
							echo '<h3 >Please wait for a few seconds...</h3>';
							//echo '<p>!</p>';
							echo '</div>';
							echo '</div>';
							echo '</div>';
					}else{
					   echo '<p class="error-message"><b>✘</b>Login failed!</p>';
					}
				}
			}else{
				if(empty($sendCompany)){
					 $sendCompanyTitle=$sendName.'-No Company Name';
				}else{
					 $sendCompanyTitle=$sendCompany;
				}
				$logo_post = array(
				'post_title'    => $sendCompanyTitle,
				'post_status'   => 'publish',          
				'post_type'     => 'logo' 
				);
				$status_logo="Pending";
				//insert the the post into database by passing $new_post to wp_insert_post
				//store our post ID in a variable $pid
				$logoId = wp_insert_post($logo_post);
				add_post_meta( $logoId, 'logo_partner_id', $sendID, true );
				add_post_meta( $logoId, 'status', $status_logo, true );
				//the array of arguements to be inserted with wp_insert_post
				$new_post = array(
				'post_title'    => $sendName,
				'post_status'   => 'publish',          
				'post_type'     => 'client' 
				);
				//insert the the post into database by passing $new_post to wp_insert_post
				//store our post ID in a variable $pid
				$pid = wp_insert_post($new_post);
				 switch($sendLevel){
				  case 1		: $level='Level 1'; break;
				  case 2		: $level='Level 2'; break;
				  case 3		: $level='Level 3'; break;
				  case 4		: $level='Level 4'; break;
				  case 5		: $level='Level 5'; break;
				  default		: $level='Level 1'; break;
				}
				//we now use $pid (post id) to help add out post meta data
				add_post_meta( $pid, 'full_name', $sendName, true );
				add_post_meta( $pid, 'email_address', $sendEmail, true );				
				add_post_meta( $pid, 'town', $sendTown, true );														
				add_post_meta( $pid, 'city', $sendCity, true );
				add_post_meta( $pid, 'country', $sendCountry, true );
				add_post_meta( $pid, 'postcode',$sendZip, true );
				add_post_meta( $pid, 'mobile_phone',$sendCphone, true );
				add_post_meta( $pid, 'home_phone',$sendHphone, true );				
				add_post_meta( $pid, 'company_city',$sendCity, true );
				add_post_meta( $pid, 'company_town',$sendTown, true );
				add_post_meta( $pid, 'company_name',$sendCompany, true );
				add_post_meta( $pid, 'company_website', $sendwebsite, true );
				add_post_meta( $pid, 'company_number', $sendCompanynum, true );
				add_post_meta( $pid, 'company_postcode',$sendZip, true );
				add_post_meta( $pid, 'company_business_email',$sendBemail, true );				
				add_post_meta( $pid, 'company_office_phone',$sendOphone, true );
				add_post_meta( $pid, 'company_mobile_phone',$sendCphone, true );
				add_post_meta( $pid, 'account_manager', $sendAgent, true);
				add_post_meta( $pid, 'package_level', $level, true );
				add_post_meta( $pid, 'partner_id', $sendID, true );	
				add_post_meta( $pid, 'account_balance', $sendAmount, true );
				add_post_meta( $pid, 'package', $sendPackagedata, true );
				$sql = $WP_CON->prepare('SELECT COUNT(*) AS total FROM wp_user_profiles_mirror WHERE partner_id = :partnerID');
				$sql->execute(array(':partnerID' => $acount_id));
				$result = $sql->fetchObject();
				if($result->total > 0) {
				}else{
					try {
						//$status=0;
						$sql = "INSERT INTO wp_user_profiles_mirror (partner_id, full_name, email_address,home_phone,mobile_phone,postcode,company_name,company_postcode,status)
						VALUES ('".$sendID."','".$sendName."', '".$sendEmail."', '".$sendHphone."','".$sendCphone."', '".$sendZip."', '".$sendCompany."', '".$sendZip."','".$status."')";
						// use exec() because no results are returned
						$WP_CON->exec($sql);
						//echo "New record created successfully";
					}catch(PDOException $e){
						echo $sql . "<br>" . $e->getMessage();
					}
					//$WP_CON = null;
				}
				try{
				$sqlImage='SELECT COUNT(*) AS totalImage FROM wp_user_imguploads WHERE uid_PartnerID = :imageUp';
				$sqlImageup = $WP_CON->prepare($sqlImage);
				$sqlImageup->execute(array(':imageUp' => $acount_id));
				$resultImage = $sqlImageup->fetchObject();
				}catch(PDOException $ERR){
					echo $ERR->getMessage();
					exit();
				}
				if($resultImage->totalImage >0){
				}else{
					$statusImage=0;
					$urlMerror=get_home_url();
					$urlImage=get_stylesheet_directory_uri().'/images/default-logo.jpg';
					try {
						$sql = "INSERT INTO wp_user_imguploads(ui_HOST,
									ui_PATH,
									ui_URL,
									uid_PartnerID,
									ui_DATEUPLOAD) VALUES (
									:uiHOST, 
									:uiPATH, 
									:uiURL, 
									:uidPartnerID,
									:uiDATEUPLOAD)";
																 
						$stmt = $WP_CON->prepare($sql);			 
						$stmt->bindParam(':uiHOST', $urlMerror, PDO::PARAM_STR);		
						$stmt->bindParam(':uiPATH', $urlMerror, PDO::PARAM_STR);		
						$stmt->bindParam(':uiURL', $urlImage, PDO::PARAM_STR);	
						$stmt->bindParam(':uidPartnerID', $acount_id, PDO::PARAM_STR);
						$stmt->bindParam(':uiDATEUPLOAD', date ("Y-m-d H:i:s"), PDO::PARAM_STR);
						// use PARAM_STR although a number										 
						$stmt->execute();
						}catch(PDOException $err){
						echo "Error: " . $err->getMessage();
						}
					//$WP_CON = null;
				}
				try{
				$sqlUser='SELECT COUNT(*) AS totalUser FROM users WHERE customer_id = :userUp';
				$sqlUserup = $WP_CON->prepare($sqlUser);
				$sqlUserup->execute(array(':userUp' => $acount_id));
				$resultUser = $sqlUserup->fetchObject();
				}catch(PDOException $ERR){
					echo $ERR->getMessage();
					exit();
				}
				if($resultUser->totalUser >0){
				}else{
					$merchant="Merchant";
					$userAllow = 'Yes';
					try {
						$saveUser = "INSERT INTO users(email,
									password,
									user_type,
									customer_id,
									allow_remarketing) VALUES (
									:userEmail, 
									:userPassword, 
									:userType, 
									:userID, 
									:userAllow)";							 
						$stmtUser = $WP_CON->prepare($saveUser);			 
						$stmtUser->bindParam(':userEmail', $sendEmail, PDO::PARAM_STR);		
						$stmtUser->bindParam(':userPassword', md5($password), PDO::PARAM_STR);		
						$stmtUser->bindParam(':userType', $merchant, PDO::PARAM_STR);	
						$stmtUser->bindParam(':userID', $acount_id, PDO::PARAM_STR);
						$stmtUser->bindParam(':userAllow', $userAllow, PDO::PARAM_STR);
						// use PARAM_STR although a number										 
						$stmtUser->execute();								 
						}catch(PDOException $err){
						echo "Error: " . $err->getMessage();
						}
					//$WP_CON = null;
				}
				$userargs = array(
				'first_name'	 => $getName->data[0]->firstname,
				'last_name'		 => $getName->data[0]->lastname,
				'user_login' 	 => $sendEmail,
				'nickname'		 => $sendName,
				'user_email'	 => $sendEmail,
				'user_pass' 	 => $password,
				'display_name'	 => $sendName,
				'role'			 => 'subscriber'
				);
				
				$user_id=wp_insert_user($userargs);
				$current_user = get_user_by( 'id', $user_id );
				// set the WP login cookie
				wp_set_auth_cookie( $user_id, false, is_ssl( ) );
				echo '<div class="message-success">';
				echo '<div style="position:relative;">';
				echo '<div style="text-align:left" class="success-message-right">';
				echo '<img src="'.get_template_directory_uri( ).'/images/Loading-Circle-Large-Red.gif">';	
				echo '</div>';
				echo '<div class="success-message-left">';
				echo '<h2><img alt="umbrella support centre" src="'.get_template_directory_uri().'/images/Umbrella-logo.png"></h2>';
				echo '<h3 >You have Successfully logged into Your Account.</h3>';
				echo '<h3 >Please wait for a few seconds...</h3>';
				//echo '<p>!</p>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}
		}
		die( ); // this is required to return a proper result
	}
	add_action( 'wp_ajax_registrationRequest', 'registrationRequest' );
	add_action( 'wp_ajax_nopriv_registrationRequest', 'registrationRequest' );
	function businessProfileProcess( ){
		global $wpdb,$WP_CON,$pID,$host,$database,$password;
		//=========Database connection=========
		$host  		= "db640728737.db.1and1.com";
		$database   = "db640728737";
		$user  		= "dbo640728737";
		$password   = "1qazxsw2!QAZXSW@";
		try{
		$WP_CON	= new PDO('mysql:host='.$host.';dbname='.$database.';', $user, $password);
		$WP_CON->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$WP_CON->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
		}catch(PDOException $ERR){
			echo $ERR->getMessage();
			exit();
		}
		try{
			$QUESTRING_GETNAIMG = "SELECT * FROM wp_user_imguploads";
			$GETNAIMG_RESULT	= $WP_CON->query($QUESTRING_GETNAIMG);
			$GETNAIMG_LISTS		= $GETNAIMG_RESULT->fetch();
		}catch(PDOException $ERR){
			echo $ERR->getMessage();
			exit();
		}
		//=====================================
		$uploadpath			= OF_FILEPATH . '/uploads/';
		//$email				= isset( $_POST[ 'post_id' ] ) ? $_POST[ 'post_id' ] : '';
		$name				= isset( $_POST[ 'fullName' ] ) ? $_POST[ 'fullName' ] : '';
		$bpaddressone		= isset( $_POST[ 'bpaddressone' ] ) ? $_POST[ 'bpaddressone' ] : '';
		$pID				= isset( $_POST[ 'businessID' ] ) ? $_POST[ 'businessID' ] : '';
		$bpaddresstwo 		= isset( $_POST[ 'bpaddresstwo' ] ) ? $_POST[ 'bpaddresstwo' ] : '';
		$bptown				= isset( $_POST[ 'bptown' ] ) ? $_POST[ 'bptown' ] : '';
		$bpcity				= isset( $_POST[ 'bpcity' ] ) ? $_POST[ 'bpcity' ] : '';
		$bpcountry			= isset( $_POST[ 'bpcountry' ] ) ? $_POST[ 'bpcountry' ] : '';
		$bpbusinessEmail	= isset( $_POST[ 'bpbusinessEmail' ] ) ? $_POST[ 'bpbusinessEmail' ] : '';
		$bppostcode			= isset( $_POST[ 'bppostcode' ] ) ? $_POST[ 'bppostcode' ] : '';
		$bpofficePhone		= isset( $_POST[ 'bpofficePhone' ] ) ? $_POST[ 'bpofficePhone' ] : '';
		$bpmobilePhone		= isset( $_POST[ 'bpmobilePhone' ] ) ? $_POST[ 'bpmobilePhone' ] : '';
		$accountmanager		= isset( $_POST[ 'accountmanager' ] ) ? $_POST[ 'accountmanager' ] : '';
		$level				= isset( $_POST[ 'level' ] ) ? $_POST[ 'level' ] : '';
		$bpaccountBalance	= isset( $_POST[ 'bpaccountBalance' ] ) ? $_POST[ 'bpaccountBalance' ] : '';
		$bpfreephoneCalls	= isset( $_POST[ 'bpfreephoneCalls' ] ) ? $_POST[ 'bpfreephoneCalls' ] : '';
		$bpfreeliveChat		= isset( $_POST[ 'bpfreeliveChat' ] ) ? $_POST[ 'bpfreeliveChat' ] : '';
		$bpremainingCall	= isset( $_POST[ 'bpremainingCall' ] ) ? $_POST[ 'bpremainingCall' ] : '';
		$bpremainingChat	= isset( $_POST[ 'bpremainingChat' ] ) ? $_POST[ 'bpremainingChat' ] : '';
		$firstRating		= isset( $_POST[ 'firstRating' ] ) ? $_POST[ 'firstRating' ] : '';
		$secondRating		= isset( $_POST[ 'secondRating' ] ) ? $_POST[ 'secondRating' ] : '';
		$thirdRating		= isset( $_POST[ 'thirdRating' ] ) ? $_POST[ 'thirdRating' ] : '';
		$fourthRating		= isset( $_POST[ 'fourthRating' ] ) ? $_POST[ 'fourthRating' ] : '';
		$fifthRating		= isset( $_POST[ 'fifthRatings' ] ) ? $_POST[ 'fifthRatings' ] : '';
		$emailAddress		= isset( $_POST[ 'emailAddress' ] ) ? $_POST[ 'emailAddress' ] : '';
		$companyDescrioption= isset( $_POST[ 'company_field' ] ) ? $_POST[ 'company_field' ] : '';
		$companyName		= isset( $_POST[ 'companyName' ] ) ? $_POST[ 'companyName' ] : '';
		$companyNumber		= isset( $_POST[ 'companyNumber' ] ) ? $_POST[ 'companyNumber' ] : '';
		$companyWebsite		= isset( $_POST[ 'companyWebsite' ] ) ? $_POST[ 'companyWebsite' ] : '';
		$postID				= isset( $_POST[ 'post_id' ] ) ? $_POST[ 'post_id' ] : '';
		$slug				=sanitize_title($name);
		$filename			= $_POST[ 'post_id' ] . '-' . date("U") .  $_FILES[ "thumbnailImage" ][ "name" ];		
		if(!empty($pID)){
			//var_dump($fifthRating);
			$postdata = array(
			 'ID'          => $postID,
			 'post_title'  => $name,
			 'post_type'   => 'client',
			 'post_name'   => $slug
			);
			$busID 	= wp_update_post($postdata);
			//var_dump($busID);
			update_post_meta( $busID, 'full_name', $name);
			update_post_meta( $busID, 'city', $bpcity);
			update_post_meta( $busID, 'town', $bptown);
			update_post_meta( $busID, 'home_phone', $bpofficePhone);
			update_post_meta( $busID, 'mobile_phone', $bpmobilePhone );
			update_post_meta( $busID, 'postcode', $bppostcode );
			update_post_meta( $busID, 'address_line_1', $bpaddressone);
			update_post_meta( $busID, 'address_line_2', $bpaddresstwo);
			update_post_meta( $busID, 'country', $bpcountry);
			update_post_meta( $busID, 'company_address_line_1', $bpaddressone);
			update_post_meta( $busID, 'company_address_line_2', $bpaddresstwo);
			update_post_meta( $busID, 'company_town', $bptown);
			update_post_meta( $busID, 'company_city', $bpcity);
			update_post_meta( $busID, 'company_postcode', $bppostcode);
			update_post_meta( $busID, 'company_business_email', $emailAddress);
			update_post_meta( $busID, 'company_name', $companyName);
			update_post_meta( $busID, 'company_number', $companyNumber);
			update_post_meta( $busID, 'company_website', $companyWebsite);
			update_post_meta( $busID, 'company_office_phone', $bpofficePhone);
			update_post_meta( $busID, 'company_mobile_phone', $bpmobilePhone);
			update_post_meta( $busID, 'first_rating', $firstRating);
			update_post_meta( $busID, 'second_rating', $secondRating);
			update_post_meta( $busID, 'third_rating', $thirdRating);
			update_post_meta( $busID, 'fourth_rating', $fourthRating);
			update_post_meta( $busID, 'fifth_rating', $fifthRating);
			update_post_meta( $busID, 'company_description', $companyDescrioption);
			if( isset( $_FILES[ "thumbnailImage" ][ "type" ] ) ){
				$validextensions 	= array("jpeg", "jpg", "png");
				$temporary 			= explode( ".", $_FILES[ "thumbnailImage" ][ "name" ] );
				$file_extension 	= end( $temporary );
				if ( ( ( $_FILES[ "thumbnailImage" ][ "type" ] == "image/png") || ( $_FILES[ "thumbnailImage" ][ "type" ] == "image/jpg" ) || ( $_FILES[ "thumbnailImage" ][ "type" ] == "image/jpeg")
				) && ( $_FILES[ "thumbnailImage" ][ "size" ] < 400000)//Approx. 100kb files can be uploaded.
				&& in_array($file_extension, $validextensions)) {
					if ($_FILES[ "thumbnailImage" ][ "error" ] > 0){
						echo "Return Code: " . $_FILES[ "fthumbnailImage" ][ "error" ] . "<br/><br/>";
					}else{
						if (file_exists(get_template_directory_uri( )."/page-template/uploads/".$_FILES[ 'thumbnailImage' ][ 'name' ])) {
							echo $_FILES[ "thumbnailImage" ][ "name" ] . " <span id='invalid'><b>already exists.</b></span> ";
						}else{
							$sourcePath = $_FILES[ "thumbnailImage" ][ "name" ]; // Storing source path of the file in a variable
							$targetPath = get_template_directory_uri( )."/page-template/uploads/".$_FILES[ 'thumbnailImage' ][ 'name' ]; // Target path where file is to be stored
							
							if ( ! function_exists( 'wp_handle_upload' ) ) {
								require_once( ABSPATH . 'wp-admin/includes/file.php' );
							}

							$uploadedfile 	= $_FILES[ "thumbnailImage" ];
							
							
							$upload_overrides = array( 'test_form' => false, 'unique_filename_callback' => 'my_cust_filename');
							
							add_filter('upload_dir', 'upload_location_directory');
							$movefile 		= wp_handle_upload( $uploadedfile, $upload_overrides );
							//echo $movefile[ 'file' ] . '<br />';
							remove_filter('upload_dir', 'upload_location_directory');
							if ( $movefile && ! isset( $movefile[ 'error' ] ) ) {
								try{
								$sqlImageup = $WP_CON->prepare('SELECT COUNT(*) AS totalImage FROM wp_user_imguploads WHERE uid_PartnerID = :parnerID');
								$sqlImageup->execute(array(':parnerID' => $pID));
								$resultImage = $sqlImageup->fetchObject();
								}catch(PDOException $ERR){
									echo $ERR->getMessage();
									exit();
								}
								if($resultImage->totalImage > 0) {
								   // echo 'Hello';
									try {
									/* $sql = "UPDATE p_user_imguploads  
										  ('ui_HOST','ui_PATH','ui_URL','uid_PartnerID','ui_DATEUPLOAD') 
										  VALUES (:host, :url, :partnerID, :dateUpdate);
										  ";*/
									$sqlUpdateimage = "UPDATE wp_user_imguploads   
													   SET ui_HOST = :host,
														   ui_PATH = :url,
														   ui_URL  = :url,
														   uid_PartnerID = :partnerID,
														   ui_DATEUPLOAD = :dateUpdate 
													 WHERE uid_PartnerID = :user_id
												  ";
									 $statementImage = $WP_CON->prepare($sqlUpdateimage);
									 $statementImage	-> bindValue(":user_id", $pID);
									 $statementImage	-> bindValue(":host", get_home_url());
									 $statementImage	-> bindValue(":path", get_home_url());
									 $statementImage	-> bindValue(":url", $movefile['url']);
									 $statementImage	-> bindValue(":partnerID", $pID);
									 $statementImage	-> bindValue(":dateUpdate", date('Y-m-d H:i:s'));
									 $statementImage	-> bindValue(":user_id", $pID);
									 $statementImage	-> execute();

									  //$conn = null;        // Disconnect
									  //echo 'Updated';
									}
									catch(PDOException $e) {
									  echo $e->getMessage();
									}
								}
								else{ 
									global $WP_CON;
									$status=0;
									try {
										$stmtImage	= $WP_CON->prepare("INSERT INTO wp_user_imguploads (ui_HOST,ui_PATH,ui_URL,uid_PartnerID,ui_DATEUPLOAD,ui_STATUS) VALUES (:host, :path, :url, :partnerID, :date, :status)");
										$stmtImage	-> bindParam(':host', get_home_url());
										$stmtImage	-> bindParam(':path', get_home_url());
										$stmtImage	-> bindParam(':url', $movefile['url']);
										$stmtImage	-> bindParam(':partnerID', $pID);
										$stmtImage	-> bindParam(':date', date('Y-m-d H:i:s'));
										$stmtImage	-> bindParam(':status', $status);
										$stmtImage	-> execute();
										//echo "New records created successfully";
										}
									catch(PDOException $e)
										{
										echo "Error: " . $e->getMessage();
										}
									//$WP_CON = null;
								}
								//echo "File is valid, and was successfully uploaded.\n";
								//var_dump( $movefile['url'] );
							} else {
								/**
								 * Error generated by _wp_handle_upload( )
								 * @see _wp_handle_upload( ) in wp-admin/includes/file.php
								 */
								echo $uploadedfile . '<br />';
								echo $movefile[ 'error' ];
							}
							
						}
					}
				}
				else{
					//echo "<span id='invalid'>***Invalid file Size or Type***<span>";
				}
			}
			if(!empty($pID)){
				try{
				$sqlProfiles = $WP_CON->prepare('SELECT COUNT(*) AS totalUser FROM wp_user_profiles_mirror WHERE partner_id = :profileID');
				$sqlProfiles->execute(array(':profileID' => $pID));
				$results = $sqlProfiles->fetchObject();
				}catch(PDOException $ERR){
					echo $ERR->getMessage();
					exit();
				}
				if($results->totalUser > 0) {
					try {
					  $sqlmerror = "UPDATE wp_user_profiles_mirror   
							   SET partner_id = :partnerID,
								   full_name = :fullName,
								   email_address  = :emailAddress,
								   home_phone = :homePhone,
								   mobile_phone = :mobilePhone, 
								   postcode	 = :postCode, 
								   company_name	 = :companyName, 
								   company_description = :companyDescription, 
								   company_postcode = :companyPostcode, 
								   ratings1 = :ratingOne, 
								   ratings2 = :ratingTwo, 
								   ratings3 = :ratingThree, 
								   ratings4 = :ratingFour, 
								   ratings5 = :ratingFive 
							 WHERE partner_id = :user_id
						  ";
							
					 $statementProfile = $WP_CON->prepare($sqlmerror);
					 $statementProfile -> bindValue(":user_id", $pID);
					 $statementProfile -> bindValue(":fullName", $name);
					 $statementProfile -> bindValue(":emailAddress", $emailAddress);
					 $statementProfile -> bindValue(":homePhone", $bpofficePhone);
					 $statementProfile -> bindValue(":partnerID", $pID);
					 $statementProfile -> bindValue(":mobilePhone", $bpmobilePhone);
					 $statementProfile -> bindValue(":postCode", $bppostcode);
					 $statementProfile -> bindValue(":companyName", $companyName);
					 $statementProfile -> bindValue(":companyDescription", $companyDescrioption);
					 $statementProfile -> bindValue(":companyPostcode", $bppostcode);
					 $statementProfile -> bindValue(":ratingOne", $firstRating);
					 $statementProfile -> bindValue(":ratingTwo", $secondRating);
					 $statementProfile -> bindValue(":ratingThree", $thirdRating);
					 $statementProfile -> bindValue(":ratingFour", $fourthRating);
					 $statementProfile -> bindValue(":ratingFive", $fifthRating);
					 $statementProfile -> execute();
					 //$WP_CON = null;        // Disconnect
					  //echo 'Updated';
					}
					catch(PDOException $e) {
					  echo $e->getMessage();
					}
				}
				else{
					try {
						$status=0;
						$sqlInsertPro = "INSERT INTO wp_user_profiles_mirror (partner_id, full_name, email_address,home_phone,mobile_phone,postcode,company_name,company_description,company_postcode,ratings1,ratings2,ratings3,ratings4,ratings5,status)
						VALUES ('".$pID."','".$name."', '".$emailAddress."', '".$bpofficePhone."','".$bpmobilePhone."', '".$bppostcode."', '".$companyName."', '".$companyDescrioption."', '".$bppostcode."', '".$firstRating."', '".$secondRating."', '".$thirdRating."', '".$fourthRating."', '".$fifthRating."', '".$status."')";
						// use exec() because no results are returned
						$WP_CON->exec($sqlInsertPro);
						//echo "New record created successfully";
						}
					catch(PDOException $e)
						{
						echo $sqlInsertPro . "<br>" . $e->getMessage();
						}

					//$WP_CON = null;
				}
			}
				  $customAPIKEY  = get_field('custom_api_key','option');// name of the admin
				  $customAPIID  = get_field('custom_api_id','option');// Email Title for the admin
				  $API_URL 		= 'http://api.ontraport.com/1/objects?'; //URL OF THE ONTRAPORT
				  //DATA TO FETCH				  
				  $API_DATA 	= array(
				   'objectID'   => 0,
				   'performAll' => 'true',
				   'sortDir'  	=> 'asc',
				   'condition'  => "email='".$emailAddress."'",
				   //'condition'  => "email='testing@umbrellasupport.co.uk'",
				   'searchNotes'=> 'true'
				  );
				  //API DETAILS
				  $API_KEY  = $customAPIKEY;
				  $API_ID  	= $customAPIID;
				  
				  //API RESULT
				  $API_RESULT 	= op_query($API_URL, 'GET', $API_DATA, $API_ID, $API_KEY);
				  $getName  	= json_decode($API_RESULT); //GET THE RESULT AND CONVERT TO JSON FORMAT
				  
				  $API_RESULT 	= null; //REASSIGN VALUE OF API RESULT TO NULL
				 /* switch($level){
					  case 'Level 1': $levelPackage=1; break;
					  case 'Level 2': $levelPackage=2; break;
					  case 'Level 3': $levelPackage=3; break;
					  case 'Level 4': $levelPackage=4; break;
					  case 'Level 5': $levelPackage=5; break;
					  default		: $levelPackage=1; break;
				  }*/
				  //API DATA TO PUT/UPDATE. NOTE: ObjectID must be 0 [0 = Contacts]; and id must be set and must be already registered in OntraPort, otherwise the result is fail.
				  $API_DATA 		= array(
				   'objectID'   			=> 0,
				   'id'  	 				=> $pID,
				   'cell_phone' 			=> $bpmobilePhone, //Set to new value. You can add more fields by adding more here. Not just firstname but all fields except the id.
				   'office_phone' 			=> $bpofficePhone, 
				   'company' 				=> $companyName, 
				   'f1564' 					=> $companyNumber, 
				   'city'  					=> $bpcity,
				   'Town_340'  				=> $bptown,
				   'County_456'  			=> $bpcountry,
				   'website'  			    => $companyWebsite,
				   'zip' 					=> $bppostcode,
				   'address' 				=> $bpaddressone,  
				   'address2' 				=> $bpaddresstwo,  
				   'f1556'					=> $emailAddress
				  );				  
				  //GET PUT RESULT
				  $API_RESULT 	= op_query( $API_URL, 'PUT', $API_DATA, $API_ID, $API_KEY );				  
				  //IF OBJECT NOT FOUND, DISPLAY ERROR MESSAGE
				  if( $API_RESULT == "Object not found" ){
				   echo 'FAILED TO PUT/UPDATE DATA!';
				   die( );
				  }
				 echo '<div class="business-message-success">';
				 echo '<span><b>✓</b>Success! Details updated.</span>';
				 echo '</div>'; 
		}else{
			echo '<div class="business-message-error">';
			echo '<span>The partner ID should not empty</span>';
			echo '</div>';
		}
		die( ); 
	}
	add_action( 'wp_ajax_businessProfileProcess', 'businessProfileProcess' );
	
	function upload_location_directory($DIR){
		return array(
			'path'   => $DIR[ 'basedir' ] . '/imageuploads',
			'url'    => $DIR[ 'baseurl' ] . '/imageuploads',
			'subdir' => '/imageuploads',
		) + $DIR;
		return $DIR;
	}
	function my_cust_filename($dir, $name, $ext){
		return $name.$ext;
	}
	// Auto-populate post title with ACF.
	function register_user_account( $value, $post_id, $field ) {
		//$date = strtotime(get_field('date', $post_id));
		global $post_id,$title,$wpdb,$userId,$user;
		$host  		= "db640728737.db.1and1.com";
		$database   = "db640728737";
		$user  		= "dbo640728737";
		$password   = "1qazxsw2!QAZXSW@";
		try{
		$WP_CON	= new PDO('mysql:host='.$host.';dbname='.$database.';', $user, $password);
		$WP_CON->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $ERR){
			echo $ERR->getMessage();
			exit();
		}
		$fullName 	 	 			 		= get_field('full_name', $post_id);
		$emailAddress						= get_field('email_address', $post_id);
		$name						 		= explode( ' ', $fullName );
		$firstname 			 		 		= $name[0];
		$lastname 			 		 		= $name[1];
		$partnerID 			 		 		= get_field('partner_id', $post_id);
		$email 						 		= get_field('email_address', $post_id);
		$address_line_1		 		 		= get_field('address_line_1', $post_id);
		$address_line_2				 		= get_field('address_line_2', $post_id);
		$town						 		= get_field('town', $post_id);
		$city				 		 		= get_field('city', $post_id);
		$postcode			 		 		= get_field('postcode', $post_id);
		$country							= get_field('country', $post_id);
		$email_address				 		= get_field('email_address', $post_id);
		$home_phone		 			 		= get_field('home_phone', $post_id);
		$companyNumber				 		= get_field('company_number', $post_id);
		$mobile_phone				 		= get_field('mobile_phone', $post_id);
		$company_name	 		 	 		= get_field('company_name', $post_id);
		$company_website	 		 	 	= get_field('company_website', $post_id);
		$company_description	 		 	= get_field('company_description', $post_id);
		$company_address_line_1		 		= get_field('company_address_line_1', $post_id);
		$company_address_line_2		 		= get_field('company_address_line_2', $post_id);
		$company_town	 					= get_field('company_town', $post_id);
		$company_city		 		 		= get_field('company_city', $post_id);
		$company_postcode			 		= get_field('company_postcode', $post_id);
		$company_business_email		 		= get_field('company_business_email', $post_id);
		$company_office_phone	 			= get_field('company_office_phone', $post_id);
		$company_mobile_phone	 			= get_field('company_mobile_phone', $post_id);
		$partner_id	 						= get_field('partner_id', $post_id);
		$account_manager	 				= get_field('account_manager', $post_id);
		$package_level	 					= get_field('package_level', $post_id);
		$account_balance			    	= get_field('account_balance', $post_id);
		$free_phone_calls				    = get_field('free_phone_calls', $post_id);
		$free_live_chat_msgs			    = get_field('free_live_chat_msgs', $post_id);
		$remaining_calls_this_month	 		= get_field('remaining_calls_this_month', $post_id);
		$remaining_live_chats_this_month	= get_field('remaining_live_chats_this_month', $post_id);
		$ratingOne		 		 	 		= get_field('first_rating', $post_id);
		$ratingTwo		 		 	 		= get_field('second_rating', $post_id);
		$ratingThree		 		 	 	= get_field('third_rating', $post_id);
		$ratingFour		 		 	 		= get_field('fourth_rating', $post_id);
		$ratingFive		 		 	 		= get_field('fifth_rating', $post_id);	
		$reviewUrl	 		 	 			= get_field('reviews_url_link', $post_id);
		$photosUrl	 		 	 			= get_field('photos_url_link', $post_id);
		$accreditationUrl	 		 	 	= get_field('accreditations_url_link', $post_id);
		$checkUrl	 		 	 			= get_field('checks_url_link', $post_id);
		$package	 		 				= get_field('package', $post_id);
		$password 				     		= wp_generate_password( 8, false );
		$title 						 		= $fullName;
		$slug 						 		= sanitize_title( $title );
		$postdata = array(
			 'ID'          => $post_id,
			 'post_title'  => $title,
			 'post_type'   => 'client',
			 'post_name'   => $slug
		);
		wp_update_post( $postdata );
		?>
		<?php
		// vars	
		$company_categories = get_field('company_categories',$post_id); 
		$field = get_field_object('company_categories',$post_id);
		$choices = $field['choices'];
		// check
		$sqlTrade = $WP_CON->prepare('SELECT COUNT(*) AS total FROM wp_trade_mirror WHERE partner_id = :partnerID');
		$sqlTrade->execute(array(':partnerID' => $partnerID));
		$result = $sqlTrade->fetchObject();
		if($result->total > 0) {
			try{
				$sqlUpdate = "UPDATE wp_trade_mirror  
						   SET trade_status = :tradeStats 
						 WHERE partner_id = :user_id AND
							   trade = :trade_cat
					  ";
				$stmt = $WP_CON->prepare($sqlUpdate);
				foreach ($choices as $value => $label) {
					$id = $partnerID;
					$stmt->bindparam(":user_id", $id, PDO::PARAM_STR); 
					
					if (in_array($value, $company_categories)) {
						$val = 'Enable';
						$stmt->bindparam(':tradeStats', $val, PDO::PARAM_STR);
					}else{
						$val = 'Disabled';
						$stmt->bindparam(':tradeStats', $val, PDO::PARAM_STR);
					}
					$stmt->bindparam(":trade_cat", $value, PDO::PARAM_STR); 
					$stmt->execute();
				}
			} catch (PDOException $e) {
			 $WP_CON->rollBack();
			}
		}else{
			$stmt = $WP_CON->prepare('INSERT INTO wp_trade_mirror (partner_id,trade,trade_status)VALUES(:partner_id,:categories,:tradeStats)');
			try {
			$WP_CON->beginTransaction();
			foreach ($choices as $value => $label) {
				$stmt->bindValue(':partner_id', $partnerID);
				$stmt->bindValue(':categories', $label);
				if (in_array($value, $company_categories)) {
					$stmt->bindValue(':tradeStats', 'Enable');
				}else{
					$stmt->bindValue(':tradeStats', 'Disabled');
				}
				$stmt->execute();
			}
			 $WP_CON->commit();
			} catch (PDOException $e) {
			 $WP_CON->rollBack();
			}
		}
		$sqlMerror = $WP_CON->prepare('SELECT COUNT(*) AS totals FROM wp_user_profiles_mirror WHERE partner_id = :partnerIDS');
		$sqlMerror->execute(array(':partnerIDS' => $partnerID));
		$results = $sqlMerror->fetchObject();
		if($results->totals > 0) {
			try {
			$sqlMerror = "UPDATE wp_user_profiles_mirror   
					   SET partner_id = :partnerIDS,
						   full_name = :fullName,
						   email_address  = :emailAddress,
						   home_phone = :homePhone,
						   mobile_phone = :mobilePhone, 
						   postcode	 = :postCode, 
						   company_name	 = :companyName, 
						   company_description = :companyDescription, 
						   company_postcode = :companyPostcode, 
						   ratings1 = :ratingOne, 
						   ratings2 = :ratingTwo, 
						   ratings3 = :ratingThree, 
						   ratings4 = :ratingFour, 
						   ratings5 = :ratingFive,
						   reviews_url = :reviewsUrl, 
						   photos_url = :photosUrl, 
						   accreditations_url = :accreditationsUrl, 
						   checks_url = :checksUrl 						   
					 WHERE partner_id = :user_id
				  ";
			 $statement = $WP_CON->prepare($sqlMerror);
			 $statement->bindValue(":user_id", $partnerID);
			 $statement->bindValue(":partnerIDS", $partnerID);
			 $statement->bindValue(":fullName", $title);
			 $statement->bindValue(":emailAddress", $email_address);
			 $statement->bindValue(":homePhone", $home_phone);
			 $statement->bindValue(":mobilePhone", $mobile_phone);
			 $statement->bindValue(":postCode", $company_postcode);
			 $statement->bindValue(":companyName", $company_name);
			 $statement->bindValue(":companyDescription", $company_description);
			 $statement->bindValue(":companyPostcode", $company_postcode);
			 $statement->bindValue(":ratingOne", $ratingOne);
			 $statement->bindValue(":ratingTwo", $ratingTwo);
			 $statement->bindValue(":ratingThree", $ratingThree);
			 $statement->bindValue(":ratingFour", $ratingFour);
			 $statement->bindValue(":ratingFive", $ratingFive);
			 $statement->bindValue(":reviewsUrl", $reviewUrl);
			 $statement->bindValue(":photosUrl", $photosUrl);
			 $statement->bindValue(":accreditationsUrl", $accreditationUrl);
			 $statement->bindValue(":checksUrl", $checkUrl);
			 $countMerror = $statement->execute();
			 //$WP_CON = null;        // Disconnect
			  //echo 'Updated';
			}
			catch(PDOException $e) {
			  echo $e->getMessage();
			}
		}else{
			try {
			$sql = "INSERT INTO wp_user_profiles_mirror (partner_id, full_name, email_address,home_phone,mobile_phone,postcode,company_name,company_description,company_postcode,ratings1,ratings2,ratings3,ratings4,ratings5,reviews_url,photos_url,accreditations_url,checks_url)
			VALUES ('".$partnerID."','".$title."', '".$email_address."', '".$home_phone."','".$mobile_phone."', '".$company_postcode."', '".$company_name."', '".$company_description."', '".$company_postcode."', '".$ratingOne."', '".$ratingTwo."', '".$ratingThree."', '".$ratingFourth."', '".$ratingFifth."', '".$reviewUrl."', '".$photosUrl."', '".$accreditationUrl."', '".$checkUrl."')";
			// use exec() because no results are returned
			$WP_CON->exec($sql);
			//echo "New record created successfully";
			}
			catch(PDOException $e)
				{
				//echo $sql . "<br>" . $e->getMessage();
				}

			//$WP_CON = null;
		}
		//$user = get_user_by( 'email', $email );
		if(email_exists($email)){
			global  $user_id;
			$login =$email;
			if(is_email( $login )){
				if( email_exists( $login )) {
				  $userID__ = email_exists($login);
				  $user_info = get_userdata($userID__);
				  $user_id  = $user_info->ID;
				 // var_dump($login);
				}
			}
			$userargs = array(
				'ID' 			 => $user_id,
				'first_name'	 => $firstname,
				'last_name'		 => $lastname,
				'user_login' 	 => $email,
				'nickname'		 => $fullName,
				'user_email'	 => $email,
				//'user_pass' 	 => $password,
				'display_name'	 => $fullName,
				'role'			 => 'subscriber'
			);
			wp_update_user($userargs);
		}else{
		}
		//Update ontraport
			  $customAPIKEY  = get_field('custom_api_key','option');// name of the admin
			  $customAPIID  = get_field('custom_api_id','option');// Email Title for the admin
			  $API_URL 		= 'http://api.ontraport.com/1/objects?'; //URL OF THE ONTRAPORT
			  //DATA TO FETCH				  
			  $API_DATA 	= array(
			   'objectID'   => 0,
			   'performAll' => 'true',
			   'sortDir'  	=> 'asc',
			   'condition'  => "email='".$email."'",
			   //'condition'  => "email='testing@umbrellasupport.co.uk'",
			   'searchNotes'=> 'true'
			  );
			  //API DETAILS
			  $API_KEY  = $customAPIKEY;
			  $API_ID  	= $customAPIID;
			  
			  //API RESULT
			  $API_RESULT 	= op_query($API_URL, 'GET', $API_DATA, $API_ID, $API_KEY);
			  $getName  	= json_decode($API_RESULT); //GET THE RESULT AND CONVERT TO JSON FORMAT
			  
			  $API_RESULT 	= null; //REASSIGN VALUE OF API RESULT TO NULL
			  switch($package_level){
				  case 'Level 1': $level=1; break;
				  case 'Level 2': $level=2; break;
				  case 'Level 3': $level=3; break;
				  case 'Level 4': $level=4; break;
				  case 'Level 5': $level=5; break;
				  default		: $level=1; break;
			  }
			  //API DATA TO PUT/UPDATE. NOTE: ObjectID must be 0 [0 = Contacts]; and id must be set and must be already registered in OntraPort, otherwise the result is fail.
			  $API_DATA 		= array(
			   'objectID'   			=> 0,
			   'id'  	 				=> $partner_id,
			   'firstname'  			=> $firstname,
			   'lastname'  				=> $lastname,
			   'address'  				=> $address_line_1,
			   'city'  					=> $city,
			   'Town_340'  				=> $town,
			   'County_456'  			=> $country,
			   'zip' 					=> $postcode, 
			   'cell_phone' 			=> $mobile_phone, //Set to new value. You can add more fields by adding more here. Not just firstname but all fields except the id.
			   'home_phone' 			=> $home_phone, 
			   'company' 				=> $company_name, 
			   'f1564' 					=> $companyNumber,
			   'website' 				=> $company_website,
			   'address2' 				=> $address_line_2, 
			   'office_phone' 			=> $company_office_phone, 
			   'f1548' 					=> $package,
			   'f1549' 					=> $level,
			   'f1556'					=> $company_business_email,
			   'f1547'					=> $account_balance
			  );				  
			  //GET PUT RESULT
			  $API_RESULT 	= op_query( $API_URL, 'PUT', $API_DATA, $API_ID, $API_KEY );				  
			  //IF OBJECT NOT FOUND, DISPLAY ERROR MESSAGE
			  if( $API_RESULT == "Object not found" ){
			   echo 'FAILED TO PUT/UPDATE DATA!';
			   die( );
			  }
		return $value;
	}
	add_action('acf/save_post', 'register_user_account');
	function disable_acf_load_field( $field ) {
	$field[ 'disabled' ] = 1;
	return $field;
	}
	add_filter('acf/load_field/name=email_address', 'disable_acf_load_field',10, 3);
	add_filter('acf/load_field/name=partner_id', 'disable_acf_load_field',10, 3);
	add_filter('acf/load_field/name=logo_partner_id', 'disable_acf_load_field',10, 3);

	if ( function_exists( 'acf_add_options_sub_page' ) ){
		acf_add_options_sub_page(array(
			'title'      => 'Profile Settings',
			'parent'     => 'edit.php?post_type=client',
			'menu_slug'  => 'profile-settings',
			'capability' => 'manage_options'
		));
	}
	function personalProfile( ){
		global $wpdb; // this is how you get access to the database
		$host  		= "db640728737.db.1and1.com";
		$database   = "db640728737";
		$user  		= "dbo640728737";
		$password   = "1qazxsw2!QAZXSW@";
		try{
			$WP_CON	= new PDO('mysql:host='.$host.';dbname='.$database.';', $user, $password);
			$WP_CON->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $ERR){
			echo $ERR->getMessage();
			exit();
		}
		$profileID			= isset( $_POST[ 'profileID' ] ) ? $_POST[ 'profileID' ] : '';
		$pesonalID			= isset( $_POST[ 'pesonalID' ] ) ? $_POST[ 'pesonalID' ] : '';
		$name				= isset( $_POST[ 'name' ] ) ? $_POST[ 'name' ] : '';
		$emailAddress		= isset( $_POST[ 'emailAddress' ] ) ? $_POST[ 'emailAddress' ] : '';
		$firstAddress		= isset( $_POST[ 'firstAddress' ] ) ? $_POST[ 'firstAddress' ] : '';
		$secondAddress		= isset( $_POST[ 'secondAddress' ] ) ? $_POST[ 'secondAddress' ] : '';
		$city				= isset( $_POST[ 'city' ] ) ? $_POST[ 'city' ] : '';
		$town				= isset( $_POST[ 'town' ] ) ? $_POST[ 'town' ] : '';
		$postcode			= isset( $_POST[ 'postcode' ] ) ? $_POST[ 'postcode' ] : '';
		$county				= isset( $_POST[ 'county' ] ) ? $_POST[ 'county' ] : '';
		$homePhone			= isset( $_POST[ 'homePhone' ] ) ? $_POST[ 'homePhone' ] : '';
		$mobilePhone		= isset( $_POST[ 'mobilePhone' ] ) ? $_POST[ 'mobilePhone' ] : '';
		$fullname		= explode( ' ', $name );
		$firstname 	= $fullname[0];
		$lastname 	= $fullname[1];
		if(empty($profileID)){
			echo 'Client profile not found!';
		}else{
			$customAPIKEY   = get_field('custom_api_key','option');// name of the admin
		    $customAPIID  	= get_field('custom_api_id','option');// Email Title for the admin
			$API_URL 		= 'http://api.ontraport.com/1/objects?'; //URL OF THE ONTRAPORT
			  //DATA TO FETCH				  
			  $API_DATA 	= array(
			   'objectID'   => 0,
			   'performAll' => 'true',
			   'sortDir'  	=> 'asc',
			   'condition'  => "email='".$emailAddress."'",
			   'searchNotes'=> 'true'
			  );
			  //API DETAILS
			  $API_KEY  = $customAPIKEY;
			  $API_ID  	= $customAPIID;
			  
			  //API RESULT
			  $API_RESULT 	= op_query($API_URL, 'GET', $API_DATA, $API_ID, $API_KEY);
			  $getName  	= json_decode($API_RESULT); //GET THE RESULT AND CONVERT TO JSON FORMAT
			  
			  $API_RESULT 	= null; //REASSIGN VALUE OF API RESULT TO NULL
			  //API DATA TO PUT/UPDATE. NOTE: ObjectID must be 0 [0 = Contacts]; and id must be set and must be already registered in OntraPort, otherwise the result is fail.
			  $API_DATA 		= array(
			   'objectID'   			=> 0,
			   'id'  	 				=> $profileID,
			   'firstname'  			=> $firstname,
			   'lastname'  				=> $lastname,
			   'address'  				=> $firstAddress,
			   'address2'  				=> $secondAddress,
			   'city'  					=> $city,
			   'Town_340'  				=> $town,
			   'County_456'  			=> $county,
			   'zip' 					=> $postcode, 
			   'cell_phone' 			=> $mobilePhone, //Set to new value. You can add more fields by adding more here. Not just firstname but all fields except the id.
			   'home_phone' 			=> $homePhone 
			  );			  
			  //GET PUT RESULT
			  $API_RESULT 	= op_query( $API_URL, 'PUT', $API_DATA, $API_ID, $API_KEY );
			  //IF OBJECT NOT FOUND, DISPLAY ERROR MESSAGE
			  if( $API_RESULT == "Object not found" ){
			   echo 'FAILED TO PUT/UPDATE DATA!';
			   die( );
			  }
				// Update post 37
				$personalPost = array(
				'ID'           => $pesonalID,
				'post_title'   => $name,
				'post_type' => 'client',
				);
				// Update the post into the database
				$perID 		= wp_update_post( $personalPost ); 
				update_post_meta( $perID, 'full_name', $name );
				update_post_meta( $perID, 'address_line_1', $firstAddress );
				update_post_meta( $perID, 'address_line_2', $secondAddress );
				update_post_meta( $perID, 'city', $city );
				update_post_meta( $perID, 'town', $town );
				update_post_meta( $perID, 'country', $county );
				update_post_meta( $perID, 'postcode', $postcode );
				update_post_meta( $perID, 'home_phone', $homePhone );
				update_post_meta( $perID, 'mobile_phone', $mobilePhone );
				update_post_meta( $perID, 'company_address_line_1', $firstAddress);
				update_post_meta( $perID, 'company_address_line_2', $secondAddress);
				update_post_meta( $perID, 'company_town', $town);
				update_post_meta( $perID, 'company_city', $city );
				update_post_meta( $perID, 'company_postcode', $postcode );
				update_post_meta( $perID, 'company_business_email', $emailAddress);
				update_post_meta( $perID, 'company_name', $companyName); 
				update_post_meta( $perID, 'company_office_phone', $homePhone );
				update_post_meta( $perID, 'company_mobile_phone', $mobilePhone);
				$sql = $WP_CON->prepare('SELECT COUNT(*) AS total FROM wp_user_profiles_mirror WHERE partner_id = :partnerID');
				$sql->execute(array(':partnerID' => $profileID));
				$result = $sql->fetchObject();
				if($result->total > 0) {
					try {
						$sql = "UPDATE wp_user_profiles_mirror   
						   SET full_name = :fullName,  
							   home_phone = :homePhone,  
							   mobile_phone = :mobilePhone,  
							   postcode = :postCode,  
							   company_postcode = :companypostCode  
						 WHERE partner_id = :user_id
						";

						$statement = $WP_CON->prepare($sql);
						$statement->bindValue(":user_id", $profileID);
						$statement->bindValue(":fullName", $name);
						$statement->bindValue(":homePhone", $homePhone);
						$statement->bindValue(":mobilePhone", $mobilePhone);
						$statement->bindValue(":postCode", $postcode);
						$statement->bindValue(":companypostCode", $postcode);
						//$statement->bindValue(":status", $status);
						$count = $statement->execute();

						$conn = null;        // Disconnect
					}
					catch(PDOException $e) {
						echo $e->getMessage();
					}
				}
				if(email_exists($emailAddress)){
					global  $user_id;
					$login = $emailAddress;
					if(is_email( $login )){
						if( email_exists( $login )) {
						  $userID__ = email_exists($login);
						  $user_info = get_userdata($userID__);
						  $user_id  = $user_info->ID;
						 // var_dump($login);
						}
					}
					$userargs = array(
					'ID' 			 => $user_id,
					'first_name'	 => $firstname,
					'last_name'		 => $lastname,
					'user_login' 	 => $emailAddress,
					'nickname'		 => $name ,
					//'user_email'	 => $emailAddress,
					//'user_pass' 	 => wp_generate_password( 8, false ),
					'display_name'	 => $name ,
					'role'			 => 'subscriber'
					);
					wp_update_user($userargs);
			  }
			  echo'<div>';
			  echo'<span class="message-success-update"><b>✓</b>Success! Details updated.</span>';
			  echo'</div>';
		}
		die( ); // this is required to return a proper result
	}
	add_action( 'wp_ajax_personalProfile', 'personalProfile' );
	add_image_size( 'admin-list-thumb', 80, 80, false );

	// add featured thumbnail to admin post columns
	function wpcs_add_thumbnail_columns( $columns ) {
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'featured_thumb' => 'Company Logo',
			'title' => 'Company Logo Title',
			'status' => 'Status',
			'partner_id' => 'Partner ID',
			'owner' => 'Owner',
			//'categories' => 'Categories',
			//'tags' => 'Tags',
			//'comments' => '<span class="vers"><div title="Comments" class="comment-grey-bubble"></div></span>',
			'date' => 'Date'
		);
		return $columns;
	}
	function wpcs_add_thumbnail_columns_data( $column, $post_id ) {
		switch ( $column ) {
			case 'featured_thumb':
				$post_thumbnail_id = get_post_thumbnail_id( $post );
				//$size = array(50);
				$post_thumbnail_image = wp_get_attachment_image_url( $post_thumbnail_id,'full');
				if (!empty($post_thumbnail_image)) {
					echo '<a href="' . get_edit_post_link() . '">';
					?>
					<img width="50" height="auto"  src="<?php echo $post_thumbnail_image; ?>" alt="<?php the_title(); ?>" />
					<?php
					echo '</a>';
				} else{ 
					echo '<a href="' . get_edit_post_link() . '">';
					?>	
					<img width="50" height="auto"  src="<?php bloginfo('template_directory'); ?>/images/default-logo.jpg" alt="<?php the_title(); ?>" />
					<?php
					echo '</a>';
				} 
			break;
			case 'partner_id' :

				/* Get the post meta. */
				$status = '';
				//$status = get_post_meta( $post_id, 'status', true );

				/* If no duration is found, output a default message. */
				$partner_id = get_post_meta( $post_id, 'logo_partner_id', true );
				if ( empty( $partner_id ) )
					//echo __( 'Pending - Need to review the logo' );
					echo'<p><b style="color:#d7090b">Empty</b></p>';

				/* If there is a duration, append 'minutes' to the text string. */
				else
					//printf( __( 'Approved' ), $status );
					echo'<p style="color:#aa7700"><b>'.$partner_id.'</b></p>';

			break;
			/* If displaying the 'duration' column. */
			case 'status' :
				/* Get the post meta. */
				$status = get_post_meta( $post_id, 'status', true );
				//$status = get_post_meta( $post_id, 'status', true );
				/* If no duration is found, output a default message. */
				if ($status=='No Selected Logo' ){
					echo'<p><b>No Selected Logo</p>';
				}
				elseif($status=='Pending'){
					//echo __( 'Pending - Need to review the logo' );
					echo'<p><b style="color:#d7090b">Pending</b> - Need to review the logo</p>';
				/* If there is a duration, append 'minutes' to the text string. */
				}else{
					//printf( __( 'Approved' ), $status );
					echo'<p style="color:#aa7700"><b>Approved</b></p>';
				}
			break;
			case 'owner' :
				/* Get the post meta. */
				$owner = get_post_meta( $post_id, 'company_owner', true );
				//$status = get_post_meta( $post_id, 'status', true );
				/* If no duration is found, output a default message. */
				echo $owner;
			break;
			/* If displaying the 'genre' column. */
			/* Just break out of the switch statement for everything else. */
			default :
			break;
		}
	}
	if ( function_exists( 'add_theme_support' ) ) {
		add_filter( 'manage_logo_posts_columns' , 'wpcs_add_thumbnail_columns' );
		add_action( 'manage_logo_posts_custom_column' , 'wpcs_add_thumbnail_columns_data', 10, 2 );
		add_filter( 'manage_logo_pages_columns' , 'wpcs_add_thumbnail_columns' );
		add_action( 'manage_logo_pages_custom_column' , 'wpcs_add_thumbnail_columns_data', 10, 2 );
	}
	//Shortcode 
	function account_balance( ){
		ob_start( );
		global $FULLNAME;
		$current_user = wp_get_current_user();
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
		$acountValue=$getName->data[0]->f1547;
		if(is_user_logged_in()){
			if(!empty($acountValue)){
				echo $acountValue;
			}else{
				echo 'XXXX';
			}
		}
		else{
			echo 'XXXX';
		}
		return ob_get_clean( );
	}
	add_shortcode('amountValue','account_balance');//
	function package_background( ){
		ob_start( );
		?>
		<?
		global $FULLNAME;
		$current_user = wp_get_current_user();
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
		$packagelower=$getName->data[0]->f1548;
		if(is_user_logged_in()){
			$firstUpper=strtolower($packagelower);
			$packageImage=ucfirst($firstUpper);
			switch($packageImage){
				case 'Standard' : echo 'class="standard-level"'; break;
				case 'Bronze'	: echo 'class="bronze-level"'; break;
				case 'Silver'	: echo 'class="silver-level"'; break;
				case 'Gold'		: echo 'class="gold-level"'; break;
				default			: echo 'class="standard-level"'; break;
			}
		}
		else{
			
		}
		return ob_get_clean( );
	}
	add_shortcode('packageBackground','package_background');//
	function package_details( ){
		ob_start( );
		global $FULLNAME;
		$current_user = wp_get_current_user();
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
		$packagelower=$getName->data[0]->f1548;
		if(is_user_logged_in()){
			$firstUpper=strtolower($packagelower);
			$packageImage=ucfirst($firstUpper);
			switch($packageImage){
				case 'Standard' : echo 'Standard'; break;
				case 'Bronze'	: echo 'Bronze'; break;
				case 'Silver'	: echo 'Silver'; break;
				case 'Gold'		: echo 'Gold'; break;
				default			: echo 'Standard'; break;
			}
		}
		else{
			
		}
		return ob_get_clean( );
	}
	add_shortcode('packageDetails','package_details');//
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
		//$amountStripe=$amountStripes+($amountStripes*0.014);
		//$amountStripe=number_format((float)$amountStripe, 2, '.', '');
		//$amountStripe=str_replace(".","",$amountStripe);
		//$amountStripe = intval($amountStripe);
		//var_dump($amountStripe);
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
			//$amountStripe=$amountStripes+($amountStripes*0.014);
			//$amountStripe=number_format((float)$amountStripe, 2, '.', '');
			//$amountStripe=str_replace(".","",$amountStripe);
			 //var_dump($amountStripe);
			  $charge = \Stripe\Charge::create(array(
				  'customer' => $customer->id,
				  'amount'   => $amountStripe.'00',
				  'metadata' => array("order_id" => "6735"),	
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
			$stripAmount=ltrim ($stripAmount,'£');
			$totalStripe=($amountStripe)+($stripAmount);
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
	function automaticTop(){ 
		$host  		= "db639369002.db.1and1.com"; 
		$database   = "db639369002";
		$user  		= "dbo639369002";
		$password   = "1qazxsw2!QAZXSW@";
		try{
		$WP_CON	= new PDO('mysql:host='.$host.';dbname='.$database.';', $user, $password);
		$WP_CON->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$WP_CON->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
		}catch(PDOException $ERR){
			echo $ERR->getMessage();
			exit();
		}
		require_once(get_stylesheet_directory() . '/stripe-php/init.php');
		$stripe = array(
		  "secret_key"      => "sk_test_E01Kh96YOzRtkhD5wItn8CDd",
		  "publishable_key" => "pk_test_u289X2Do4OavHR2STjbI2TsL"
		);
		\Stripe\Stripe::setApiKey($stripe['secret_key']);
		$autostripeToken = isset( $_POST[ 'autostripeToken' ] ) ? $_POST[ 'autostripeToken' ] : '';
		$autotopupAmount = isset( $_POST[ 'autotopupAmount' ] ) ? $_POST[ 'autotopupAmount' ] : '';
		$minAmounts		 = isset( $_POST[ 'minAmounts' ] ) ? $_POST[ 'minAmounts' ] : '';
		//$autotopupAmount=$autotopupAmounts+($autotopupAmounts*0.014);
		//$autotopupAmount=number_format((float)$autotopupAmount, 2, '.', '');
		//$autotopupAmount=str_replace(".","",$autotopupAmount);
		//$autotopupAmount = intval($autotopupAmount);
		$current_user 	 = wp_get_current_user();
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
		$autocreditID	= $getName->data[0]->id;
		$autostripAmount = $getName->data[0]->f1547;
		if(empty($autocreditID)){
			echo 'Please register this email in ontraport to be processed!';
		}else{
			if(empty($minAmounts)){
				echo 'Please Select for minimum balance!';
			}else{
				$customerAuto 	 = \Stripe\Customer::create(array(
				  'email' 	=> $current_user->user_email,
				  'source'  => $autostripeToken
				));
				//$autotopupAmount=$autotopupAmounts+($autotopupAmounts*0.014);
				//var_dump($amountStripe);
				$chargeAuto = \Stripe\Charge::create(array(
				  'customer' => $customerAuto->id,
				  'amount'   => $autotopupAmount.'00',
				  'currency' => 'GBP' 
				)); 
				//var_dump($chargeAuto->source->name); 
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
				$brand=$chargeAuto->source->brand;
				switch($brand){
					case 'Visa':			$card = 795;break;
					case 'MasterCard':		$card = 796;break;
					case 'American Express':$card=805;break;
					default:				$card = "";	break;
				}
				$autostripAmount=ltrim ($autostripAmount,'£');
				$autototalStripe=($autotopupAmount)+($autostripAmount);
				$API_DATA 		= array(
				'objectID'   			=> 0,
				'id'  	 				=> $autocreditID,
				'CreditDebi_497'  		=> $card,
				'ccExpirationMonth'  	=> $chargeAuto->source->exp_month,
				'ccExpirationYear'  	=> $chargeAuto->source->exp_year,
				'f1547'  				=> '£'.$autototalStripe,
				'ccNumber' 				=> $chargeAuto->source->last4 
				);			  
				//GET PUT RESULT
				$API_RESULT 	= op_query( $API_URL, 'PUT', $API_DATA, $API_ID, $API_KEY );
				//IF OBJECT NOT FOUND, DISPLAY ERROR MESSAGE
				if( $API_RESULT == "Object not found" ){
				echo 'FAILED TO PUT/UPDATE DATA!';
				//die();
				}
				$sqlCredit='SELECT COUNT(*) AS totalcredit FROM wp_creditcard WHERE credit_partnerid = :creditID';
				$sqlCreditup = $WP_CON->prepare($sqlCredit);
				$sqlCreditup->execute(array(':creditID' => $autocreditID));
				$resultCredit = $sqlCreditup->fetchObject();
				if($resultCredit->totalcredit >0){
				}else{
					$emailStripe	= $current_user->user_email;
					$nameStripe		= $chargeAuto->source->name;
					$monthStripe	= $chargeAuto->source->exp_month;
				 	$yearStripe		= $chargeAuto->source->exp_year; 
					try {
						$sql = "INSERT INTO wp_creditcard(credit_emailaddress,
									credit_partnerid,
									credit_name,
									credit_minbalance,
									credit_topup,
									credit_exmonth,
									credit_exyear) VALUES (
									:cEmail, 
									:cpartnerID, 
									:cName, 
									:cMin, 
									:cPop,
									:cExmonth,
									:cExyear)";
																 
						$stmt = $WP_CON->prepare($sql);			 
						$stmt->bindParam(':cEmail', $emailStripe, PDO::PARAM_STR);		
						$stmt->bindParam(':cpartnerID', $autocreditID, PDO::PARAM_STR);		
						$stmt->bindParam(':cName', $nameStripe, PDO::PARAM_STR);		
						$stmt->bindParam(':cMin', $minAmounts, PDO::PARAM_STR);	
						$stmt->bindParam(':cPop', $autotopupAmount, PDO::PARAM_STR);
						$stmt->bindParam(':cExmonth', $monthStripe, PDO::PARAM_STR);
						$stmt->bindParam(':cExyear', $yearStripe, PDO::PARAM_STR);
						// use PARAM_STR although a number										 
						$stmt->execute();
						}catch(PDOException $err){
						echo "Error: " . $err->getMessage();
						}
					//$WP_CON = null;
					//echo 'Succesfully top up!';
				}	
				echo 'Succesfully top up!';
			}
		}
		die();
	}
	add_action( 'wp_ajax_automaticTop', 'automaticTop' );
	add_action( 'wp_ajax_nopriv_automaticTop', 'automaticTop' );
	function opacity_level( ){
		ob_start( );
		global $FULLNAME;
		$current_user = wp_get_current_user();
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
		switch($getName->data[0]->f1549){
			 case 1: {  
					echo 'class="reward-package-level-one"'; 
					break; 
				}
			 case 2: { 
					echo 'class="reward-package-level-two"';
					break;
				}
			 case 3: { 
					echo 'class="reward-package-level-three"';
					break;
				}
			 case 4: { 
					echo 'class="reward-package-level-four"';
					break;
				}
			 case 5: { 
					echo 'class="reward-package-level-five"'; 
					break;
				}
			 default:{ 
				break;
			 }
		}
		return ob_get_clean( );
	}
	add_shortcode('opacityLevel','opacity_level');//
	function setcreditCard(){
		$setcreditID = isset( $_POST[ 'creditID' ] ) ? $_POST[ 'creditID' ] : '';
		var_dump($setcreditID);
		die();
	}
	add_action( 'wp_ajax_setcreditCard', 'setcreditCard' );
	add_action( 'wp_ajax_nopriv_setcreditCard', 'setcreditCard' );
	function changeCard(){
		$host  		= "db651120122.db.1and1.com"; 
		$database   = "db651120122";
		$user  		= "dbo651120122";
		$password   = "1qazxsw2!QAZXSW@";
		try{
		$WP_CON	= new PDO('mysql:host='.$host.';dbname='.$database.';', $user, $password);
		$WP_CON->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$WP_CON->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
		}catch(PDOException $ERR){
			echo $ERR->getMessage();
			exit();
		}
		$current_user = wp_get_current_user();
		require_once(get_stylesheet_directory() . '/stripe-php/init.php');
		$stripe = array(
		  "secret_key"      => "sk_test_E01Kh96YOzRtkhD5wItn8CDd",
		  "publishable_key" => "pk_test_u289X2Do4OavHR2STjbI2TsL"
		);
		\Stripe\Stripe::setApiKey($stripe['secret_key']);
		$setcreditToken = isset( $_POST[ 'setstripeToken' ] ) ? $_POST[ 'setstripeToken' ] : '';
		$setPartner 	= isset( $_POST[ 'setPartner' ] ) ? $_POST[ 'setPartner' ] : '';
		$setcardName 	= isset( $_POST[ 'setcardName' ] ) ? $_POST[ 'setcardName' ] : '';
		$setcardCvc 	= isset( $_POST[ 'setcardCvc' ] ) ? $_POST[ 'setcardCvc' ] : '';
		$setcardNumber 	= isset( $_POST[ 'setcardNumber' ] ) ? $_POST[ 'setcardNumber' ] : '';
		$customerSet 	= \Stripe\Customer::create(array(
		  'email' 	=> $current_user->user_email,
		  'source'  => $setcreditToken
		));
		$customer = \Stripe\Customer::retrieve($customerSet->id);
		$customerSet= $customer->sources->retrieve($customerSet->default_source);
		//$card = $customer->sources->brand;
		$customerSet->exp_year;
		$customerSet->exp_month;
		$customerSet->last4;
		$customerSet->brand;
		$sqlCredit='SELECT COUNT(*) AS totalcredit FROM wp_settingscredit WHERE setting_cardnumber = :cardID';
		$sqlCreditup = $WP_CON->prepare($sqlCredit);
		$sqlCreditup->execute(array(':cardID' => $customerSet->last4));
		$resultCredit = $sqlCreditup->fetchObject();
		if($resultCredit->totalcredit >0){
		}else{
			$setPrimary='Alternative Card';
			try {
				$sql = "INSERT INTO wp_settingscredit(setting_cardtype,
							setting_cardname,
							setting_cardnumber,
							setting_cvv,
							setting_partnerid,
							setting_expmonth,
							setting_expyear,
							setting_primarycard) VALUES (
							:sCardtype, 
							:sCardname, 
							:sCardnumber, 
							:sCvv, 
							:sPartnerid, 
							:sExpmonth, 
							:sExpyear,
							:sPrimary)";
														 
				$stmt = $WP_CON->prepare($sql);			 
				$stmt->bindParam(':sCardtype', $customerSet->brand, PDO::PARAM_STR);		
				$stmt->bindParam(':sCardname', $setcardName, PDO::PARAM_STR);		
				$stmt->bindParam(':sCardnumber', $setcardNumber, PDO::PARAM_STR);				
				$stmt->bindParam(':sCvv', $setcardCvc, PDO::PARAM_STR);				
				$stmt->bindParam(':sPartnerid', $setPartner, PDO::PARAM_STR);	
				$stmt->bindParam(':sExpmonth', $customerSet->exp_month, PDO::PARAM_STR);
				$stmt->bindParam(':sExpyear', $customerSet->exp_year, PDO::PARAM_STR);
				$stmt->bindParam(':sPrimary', $setPrimary, PDO::PARAM_STR);
				// use PARAM_STR although a number										 
				$stmt->execute();
				}catch(PDOException $err){
				echo "Error: " . $err->getMessage();
				}
			//$WP_CON = null;
			//echo 'Succesfully top up!';
		}	
		echo 'Succesfully top save!';
		die();
	}
	add_action( 'wp_ajax_changeCard', 'changeCard' );
	add_action( 'wp_ajax_nopriv_changeCard', 'changeCard' );
function directCARDPAYMENT(){
// Include library
	$manualID 	= isset( $_POST[ 'directmanualID' ] ) ? $_POST[ 'directmanualID' ] : '';
	var_dump($manualID);
	require get_stylesheet_directory() . '/gocardless-php/vendor/autoload.php';
	// Config vars
	$postargs 		= "https://api-sandbox.gocardless.com/";
	//$postargs 		= "http://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'testing@umbrellasupport.co.uk'&searchNotes=true";
	$request		= "";
	$session 		= curl_init();
	curl_setopt ( $session, CURLOPT_RETURNTRANSFER, true );
	curl_setopt ( $session, CURLOPT_URL, $postargs );
	//curl_setopt ($session, CURLOPT_HEADER, true);
	curl_setopt ( $session, CURLOPT_HTTPHEADER, array(
		'Authorization: Bearer Umbrella_Test',
		'GoCardless-Version: 2015-07-06'
	));
	$response = curl_exec( $session ); 
	curl_close( $session );
	//header("Content-Type: text");
	//echo "CODE: " . $response;
	$getName 		= json_decode( $response );
	$directToken = 'sandbox_N0CPkkFPndE1s_-JAVzzDhgo_ekl3AWVgfldd5ja'; 
	$client = new \GoCardlessPro\Client(array(
	  'access_token' => 'AC00001F02S4003E4G81A05PK6ZAK0T6',
	  'environment'  => \GoCardlessPro\Environment::SANDBOX
	));
	$client->customers()->list();
	var_dump($client);
	die();
	}
add_action( 'wp_ajax_directCARDPAYMENT', 'directCARDPAYMENT' );
add_action( 'wp_ajax_nopriv_directCARDPAYMENT', 'directCARDPAYMENT');

function e3ve_hidden_page() {
	ob_start();
	/*if(!is_user_logged_in()){
    ?>
    <div class="e3ve-hidden-page" style="background: rgba(248, 248, 248, 0.9) none repeat scroll 0 0; height: 100%; left: 0;     position: absolute; top: 0; width: 100%;"><div class="e3ve-hidden-watermark" style="text-align:center; font-size:50px; text-transform:uppercase; position:relative; top:11%"><img src="http://testing.umbrellasupport.co.uk/wp-content/uploads/2016/10/podlock-red.png" width="30%" height="auto" alt=""><div><a id="e3ve-lock-page-popup" href="#">Login</a> to Unlock Page</div></div></div>
    <?php
	}*/
	return ob_get_clean();
}
add_shortcode('e3veHiddenPage','e3ve_hidden_page');

 /*add_action( 'template_redirect', function() {
    if (! is_user_logged_in() ){
		if(is_page(1113)){
		}else{
			wp_redirect( home_url('/') );
			exit();
		}
    }
 });*/
/*function lockShortcode() {
	ob_start();
	require_once( get_stylesheet_directory() . '/facebook/src/Facebook/autoload.php' );
	
	global $getID, $helper, $successLogin;
	
	$successLogin = false;
	
	$fb = new Facebook\Facebook([
	  'app_id' => '1809109289321551', // Replace {app-id} with your app id
	  'app_secret' => 'c708e1816369948058edebc76df52d9d',
	  'default_graph_version' => 'v2.7',
	]);
	$helper = $fb->getRedirectLoginHelper();
	
	
	
	if(isset($_GET['code'])){
		try {
		  $accessToken = $helper->getAccessToken();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  // When Graph returns an error
		  echo 'Graph returned an error: ' . $e->getMessage();
		  exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  // When validation fails or other local issues
		  echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  exit;
		}
		if (!isset($accessToken)) {
		  if ($helper->getError()) {
			header('HTTP/1.0 401 Unauthorized');
			echo "Error: " . $helper->getError() . "\n";
			echo "Error Code: " . $helper->getErrorCode() . "\n";
			echo "Error Reason: " . $helper->getErrorReason() . "\n";
			echo "Error Description: " . $helper->getErrorDescription() . "\n";
			exit;
		  } else {
			header('HTTP/1.0 400 Bad Request');
			echo 'Bad request';
			exit;
		  }
		  //exit;
		}
		
		if(isset($accessToken)){
			// Logged in
			//echo '<h3>Access Token</h3>';
			//var_dump($accessToken->getValue());

			// The OAuth 2.0 client handler helps us manage access tokens
			$oAuth2Client = $fb->getOAuth2Client();

			// Get the access token metadata from /debug_token
			$tokenMetadata = $oAuth2Client->debugToken($accessToken);
			//echo '<h3>Metadata</h3>';
			//var_dump($tokenMetadata);
			// Validation (these will throw FacebookSDKException's when they fail)
			$tokenMetadata->validateAppId('1809109289321551'); // Replace {app-id} with your app id
			// If you know the user ID this access token belongs to, you can validate it here
			//$tokenMetadata->validateUserId('123');
			$tokenMetadata->validateExpiration();

			if (! $accessToken->isLongLived()) {
			  // Exchanges a short-lived access token for a long-lived one
			  try {
				$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
			  } catch (Facebook\Exceptions\FacebookSDKException $e) {
				echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
				exit;
			  }

			  echo '<h3>Long-lived</h3>';
			  //var_dump($accessToken->getValue());
			}

			$_SESSION['fb_access_token'] = (string) $accessToken;
			
			try {
			  // Returns a `Facebook\FacebookResponse` object
				$response = $fb->get('/me?fields=id,name,email,first_name,last_name, gender, birthday,picture',$_SESSION['fb_access_token']);
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
			  echo 'Graph returned an error: ' . $e->getMessage();
			  exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
			  echo 'Facebook SDK returned an error: ' . $e->getMessage();
			  exit;
			}
			
			$user 		= $response->getGraphUser();
			$fbEmail	= $user['email'];
			//var_dump($fbEmail);
			if(!empty($fbEmail)){
				
				$host  		= "db640728737.db.1and1.com";
				$database   = "db640728737";
				$user  		= "dbo640728737";
				$password   = "1qazxsw2!QAZXSW@";
				
				try{
					$WP_CON	= new PDO('mysql:host='.$host.';dbname='.$database.';', $user, $password);
					$WP_CON->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				}catch(PDOException $ERR){
					echo $ERR->getMessage();
					exit();
				}
				
				try{
					$customAPIKEY  	= get_field('custom_api_key','option');// name of the admin
					$customAPIID  	= get_field('custom_api_id','option');// Email Title for the admin
					$postargs 		= "https://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'".$fbEmail."'&searchNotes=true";
					//$postargs 		= "https://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'testing@umbrellasupport.co.uk'&searchNotes=true";
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
					$sendEmail 		= $fbEmail;
					$fbID  	 		= $getName->data[0]->id;
					$fbName 	 	= $getName->data[0]->firstname.' '.$getName->data[0]->lastname;
					$fbEmail   		= $sendEmail;
					$fbLevel   		= $getName->data[0]->f1549;
					$fbAmount  		= $getName->data[0]->f1547;
					$fbCity    		= $getName->data[0]->city;
					$fbtown    		= $getName->data[0]->Town_340;
					$fbcountry    	= $getName->data[0]->County_456;
					$fbAddress 		= $getName->data[0]->address;
					$fbZip     		= $getName->data[0]->zip;
					$fbBemail  		= $getName->data[0]->f1556;
					$fbCphone  		= $getName->data[0]->cell_phone;
					$fbHphone  		= $getName->data[0]->home_phone;
					$fbCountry  	= $getName->data[0]->country;
					$fbAddress2   	= $getName->data[0]->address2;
					$fbOphone     	= $getName->data[0]->office_phone;
					$fbState      	= $getName->data[0]->state;
					$fbCompany    	= $getName->data[0]->company;
					$fbCompanynum  	= $getName->data[0]->f1564;
					$fbwebsite  	= $getName->data[0]->website;
					$packagelower   = $getName->data[0]->f1548;
					$firstUpper		= strtolower($packagelower);
					$fbpackage		= ucfirst($firstUpper);
					$fbAmount    	= $getName->data[0]->f1547;
					$fbAmountSend	= $str = substr($fbAmount,1);
					$fbManager 		= $fbName;
					$fbAgentID    = $getName->data[0]->CallAgent_462;
					
				}catch(Exception $E){
					echo 'Error: ' . $E->getMessage();
					exit();
				}
				
				
				if(!empty($fbpackage)){
					$fbpackageData=$fbpackage;
				}else{
					$fbpackageData="Standard";
				}
				
				$arg= array( 
					'post_type'  	 	=> 'client',
					'meta_query'   		=> array(
						array(
							'key'       => 'partner_id',					
							//'value'     => '77514',
							'value'     => $fbID,
							'compare'   => 'IN',
						)
					)
				);
				
				$the_query = new WP_Query($arg);
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) { $the_query->the_post();
						$getID=get_the_ID();
					}
				}
				//var_dump($getID);
				switch($fbAgentID){
					case 941: $fbAgent="Paul Diu"; break;
					case 791: $fbAgent="Not Known"; break;
					case 818: $fbAgent="Edward Pink"; break;
					case 817: $fbAgent="Dave Knowles"; break;
					case 790: $fbAgent="Katie Smith"; break;
					case 773: $fbAgent="Jeff Ramsay"; break;
					case 741: $fbAgent="Arthur Orin"; break;
					case 740: $fbAgent="Franz Kafka"; break;
					case 816: $fbAgent="Sabrina Ali"; break;
					default: $fbAgent="";break;
				}
				
				if(empty($fbID)){
					//header("Refresh: 0; url=".home_url());
					//echo '<meta http-equiv="refresh" content="0.1;'.home_url().'">';
					echo '<p class="facebook-error-message"><b>✘</b>Email is not registered in ontraport!</p>';
					//exit;
				}else{
					try{
						$sql = $WP_CON->prepare('SELECT COUNT(*) AS total FROM wp_user_profiles_mirror WHERE partner_id = :partnerID');
						$sql->execute(array(':partnerID' => $fbID));
						$result = $sql->fetchObject();
					}catch(PDOException $E){
						echo 'Error: ' . $E->getMessage();
					}
					
					if(email_exists($sendEmail)){
						global  $user_id;
						$login = $sendEmail;
						if(is_email( $login )){
							if( email_exists( $login )) {
							  $userID__ = email_exists($login);
							  $user_info = get_userdata($userID__);
							  $user_id  = $user_info->ID;
							 // var_dump($login);
							}
						}
						
						if($result->total > 0) {
							//$status=0;
						   // echo 'Hello';
							try {
							$sql = "UPDATE wp_user_profiles_mirror   
									   SET full_name  = :fullName,  
										   home_phone = :homePhone, 
										   mobile_phone = :mobilePhone, 
										   postcode = :postCode, 
										   company_name = :companyName 
									 WHERE partner_id = :user_id
								  ";
									
							 $statement = $WP_CON->prepare($sql);
							 $statement->bindValue(":user_id", $fbID);
							 $statement->bindValue(":fullName", $fbName);
							 $statement->bindValue(":homePhone", $fbHphone);
							 $statement->bindValue(":mobilePhone", $fbCphone);
							 $statement->bindValue(":postCode", $fbZip);
							 $statement->bindValue(":companyName", $fbCompany);
							 //$statement->bindValue(":status", $status);
							 $count = $statement->execute();

							//  $conn = null;        // Disconnect
							  //echo 'Updated';
							}
							catch(PDOException $e) {
							  echo $e->getMessage();
							}
						}
						try{
							$sqlImage='SELECT COUNT(*) AS totalImage FROM wp_user_imguploads WHERE uid_PartnerID = :imageUp';
							$sqlImageup = $WP_CON->prepare($sqlImage);
							$sqlImageup->execute(array(':imageUp' => $fbID));
							$resultImage = $sqlImageup->fetchObject();
						}catch(PDOException $ERR){
							echo $ERR->getMessage();
							exit();
						}
						if($resultImage->totalImage >0) {
						
						}else{
							$statusImage=0;
							$urlMerror=get_home_url();
							$urlImage=get_stylesheet_directory_uri().'/images/default-logo.jpg';
							try {
								$sql = "INSERT INTO wp_user_imguploads(ui_HOST,
											ui_PATH,
											ui_URL,
											uid_PartnerID,
											ui_DATEUPLOAD) VALUES (
											:uiHOST, 
											:uiPATH, 
											:uiURL, 
											:uidPartnerID,
											:uiDATEUPLOAD)";
																		 
								$stmt = $WP_CON->prepare($sql);			 
								$stmt->bindParam(':uiHOST', $urlMerror, PDO::PARAM_STR);		
								$stmt->bindParam(':uiPATH', $urlMerror, PDO::PARAM_STR);		
								$stmt->bindParam(':uiURL', $urlImage, PDO::PARAM_STR);	
								$stmt->bindParam(':uidPartnerID', $fbID, PDO::PARAM_STR);
								$stmt->bindParam(':uiDATEUPLOAD', date ("Y-m-d H:i:s"), PDO::PARAM_STR);
								// use PARAM_STR although a number										 
								$stmt->execute();
								}catch(PDOException $err){
								echo "Error: " . $err->getMessage();
								}
							//$WP_CON = null;
						}
						try{
							$sqlUser='SELECT COUNT(*) AS totalUser FROM users WHERE customer_id = :userUp';
							$sqlUserup = $WP_CON->prepare($sqlUser);
							$sqlUserup->execute(array(':userUp' => $fbID));
							$resultUser = $sqlUserup->fetchObject();
						}catch(PDOException $ERR){
							echo $ERR->getMessage();
							exit;
						}
						if($resultUser->totalUser >0){
						}else{
							$merchant="Merchant";
							$userAllow = 'Yes';
							try {
								$saveUser = "INSERT INTO users(email,
											user_type,
											customer_id,
											allow_remarketing) VALUES (
											:userEmail,  
											:userType, 
											:userID, 
											:userAllow)";							 
								$stmtUser = $WP_CON->prepare($saveUser);			 
								$stmtUser->bindParam(':userEmail', $fbEmail, PDO::PARAM_STR);		
								//$stmtUser->bindParam(':userPassword', md5($password), PDO::PARAM_STR);		
								$stmtUser->bindParam(':userType', $merchant, PDO::PARAM_STR);	
								$stmtUser->bindParam(':userID', $fbID, PDO::PARAM_STR);
								$stmtUser->bindParam(':userAllow', $userAllow, PDO::PARAM_STR);
								// use PARAM_STR although a number										 
								$stmtUser->execute();			
							}catch(PDOException $err){
								echo "Error: " . $err->getMessage();
							}
							//$WP_CON = null;
						}
						$userargs = array(
							'ID' 			 => $user_id,
							'first_name'	 => $getName->data[0]->firstname,
							'last_name'		 => $getName->data[0]->lastname,
							'user_login' 	 => $fbEmail,
							'nickname'		 => $fbName,
							'user_email'	 => $fbEmail,
							'user_pass' 	 => wp_generate_password( 8, false ),
							'display_name'	 => $fbName,
							'role'			 => 'subscriber'
						);
						$update_post = array(
							'ID'    		=> $getID,
							'post_title'    => $fbName,
							'post_status'   => 'publish',          
							'post_type'     => 'client' 
						);
						$postId = wp_update_post($update_post);
						
						switch($fbLevel){
						  case 1		: $level='Level 1'; break;
						  case 2		: $level='Level 2'; break;
						  case 3		: $level='Level 3'; break;
						  case 4		: $level='Level 4'; break;
						  case 5		: $level='Level 5'; break;
						  default		: $level='Level 1'; break;
						}
						
						update_post_meta( $postId, 'full_name', $fbName);
						update_post_meta( $postId, 'address_line_1', $fbAddress);
						update_post_meta( $postId, 'address_line_2', $fbAddress2);
						update_post_meta( $postId, 'town', $fbtown);
						update_post_meta( $postId, 'city', $fbCity);
						update_post_meta( $postId, 'postcode', $fbZip);
						update_post_meta( $postId, 'home_phone', $fbHphone);
						update_post_meta( $postId, 'mobile_phone', $fbCphone );										
						update_post_meta( $postId, 'company_city', $fbCity );
						update_post_meta( $postId, 'country', $fbcountry );
						update_post_meta( $postId, 'company_address_line_1', $fbAddress);
						update_post_meta( $postId, 'company_address_line_2', $fbAddress2);										
						update_post_meta( $postId, 'company_postcode', $fbZip);
						update_post_meta( $postId, 'company_name', $fbCompany);
						update_post_meta( $postId, 'company_number', $fbCompanynum);
						update_post_meta( $postId, 'company_website', $fbwebsite);
						update_post_meta( $postId, 'company_office_phone', $fbHphone);
						update_post_meta( $postId, 'company_mobile_phone', $fbCphone);
						update_post_meta( $postId, 'company_town', $fbtown);
						update_post_meta( $postId, 'company_business_email', $fbEmail);
						update_post_meta( $postId, 'package_level', $level);
						update_post_meta( $postId, 'account_manager', $fbAgent);
						update_post_meta( $postId, 'account_balance', $fbAmount);
						update_post_meta( $postId, 'package', $fbpackageData);
						update_post_meta( $postId, 'partner_id', $fbID);
						$update_id = wp_update_user($userargs);
						$current_user = get_user_by( 'id', $update_id );
						// set the WP login cookie
						wp_set_auth_cookie( $update_id, false, is_ssl() );
						$successLogin = true;
						//echo '<p>!</p>';
						
						//header("Refresh: 0; url=".home_url());
						echo '<meta http-equiv="refresh" content="0.1;'.home_url().'">';

					}else{
						$new_post = array(
							'post_title'    => $fbName,
							'post_status'   => 'publish',          
							'post_type'     => 'client'
						);
						//insert the the post into database by passing $new_post to wp_insert_post
						//store our post ID in a variable $pid
						$pid = wp_insert_post($new_post);
						 switch($fbLevel){
						  case 1		: $level='Level 1'; break;
						  case 2		: $level='Level 2'; break;
						  case 3		: $level='Level 3'; break;
						  case 4		: $level='Level 4'; break;
						  case 5		: $level='Level 5'; break;
						  default		: $level='Level 1'; break;
						}
						if($result->total > 0) {
							//$status=0;
						   // echo 'Hello';
							try {
							$sql = "UPDATE wp_user_profiles_mirror   
									   SET full_name  = :fullName,  
										   home_phone = :homePhone, 
										   mobile_phone = :mobilePhone, 
										   postcode = :postCode, 
										   company_name = :companyName 
									 WHERE partner_id = :user_id
								  ";
									
							 $statement = $WP_CON->prepare($sql);
							 $statement->bindValue(":user_id", $fbID);
							 $statement->bindValue(":fullName", $fbName);
							 $statement->bindValue(":homePhone", $fbHphone);
							 $statement->bindValue(":mobilePhone", $fbCphone);
							 $statement->bindValue(":postCode", $fbZip);
							 $statement->bindValue(":companyName", $fbCompany);
							 //$statement->bindValue(":status", $status);
							 $count = $statement->execute();

							 // $conn = null;        // Disconnect
							  //echo 'Updated';
							}
							catch(PDOException $e) {
							  echo $e->getMessage();
							}
						}
						try{
						$sqlImage='SELECT COUNT(*) AS totalImage FROM wp_user_imguploads WHERE uid_PartnerID = :imageUp';
						$sqlImageup = $WP_CON->prepare($sqlImage);
						$sqlImageup->execute(array(':imageUp' => $fbID));
						$resultImage = $sqlImageup->fetchObject();
						}catch(PDOException $ERR){
							echo $ERR->getMessage();
							exit();
						}
						if($resultImage->totalImage >0) {
						}
						else{
							$statusImage=0;
							$urlMerror=get_home_url();
							$urlImage=get_stylesheet_directory_uri().'/images/default-logo.jpg';
							try {
								$sql = "INSERT INTO wp_user_imguploads(ui_HOST,
											ui_PATH,
											ui_URL,
											uid_PartnerID
											ui_DATEUPLOAD) VALUES (
											:uiHOST,
											:uiPATH,
											:uiURL,
											:uidPartnerID,
											:uiDATEUPLOAD)";
								$stmt = $WP_CON->prepare($sql);
								$stmt->bindParam(':uiHOST', $urlMerror, PDO::PARAM_STR);
								$stmt->bindParam(':uiPATH', $urlMerror, PDO::PARAM_STR);
								$stmt->bindParam(':uiURL', $urlImage, PDO::PARAM_STR);
								$stmt->bindParam(':uidPartnerID', $fbID, PDO::PARAM_STR);
								$stmt->bindParam(':uiDATEUPLOAD', date ("Y-m-d H:i:s"), PDO::PARAM_STR);
								// use PARAM_STR although a number
								$stmt->execute();
								}catch(PDOException $err){
								echo "Error: " . $err->getMessage();
								}
							//$WP_CON = null;
						}
						try{
						$sqlUser='SELECT COUNT(*) AS totalUser FROM users WHERE customer_id = :userUp';
						$sqlUserup = $WP_CON->prepare($sqlUser);
						$sqlUserup->execute(array(':userUp' => $fbID));
						$resultUser = $sqlUserup->fetchObject();
						}catch(PDOException $ERR){
							echo $ERR->getMessage();
							exit();
						}
						if($resultUser->totalUser >0){
						}else{
							$merchant="Merchant";
							$userAllow = 'Yes';
							try {
								$saveUser = "INSERT INTO users(email,
											user_type,
											customer_id,
											allow_remarketing) VALUES (
											:userEmail,
											:userType,
											:userID,
											:userAllow)";
								$stmtUser = $WP_CON->prepare($saveUser);
								$stmtUser->bindParam(':userEmail', $fbEmail, PDO::PARAM_STR);
								//$stmtUser->bindParam(':userPassword', md5($password), PDO::PARAM_STR);
								$stmtUser->bindParam(':userType', $merchant, PDO::PARAM_STR);
								$stmtUser->bindParam(':userID', $fbID, PDO::PARAM_STR);
								$stmtUser->bindParam(':userAllow', $userAllow, PDO::PARAM_STR);
								// use PARAM_STR although a number
								$stmtUser->execute();
							}catch(PDOException $err){
								echo "Error: " . $err->getMessage();
							}
							//$WP_CON = null;
						}
						//we now use $pid (post id) to help add out post meta data
						add_post_meta( $pid, 'full_name', $fbName, true );
						add_post_meta( $pid, 'email_address', $fbEmail, true );
						add_post_meta( $pid, 'address_line_1', $fbAddress, true );
						add_post_meta( $pid, 'address_line_2', $fbAddress2, true );
						add_post_meta( $pid, 'town', $fbtown, true );
						add_post_meta( $pid, 'city', $fbCity, true );
						add_post_meta( $pid, 'mobile_phone', $fbCphone, true );
						add_post_meta( $pid, 'home_phone', $fbHphone, true );
						add_post_meta( $pid, 'country', $fbcountry, true );
						add_post_meta( $pid, 'postcode', $fbZip, true );
						add_post_meta( $pid, 'company_postcode', $fbZip, true );
						add_post_meta( $pid, 'company_name', $fbCompany, true );
						add_post_meta( $pid, 'company_number', $fbCompanynum, true );
						add_post_meta( $pid, 'company_postcode', $fbZip, true);
						add_post_meta( $pid, 'company_website', $fbwebsite, true );
						add_post_meta( $pid, 'company_town', $fbtown, true );
						add_post_meta( $pid, 'company_address_line_1', $fbAddress, true);
						add_post_meta( $pid, 'company_address_line_2', $fbAddress2, true);
						add_post_meta( $pid, 'company_city', $fbCity, true );
						add_post_meta( $pid, 'company_office_phone', $fbHphone, true );
						add_post_meta( $pid, 'company_mobile_phone', $fbCphone, true );									
						add_post_meta( $pid, 'company_business_email', $fbBemail, true );										
						add_post_meta( $pid, 'company_office_phone', $fbOphone, true );
						add_post_meta( $pid, 'account_manager', $fbAgent, true );
						add_post_meta( $pid, 'partner_id', $fbID, true );
						add_post_meta( $pid, 'package_level', $level, true );
						add_post_meta( $pid, 'account_manager', $fbAgent, true );
						add_post_meta( $pid, 'account_balance', $fbAmount, true );
						add_post_meta( $pid, 'package', $fbpackageData, true );
						$userargs = array(
							'first_name'	 => $getName->data[0]->firstname,
							'last_name'		 => $getName->data[0]->lastname,
							'user_login' 	 => $sendEmail,
							'nickname'		 => $fbName,
							'user_email'	 => $sendEmail,
							'user_pass' 	 => wp_generate_password( 8, false ),
							'display_name'	 => $fbName,
							'role'			 => 'subscriber'
						);
						$user_id = wp_insert_user($userargs);
						$current_user = get_user_by( 'id', $user_id );
						// set the WP login cookie
						wp_set_auth_cookie( $user_id, false, is_ssl() );
						$successLogin = true;
						
						//header("Refresh: 0; url=".home_url());
						echo '<meta http-equiv="refresh" content="0.1;'.home_url().'">';
					}
				}
			}else{
				header("Refresh: 0; url=".home_url());
				echo '<p class="error-message"><b>✘</b>The email used in facebook is not verified!';
				exit;
			}
		}
	}
	if(!is_user_logged_in()){
		if(!is_front_page()|| !is_home()){
    ?>
			<script type="text/javascript">
				jQuery(document).ready(function($){
				   $('#page-content').empty().hide();		
				   $('center h1').empty().hide();		
				});
			</script>
			<div class="locker">
				<div class="e3ve-hidden-page" style="color:#000;background: rgba(248, 248, 248, 0.9) none repeat scroll 0 0; height: 100%; left: 0;     position: absolute; top: 0; width: 100%;"><div class="e3ve-hidden-watermark" style="text-align:center; font-size:50px; text-transform:uppercase; position:relative; top:11%"><img src="http://testing.umbrellasupport.co.uk/wp-content/uploads/2016/10/podlock-red.png" width="30%" height="auto" alt=""><div><a id="e3ve-lock-page-popup" href="#<?php// echo home_url();?>">Login</a> to Unlock Page</div></div></div>
			</div>
			<div class="e3ve-hidden-page-login">
			  <div class="e3ve-hidden-page-inner">
				<div class="modal-header">
				  <h2><img width="50%" height="auto" style="margin:0 auto; text-align:center; display:block" alt="umbrella support centre" src="<?php echo get_template_directory_uri().'/images/Umbrella-logo.png';?>"></h2>
				  <a href="#" class="btn-close" aria-hidden="true">×</a> </div>
				<div class="modal-body">
				    <div class="modal-body-left">
						<ul class="tab">
							<li><a onclick="openTabnav(event, 'Login')" class="tablinks active" href="#/">Login</a></li>
							<li><a onclick="openTabnav(event, 'SignUp')" class="tablinks" href="#/">Signup</a></li>
						</ul>
						<div style="display:block;" class="tabcontent"  id="Login">
							<div class="log"></div>
								<form action="" method="post">
								  <div class="e3ve-modal-container">
									<label><b>Email Address</b></label>
									<input type="text" placeholder="Enter Email Address" name="email" id="email" required="">
									<label><b>Password</b></label>
									<input type="password" placeholder="Enter Password" name="psw" id="password" required="">
									<button type="submit" value="register" id="submit">Login to Your Support Centre &gt;&gt;</button>
									<!--<input type="checkbox" checked="checked" class="e3ve-remember">
									Remember me --></div>
								</form>
								<script type="text/javascript">
								jQuery(document).ready(function($){
									$("#submit").click(function(){
										var email = $("#email").val();
										var password = $("#password").val();
										var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
										// Returns successful data submission message when the entered information is stored in database.
										if(email==''||password==''){
											$( ".log" ).append("<p class="+'error-message'+"><b>✘</b>Email and password should not be empty!</p>");
										}else{
											// AJAX Code To Submit Form.
											$.ajax({
												type: "POST",
												url: ajaxurl,
												timeout: 3000,
												data:{
												   action: "registrationRequest",
												   email:email,
												   password:password
												},
												cache: false,
												success: function(result){
													$(".log").html(result);
													$( "div.modal-dialog" ).addClass( "modal-login" );
													window.top.location.reload();
												}
											});
										}
										return false;
									});
								 });	
								</script>
							<div class="e3ve-modal-divider">OR</div>
							<div class="modal-body-right">
								<h3>Sign in with</h3>
								 <?php
									$permissions = ['email','public_profile','user_birthday']; // Optional permissions
									$loginUrl = $helper->getLoginUrl('http://testing.umbrellasupport.co.uk', $permissions);
									echo '<a href="' . htmlspecialchars($loginUrl) . '"><img src="'.get_stylesheet_directory_uri().'/images/facebook.png"></a>';

									if(isset($successLogin) && $successLogin == true){
										echo '<div class="facebook-message-success">';
										echo '<div style="position:relative;">';
										echo '<div style="text-align:left" class="facebook-message-right">';
										echo '<img src="'.get_template_directory_uri().'/images/Loading-Circle-Large-Red.gif">';	
										echo '</div>';
										echo '<div class="facebook-message-left">';
										echo '<h2><img alt="umbrella support centre" src="'.get_template_directory_uri().'/images/Umbrella-logo.png"></h2>';
										echo '<h3 >You have Successfully logged into Your Account.</h3>';
										echo '<h3 >Please wait for a few seconds...</h3>';
										//echo '<p>!</p>';
										echo '</div>';
										echo '</div>';
										echo '</div>';
								?>
										<script type="text/javascript">
											jQuery(document).ready(function($){
												$( "#home-content div.modal-dialog" ).addClass( "modal-login" );
											});
										</script>
								<?php
									}
								?>
							</div>
						</div> 
						<div style="" class="tabcontent" id="SignUp">
							<div class="e3ve-modal-container">
								<script type="text/javascript" src="//forms.ontraport.com/v2.4/include/formEditor/genbootstrap.php?method=script&uid=p2c7818f328&version=1"></script> 
							</div>
						</div>
					</div>
				</div>
			  </div>
			</div>
			<script type="text/javascript">
			jQuery(document).ready(function(){
				$('#preloader').show();
				$.ajax({
				   type: "POST", // HTTP method POST or GET
				   url: "<?php echo admin_url('admin-ajax.php'); ?>", //Where to make Ajax calls
				   //dataType:"text", // Data type, HTML, json etc.
				   data:{action:'getleads'},
				   success:function(response){
					 //responseaction
					 //alert(response);
					 //process_tickers(response);
					//jQuery('.vticker ul').html(response);
					//process_tickers(response);
					//load_ticker();
				   },
				   error:function (xhr, ajaxOptions, thrownError){
					//alert("Error: " + thrownError);
				   },
				   complete: function(){
					$('#preloader').hide();
				   }
				});
			});
			
			jQuery( document ).ready( function() {
				setTimeout(function(){
				$('.modal').delay(500).addClass('loaded');
				}, 3000);
				$('.btn-close,  .btn').click( function() {
					$('.modal').removeClass('loaded');
				});
			});

			jQuery( document ).ready(function() {
				$('.modal-dialog').css({
					'position' : 'absolute',
					'left' : '50%',
					'top' : '50%',
					'margin-left' : -$('.modal-dialog').outerWidth()/2,
					'margin-top' : -$('.modal-dialog').outerHeight()/2
				});
			});

			function openTabnav(evt, tabnavName) {
				var i, tabcontent, tablinks;
				tabcontent = document.getElementsByClassName("tabcontent");
				for (i = 0; i < tabcontent.length; i++) {
					tabcontent[i].style.display = "none";
				}
				tablinks = document.getElementsByClassName("tablinks");
				for (i = 0; i < tablinks.length; i++) {
					tablinks[i].className = tablinks[i].className.replace(" active", "");
				}
				document.getElementById(tabnavName).style.display = "block";
				evt.currentTarget.className += " active";
			}

			$(document).ready(function() {
				$("#e3ve-lock-page-popup").click(function(){
					$(".e3ve-hidden-page-login").show();
					$(".e3ve-hidden-page-login").addClass('loaded');
				});
				$(".btn-close, .btn").click( function() {
					$(".e3ve-hidden-page-login").hide();
				});
			});
		</script>
	<?php
		}else{
			
		}
	}else{
	?>

	<?php	
	}
	return ob_get_clean();
}
add_shortcode('lock_shortcode','lockShortcode');*/
function lockShortcode() {
	ob_start();
	if(!is_user_logged_in()){
		if(!is_front_page()){
    ?>
			<script type="text/javascript">
				jQuery(document).ready(function($){
				   $('#page-content').empty().hide();		
				   $('center h1').empty().hide();		
				});
			</script>
			<div class="locker">
				<div class="e3ve-hidden-page" style="color:#000;background: rgba(248, 248, 248, 0.9) none repeat scroll 0 0; height: 100%; left: 0;     position: absolute; top: 0; width: 100%;"><div class="e3ve-hidden-watermark" style="text-align:center; font-size:50px; text-transform:uppercase; position:relative; top:11%"><img src="http://testing.umbrellasupport.co.uk/wp-content/uploads/2016/10/podlock-red.png" width="30%" height="auto" alt=""><div><a id="e3ve-lock-page-popup" href="<?php echo home_url();?>">Login</a> to Unlock Page</div></div></div>
			</div>
	<?php
		}else{
			
		}
	}else{
	?>

	<?php	
	}
	return ob_get_clean();
}
add_shortcode('lock_shortcode','lockShortcode');
?>