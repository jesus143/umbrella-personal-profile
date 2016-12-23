<?php
	require_once( get_stylesheet_directory() . '/facebook/src/Facebook/autoload.php' );
	
	global $getID, $helper, $successLogin,$getName;
	
	$successLogin = false;
	
	$fb = new Facebook\Facebook([
	  'app_id' => '1809109289321551', // Replace {app-id} with your app id
	  'app_secret' => 'c708e1816369948058edebc76df52d9d',
	  'default_graph_version' => 'v2.7'
	  
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
?>
<!doctype html>
<!--[if lt IE 7]> <html class="ie6 oldie"> <![endif]-->
<!--[if IE 7]>    <html class="ie7 oldie"> <![endif]-->
<!--[if IE 8]>    <html class="ie8 oldie"> <![endif]-->
<html class="" <?php language_attributes();?>>
<head>
<meta charset="<?php bloginfo('charset');?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>
<?php wp_title('&laquo;', true, 'right');?>
<?php bloginfo('name');?>
</title>
<link href="<?php bloginfo('stylesheet_url'); ?>" rel="stylesheet" type="text/css">
<link href="<?php bloginfo('stylesheet_directory'); ?>/responsive/fluid.css" rel="stylesheet" type="text/css">
<link href="<?php bloginfo('stylesheet_directory'); ?>/css/bottom-three-column-boxes.css" rel="stylesheet" type="text/css">
<link href="<?php bloginfo('stylesheet_directory'); ?>/css/responsive-menu.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/wysiwyg.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/slider/css/demo.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/slider/flexslider.css" type="text/css" media="screen" />
<link href="<?php bloginfo('stylesheet_directory'); ?>/css/foundation.css" rel="stylesheet" type="text/css">
<link href="<?php bloginfo('stylesheet_directory'); ?>/responsive/boilerplate.css" rel="stylesheet" type="text/css">
<link href="<?php bloginfo('stylesheet_directory'); ?>/css/prism.css" rel="stylesheet" type="text/css">
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<link rel='stylesheet prefetch' href='http://netdna.bootstrapcdn.com/font-awesome/3.1.1/css/font-awesome.css'>
<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_directory'); ?>/datatables/media/css/jquery.dataTables.css">
<script defer src="<?php bloginfo('stylesheet_directory'); ?>/slider/jquery.flexslider.js"></script>
<script async src="<?php bloginfo('stylesheet_directory'); ?>/js/prism.js"></script>
<!-- Slider Modernizr -->
<script async src="<?php bloginfo('stylesheet_directory'); ?>/slider/js/modernizr.js"></script>
<!-- Optional FlexSlider Additions -->
<script defer src="<?php bloginfo('stylesheet_directory'); ?>/slider/js/demo.js"></script>
<script async src="http://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script async type="text/javascript" src="https://js.stripe.com/v2/"></script>
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
				jQuery('.vticker ul').html(response);
				//process_tickers(response);
				//load_ticker();
			   },
			   error:function (xhr, ajaxOptions, thrownError){
				alert("Error: " + thrownError);
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
			});
			$(".btn-close, .btn").click( function() {
				$(".e3ve-hidden-page-login").hide();
			});
		});
	</script>
