<?php
/*
*Template Name:Hidden Page
*/
get_header(); ?>
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
						require_once( get_stylesheet_directory() . '/facebook/src/Facebook/autoload.php' );
						$fb = new Facebook\Facebook([
						  'app_id' => '1809109289321551', // Replace {app-id} with your app id
						  'app_secret' => 'c708e1816369948058edebc76df52d9d',
						  'default_graph_version' => 'v2.7',
						  ]);
						$helper = $fb->getRedirectLoginHelper();
						$permissions = ['email','public_profile','user_birthday']; // Optional permissions
						$loginUrl = $helper->getLoginUrl('http://testing.umbrellasupport.co.uk', $permissions);
						echo '<a href="' . htmlspecialchars($loginUrl) . '"><img src="'.get_stylesheet_directory_uri().'/images/facebook.png"></a>';
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
							if (! isset($accessToken)) {
							  if ($helper->getError()) {
								header('HTTP/1.0 401 Unauthorized');
								echo "Error: " . $helper->getError() . "\n";
								echo "Error Code: " . $helper->getErrorCode() . "\n";
								echo "Error Reason: " . $helper->getErrorReason() . "\n";
								echo "Error Description: " . $helper->getErrorDescription() . "\n";
							  } else {
								header('HTTP/1.0 400 Bad Request');
								echo 'Bad request';
							  }
							  //exit;
							}
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
							  var_dump($accessToken->getValue());
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
							if( ! empty( $fbEmail ) ){
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
								$customAPIKEY  	= get_field('custom_api_key','option');// name of the admin
								$customAPIID  	= get_field('custom_api_id','option');// Email Title for the admin
								$postargs 		= "http://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'".$fbEmail."'&searchNotes=true";
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
									header("Refresh: 0; url=".home_url());
									echo '<p class="facebook-error-message"><b>✘</b>Email is not registered in ontraport!</p>';
								}else{
									$sql = $WP_CON->prepare('SELECT COUNT(*) AS total FROM wp_user_profiles_mirror WHERE partner_id = :partnerID');
									$sql->execute(array(':partnerID' => $fbID));
									$result = $sql->fetchObject();
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
										}
										else{
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
										$update_id = wp_update_user($userargs);
										$current_user = get_user_by( 'id', $update_id );
										// set the WP login cookie
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
										//echo '<p>!</p>';
										wp_set_auth_cookie( $update_id, false, is_ssl() );
										//header("Refresh: 0; url=".home_url());
										echo '<meta http-equiv="refresh" content="0.1;'.home_url().'">';
										?>
            <script type="text/javascript">
											jQuery(document).ready(function($){
												$( "div.modal-dialog" ).addClass( "modal-login" );
											});
										</script>
            <?php
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
										//wp_set_auth_cookie( $user_id, false, is_ssl() );
										//header("Refresh: 0; url=".home_url());
										echo '<meta http-equiv="refresh" content="0.1;'.home_url().'">';
										?>
            <script type="text/javascript">
										jQuery(document).ready(function($){
											$( "div.modal-dialog" ).addClass( "modal-login" );
										});
										</script>
            <?php
									}
								}
							}else{
								header("Refresh: 0; url=".home_url());
								echo '<p class="error-message"><b>✘</b>The email used in facebook is not verified!';
							}
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
<div id="page-content">
	<div class="manual-top-up">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<h2><?php the_title(); ?></h2>
		<p>
			<?php the_content(); ?>
		</p>
    <?php endwhile; endif; ?>
	</div>
</div>
<?php get_footer(); ?>