<!--[if lt IE 9]>
<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
	<script async src="<?php bloginfo('stylesheet_directory'); ?>/responsive/respond.min.js"></script>
	<?php wp_enqueue_script("jquery");?>
	<?php wp_head();?>
	<script async src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
	<script>
		function delete_cookie(){
			document.cookie = "played=0"+";expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/";
		}
		function swfLoadEvent(fn){
			//Ensure fn is a valid function
			if(typeof fn !== "function"){ return false; }
			//This timeout ensures we don't try to access PercentLoaded too soon
			
			var initialTimeout = setTimeout(function (){
			//Ensure Flash Player's PercentLoaded method is available and returns a value
				if(typeof fn.PercentLoaded !== "undefined" && fn.PercentLoaded()){
					//Set up a timer to periodically check value of PercentLoaded
					var loadCheckInterval = setInterval(function (){
						//Once value == 100 (fully loaded) we can do whatever we want
						if(fn.PercentLoaded() === 100){
							//Execute function
							swfobject.getObjectById('flashcontent').GotoFrame(90);
							//Clear timer
							clearInterval(loadCheckInterval);
						}
					}, 1500);
				}
			}, 200);

			
		}
		
		function played(){
			var ca = document.cookie.split(';');
			for(var i=0; i<ca.length; i++) {
			  var c = ca[i];
			  while (c.charAt(0)==' ') c = c.substring(1,c.length);
			  if (c.indexOf("played=") == 0) return 1;
			}
			var date = new Date();
			var days = 7;
			date.setTime(date.getTime()+(days*24*60*60*1000));
			document.cookie = "played=1"+"; expires="+date.toGMTString()+"; path=/";
			return 0;
		}
		var callback = function (e){		 
			//Only execute if SWFObject embed was successful
			if(!e.success || !e.ref){ return false; }
		 
			setTimeout(function checkSWFExistence(){
				if(typeof e.ref.PercentLoaded !== "undefined"){
					swfLoadEvent(e.ref);
				} else {
					swfLoadEvent();
				}
			}, 1300);
		 
		};
		
		function load_swf(){			
			if(played() == 0){
				var flashvars = {};
				var params                  =   {};
				params.play					=   "true";
				params.menu                 =   "false";
				params.scale                =   "noscale";
				params.wmode                =   "transparent";
				params.allowScriptAccess    =   "always";
				params.loop					=   "false";
				params.bgcolor				=   "#FFFFFF";				
				var attributes              =   {};
				swfobject.embedSWF("http://testing.umbrellasupport.co.uk/wp-content/uploads/2016/09/Umbrella-intro-small.swf", "flashcontent", "100%", "100%", "7", false, flashvars, params, attributes, callback);
			}else{

				var par = document.getElementById('flashcontent');
				var img = document.createElement('img');
				img.src = 'http://portal.umbrellasupport.co.uk/wp-content/uploads/2016/03/logo-1.png';
				par.appendChild(img);
			}
		}
	</script>
	
	<style>
		.vticker{
			width: 290px;
			height: 143px;
			overflow:auto;
		}
		.vticker ul{
			padding: 0;
		}
		.vticker li{
			list-style: none;
		}
		.tickerspan{
			color:#F00;
			font-weight:bold;
			font-size: 14px;
			padding: 5px;
		}
		.tickervalue{
			font-weight: bold;
		}
		.thumb-lead-container{
			margin: 10px;
		}
		.thumb-lead-img{
			float:left;
		}
		.thumb-lead-img img{
			display:block;
		}
		.thumb-lead-content{
			margin-left: 80px;
		}
				
		body {
			
			font-family: "Verdana",Helvetica,Arial,sans-serif !important;
			font-size:13px  !important;
			line-height: 1.231 !important;
			
		}
		ol, ul {
			margin-top: 0 !important;
			margin-bottom: 0 !important;
			list-style:none;
		}

		#header-right h2 img{

			-webkit-box-sizing: unset;
			-moz-box-sizing: unset;
			box-sizing: unset;
			
		}


		.h1, .h2, .h3, h1, h2, h3 {
			margin-top: 16px !important;
			margin-bottom: 10px;
		}


		 * {
			-webkit-box-sizing: unset !important;
			-moz-box-sizing: unset !important;
			box-sizing: unset !important;
		} 

		.modal * {
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
		}		
	</style>
</head>
<body <?php body_class(); ?>>

<div class="gridContainer clearfix">
<div id="outside-wrapper">
<div id="header">
<?php
	if(is_user_logged_in()){
		$customAPIKEY  = get_field('custom_api_key','option');// name of the admin
		$customAPIID  = get_field('custom_api_id','option');// Email Title for the admin	
		$current_user = wp_get_current_user();
		//$customAPIKEY  = get_field('custom_api_key','option');// name of the admin
		//$customAPIID  = get_field('custom_api_id','option');// Email Title for the admin
		//echo "Email Address: " . $current_user->user_email; 
		//$postargs = "http://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'testing@umbrellasupport.co.uk'&searchNotes=true";
		$postargs = "http://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'".$current_user->user_email."'&searchNotes=true";

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
		//var_dump($getName);
		if(isset($getName->data) && count($getName->data) > 0){
			$acount_id		= $getName->data[0]->id;
			$acount_balance	= $getName->data[0]->f1547;
			$packagelower	= $getName->data[0]->f1548;
		}
		
		//echo '<br /><br />Name: '. $getName->data[0]->f1549;
	}
	
	//var_dump($getName);
?>
  <div id="header-top">
    <div class="partner-id-top">
      <p>PARTNER ID: <span><strong><?php if(!is_user_logged_in()||empty($acount_id)){echo 'xxxxxx'; }else{ echo $acount_id; } ?></strong></span></p>
    </div>
    <div class="package-type-top">
      <p>PACKAGE: <span><strong>
	  <?php if(!is_user_logged_in()||empty($packagelower)){
		  echo 'Standard';
		}else{ 
			$firstUpper		= strtolower($packagelower);
			$package		= ucfirst($firstUpper);
			switch($package){
				case 'Standard' : echo 'Standard'; break;
				case 'Bronze'	: echo 'Bronze'; break;
				case 'Silver'	: echo 'Silver'; break;
				case 'Gold'		: echo 'Gold'; break;
				default			: echo 'Standard'; break;
			} 
		} 
	  ?></strong></span></p>
    </div>
    <div class="account-balance-top">
      <p>ACCOUNT BALANCE: <span><strong><?php if(empty($acount_balance)){ echo '£x.xx'; }else{ echo $acount_balance; } ?></strong></span></p>
    </div>
    <ul>
      <!--<li><a href="index.php">Home</a><div class="menu-border"></div></li>
      <li><span> | </span></li>
      <li><a href="/my-account">My Account</a><div class="menu-border"></div></li>
      <li><span> | </span></li>
      <li><a href="/contact-us">Contact Us</a><div class="menu-border"></div></li>
      <li><span> | </span></li>-->
      <?php echo get_option('of_topmenu') ?>
      <li>
		<?php if(is_user_logged_in()){ ?>
		  <a href="<?php echo wp_logout_url( home_url() ); ?>">Log Out</a>
		<?php }else{ ?>
		  <a href="<?php echo home_url(); ?>">Log In</a>
		<?php } ?>
	  <div class="menu-border"></div></li>
	</ul>

	<?php 
		if(is_user_logged_in())
		{
   
		    $current_user = wp_get_current_user();
		    //$customAPIKEY  = get_field('custom_api_key','option');// name of the admin
		    //$customAPIID  = get_field('custom_api_id','option');// Email Title for the admin
		    //echo "Email Address: " . $current_user->user_email; 
		    //$postargs = "http://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'testing@umbrellasupport.co.uk'&searchNotes=true";
		    $postargs = "http://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'".$current_user->user_email."'&searchNotes=true";
		   
		    $session = curl_init();
		    curl_setopt ($session, CURLOPT_RETURNTRANSFER, true);
		    curl_setopt ($session, CURLOPT_URL, $postargs);
		    //curl_setopt ($session, CURLOPT_HEADER, true);
		    curl_setopt ($session, CURLOPT_HTTPHEADER, array(
		      'Api-Appid:2_7818_AFzuWztKz',
		      'Api-Key:fY4Zva90HP8XFx3'
		    ));
		    $response = curl_exec($session); 
		    curl_close($session);
		    //header("Content-Type: text");
		    //echo "CODE: " . $response;
		    $getName = json_decode($response);  

		    $acount_id  = $getName->data[0]->id;
		    $acount_balance = $getName->data[0]->f1547;
		    $packagelower = $getName->data[0]->f1548;
		    //var_dump($getName);

		    if( isset($acount_id) AND $acount_id != '' ){
		      // $accountid = $getName->data[0]->id;
		      $accountid = $acount_id;
		    }
		    else{
		      $accountid = 00000;
		    }

		    $wpdb_b = new wpdb( "dbo656995985", "umbrella1986", "db656995985", "db656995985.db.1and1.com" );
			$rows = $wpdb_b->get_results( "SELECT count(*) as queuecount FROM queue WHERE partner_id=".$accountid." AND status='Pending' ORDER BY queue_id ASC", 'ARRAY_A' );
			$queuecount = $rows[0]['queuecount'];

			echo '<script type="text/javascript">
				jQuery(document).ready(function(){
					$(\'.queuecount\').html(\'<span id="ump-unread-notifications" style="color: red;">('.$queuecount.')</span> Queue\');
				});
				</script>';
	    }
	?>

  </div>
  <div id="header-left" style="padding:5px; width:550px; height: 154px;">
	<?php if(!is_user_logged_in()){	?>
			<h1><a href="<?php bloginfo('home'); ?>"><img src="http://portal.umbrellasupport.co.uk/wp-content/uploads/2016/03/logo-1.png"></a></h1>
			<script  type="text/javascript">
				delete_cookie();
			</script>
	<?php }else{ ?>
	<div id="flashcontent" style="width:550px; height: 154px; border: 0px solid #F00; margin: 0px;"></div>
	<script  type="text/javascript">
		load_swf();
	</script>
	<?php } ?>
  </div>
  <div id="header-right" style="float:right">
    <h2><img width="1000" height="685" alt="Plugin Your Business" src="<?php echo get_option('of_businessconnect') ?>"></h2>
  </div>
  <div id="nav">
    <div id="show-nav"><a href="#menu" class="box-shadow-menu"></a></div>
    <?php wp_nav_menu(array('container_class' => 'main-nav', 'container' => 'nav')); ?>
  </div>
</div>
<script type="text/javascript">
</script>
<?php echo do_shortcode('[lock_shortcode]');?>