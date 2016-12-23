<?php
/**
 * Created by PhpStorm.
 * User: JESUS
 * Date: 12/22/2016
 * Time: 12:58 AM
 */ 

namespace App;
 
class UPPUmbrellaPersonalProfile
{ 
	protected $user_id = 0; 	 
	function __construct() {
		$this->setCurrentUserLogged();
		$this->checkRequestFbDelete();
	}
	protected function checkRequestFbDelete()
	{
		if(isset($_POST['fbDelete'])) {
			$this->deleteSessionFaceBookInformationAll();
			$this->deleteFaceBookInformationFromWpOptionAll();
			$this->deleteTagInfoFromOntraPort();
		} else {
			$this->updateTagInfoToOntraPort();
		}
	}
	public function setCurrentUserLogged() {
		$current_user = wp_get_current_user();
	    /**
	     * @example Safe usage: $current_user = wp_get_current_user();
	     * if ( !($current_user instanceof WP_User) )
	     *     return;
	     */
	    // echo 'Username: ' . $current_user->user_login . '<br />';
	    // echo 'User email: ' . $current_user->user_email . '<br />';
	    // echo 'User first name: ' . $current_user->user_firstname . '<br />';
	    // echo 'User last name: ' . $current_user->user_lastname . '<br />';
	    // echo 'User display name: ' . $current_user->display_name . '<br />';
	    // echo 'User ID: ' . $current_user->ID . '<br />';  
	    $this->user_id = $current_user->ID; 
	}
	public function getCurrentUserId() 
	{
		return  $this->user_id;
	} 
	// facebook session variables
	public function getSessionFaceBookUserId()
	{
		return $_SESSION['personal_profile']['fb_id'];
	}
	public function getSessionFaceBookEmail()
	{
		return $_SESSION['personal_profile']['fb_email'];
	}
	public function getSessionFaceBookName()
	{
		return $_SESSION['personal_profile']['fb_fn'];
	}
	public function getSessionFaceBookProfilePicPath()
	{
		return $_SESSION['personal_profile']['fb_profile_pic'];
	}
	public function getSessionFaceBookIsAuthenticated()
	{
		return $_SESSION['personal_profile']['fb_authenticated'];
	}
	public function setSessionFacebookProfileVariables($faceBookData = [])
	{ 
		$_SESSION['personal_profile']['fb_id'] = $faceBookData['fb_id'];
		$_SESSION['personal_profile']['fb_email'] = $faceBookData['fb_email'];
		$_SESSION['personal_profile']['fb_fn'] = $faceBookData['fb_fn'];
		$_SESSION['personal_profile']['fb_profile_pic'] = $faceBookData['fb_profile_pic'];
		$_SESSION['personal_profile']['fb_authenticated'] = $faceBookData['fb_authenticated'];
	}
	public function deleteSessionFaceBookInformation($fbInfoKey)
	{
		unset($_SESSION['personal_profile'][$fbInfoKey]);
	}
	public function deleteSessionFaceBookInformationAll()
	{
		unset($_SESSION['personal_profile']['fb_id']);
		unset($_SESSION['personal_profile']['fb_email']);
		unset($_SESSION['personal_profile']['fb_fn']);
		unset($_SESSION['personal_profile']['fb_profile_pic']);
		unset($_SESSION['personal_profile']['fb_authenticated']);
		return true;
	}
	// wp_option database
	public function getWpOptionFaceBookUserId()
	{
		return $this->getOption($this->getCurrentUserId() . '_fb_id');
	}
	public function getWpOptionFaceBookEmail()
	{
		return $this->getOption($this->getCurrentUserId() . '_fb_email');
	}
	public function getWpOptionFaceBookName()
	{
		return $this->getOption($this->getCurrentUserId() . '_fb_fn');
	}
	public function getWpOptionFaceBookProfilePicPath()
	{
		return $this->getOption($this->getCurrentUserId() . '_fb_profile_pic');
	}
	public function getWpOptionFaceBookIsAuthenticated()
	{
		return $this->getOption($this->getCurrentUserId() . '_fb_authenticated');
	}
	public function deleteFaceBookInformationFromWpOption($faceBookData = [])
	{
		foreach($faceBookData as $key => $value) {
			delete_option($key);
		}
		return true;
	}
	public function deleteFaceBookInformationFromWpOptionAll()
	{
		$faceBookData = [
			$this->getCurrentUserId() . '_fb_id',
			$this->getCurrentUserId() . '_fb_email',
			$this->getCurrentUserId() . '_fb_fn',
			$this->getCurrentUserId() . '_fb_profile_pic',
			$this->getCurrentUserId() . '_fb_authenticated',
		];

		foreach($faceBookData as $key) {
			delete_option($key);
		}
		return true;
	}
	protected function getOption($key)
	{
		return get_option($key);
	}
	protected function deleteOption($key)
	{
		return delete_option($key);
	}
	public function saveFaceBookInformationToWpOption($faceBookData = [])
	{
		foreach($faceBookData as $key => $value) {
			update_option($this->getCurrentUserId() . '_' . $key, $value);
		}
		return true;
	} 
	public function isAuthenticatedWithFaceBook()
	{
		if($this->getSessionFaceBookIsAuthenticated() == true || $this->getWpOptionFaceBookIsAuthenticated() == true) {
			return true;
		} else {
			return false;
		}

	}
	// Ontraport
	public function OpUpdateDeleteInit($faceBookEmail, $method)
	{
		$user 	= wp_get_current_user();
		$API_URL	= 'https://api.ontraport.com/1/objects?';
//		print "<brr> current user email " . $user->user_email;

		$API_DATA	= array(
				'objectID'		=> 0,
				'performAll'	=> 'true',
				'sortDir'		=> 'asc',
				'condition'		=> "email='".$user->user_email."'", //use this format since its a sql query condition. For other fields, you may change this value to something else.
				'searchNotes'	=> 'true'
		);

		$API_KEY 	= 'fY4Zva90HP8XFx3';
		$API_ID		= '2_7818_AFzuWztKz';

		//$API_RESULT	= query_api_call($postargs, $API_ID, $API_KEY);

		$API_RESULT = $this->op_query($API_URL, 'GET', $API_DATA, $API_ID, $API_KEY);

		$getName 	= json_decode($API_RESULT);

		//		var_dump($getName->data[0]); //sample for getting all data from the decoded json

		$PARTNER_ID 	= $getName->data[0]->id;
		//		echo $PARTNER_ID; //partner ID

		$FACEBOOK_EMAILSAMPLE = $faceBookEmail;

		$API_UDATA 	= array(
				'objectID'   		=> 0,
				'id'  	 		=> $PARTNER_ID,
				'f1583'  		=> $FACEBOOK_EMAILSAMPLE
		);

		//GET PUT RESULT
		return $this->op_query( $API_URL, $method, $API_UDATA, $API_ID, $API_KEY );
	}
	private function op_query($url, $method, $data, $appID, $appKey){
		$ch = curl_init( );
		switch ($method){
			case 'POST': {
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen(json_encode($data)), 'Api-Appid:' . $appID, 'Api-Key:' . $appKey));
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				break;
			}
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
	private function op_other_query()
	{
		$current_user = wp_get_current_user();
		$customAPIKEY  = get_field('custom_api_key','option');// name of the admin
		$customAPIID  = get_field('custom_api_id','option');// Email Title for the admin
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
		$getName  = json_decode($response);
		$profileID = $getName->data[0]->id;
		$currentAmount = $getName->data[0]->f1547;
	}
	public function getOntraportFaceBookEmailTag()
	{
		$opResponse =$this->OpUpdateDeleteInit($this->getFaceBookEmail(), "GET");
		$opResponse = json_decode($opResponse, true );
		return $opResponse['data'][0]['f1583'];
	}
	public function getTagInfoOntraPort()
	{
		$opResponse =$this->OpUpdateDeleteInit($this->getFaceBookEmail(), "GET");
		$opResponse = json_decode($opResponse, true );
		return $opResponse;
	}
	public function updateTagInfoToOntraPort()
	{
		return $this->OpUpdateDeleteInit($this->getFaceBookEmail(), "PUT");
		// coding that will update the tag from OP here
	}
	public function deleteTagInfoFromOntraPort()
	{
		return $this->OpUpdateDeleteInit("", "PUT");
		// coding that will remove the tag info saved from ontraport
	}
	public function getPartnerIdFromOntraport() {
		return $this->getTagInfoOntraPort()['data'][0]['id'];
	}
	// html
	public function htmlPrintFacebookInfoIncludingPicture()
	{
		$this->styleCss();
		?>

			<div class="row user-fb-wrapper" style="">
				<div class="col-md-2" >
					<img class="fb-profile" src="<?php print $this->getFaceBookProfilePicPath(); ?>">
					<!-- <div class="facebook-icon"><img src="http://livewebchatcode.com/facebook/facebook-icon.png"></div>-->
				</div>
				<div class="col-md-8">
					<?php
					echo '<p class="fb-details"><b>'. $this->getFaceBookName()  . '</b><br/>';
					echo  $this->getFaceBookEmail()   . '</p>';
					?>
					<a href="#" class="fb-logout" onclick="showPopUp( )"></a>
					<input type="hidden" name="Username" value="<?php echo htmlspecialchars($this->getFaceBookName());?>" />
					<input type="hidden" name="Email" value="<?php echo htmlspecialchars($this->getFaceBookEmail());?>" />
				</div>
			</div>
		<?php
	} 
	public function htmlDesignForFaceBookRemovePopup($message="Are you sure you want to remove your Facebook account from your portal? This could negatively affect your Reward Level?")
	{
		$this->popupStyleCss();
		$this->popupJs();
		?>
			<!-- The Confirm Modal -->
			<div id="sendconfirmModal" class="ss-modal">
				<!-- Modal content -->
				<div class="ss-modal-content">
					<div class="ss-modal-header">
						<span class="ss-close">×</span>
						<h2>Important Notice</h2>
					</div>
					<div class="ss-modal-body ss-passsend-modal-text" style="text-align: center;">
						<?php print $message; ?>
					</div>
					<div class="ss-modal-footer">

							<form method="post" action="" style="display: inline-block;">
								<button name="fbDelete" type="submit" class="ss-button ss-btn-green" >Yes</button>
							</form>
							<button style="position: inline" type="button" class="ss-button ss-btn-red ss-cancel">No</button>

					</div>
				</div>
			</div>
		<?php 
	}
	protected function popupJs()
	{
		?>
			<script>
				function selectPopUpYes() {
					console.log("selected yes popup");
					 setTimeout(function(){
						document.location = '?fb=remove';
					 }, 2000);
				}
				function showPopUp( ) {
					console.log("open popup now");
					var modal = document.getElementById('sendconfirmModal');
					modal.style.display = "block";
				}
			</script>
		<?php
	}
	protected function popupStyleCss()
	{
		?>
			<style>
				/*/ The Modal (background) /*/
				.ss-modal {
					display: none; / Hidden by default /
					position: fixed; / Stay in place /
					z-index: 100; / Sit on top /
					left: 0;
					top: 0;
					width: 100%; / Full width /
					height: 100%; / Full height /
					overflow: auto; / Enable scroll if needed /
					background-color: rgb(0,0,0); / Fallback color /
					background-color: rgba(0,0,0,0.4); / Black w/ opacity /
				}
				/*/ The Close Button /*/
				.ss-close {
					color: #aaa;
					float: right;
					font-size: 20px;
					font-weight: bold;
					margin: 9px 0;
				}
				.ss-close:hover,
				.ss-close:focus {
					color: black;
					text-decoration: none;
					cursor: pointer;
				}
				/*/ Modal Header /*/
				.ss-modal-header {
					padding: 2px 16px;
					background-color: #D70019;
					color: white;
				}
				.ss-modal-header h2{
					color: #fff;
					font-size: 15px;
				}
				/*/ Modal Body /*/
				.ss-modal-body {padding: 20px;text-align:center;}
				/*/ Modal Footer /*/
				.ss-modal-footer {
					padding: 20px;
					background-color: #fff;
					color: white;
					text-align: center;
				}
				/*/ Modal Content /*/
				.ss-modal-content {
					position: relative;
					background-color: #fff;
					margin: 15% auto; / 15% from the top and centered /
					padding: 0px;
					width: 40%;
					z-index: 1050;
					border-radius: 5px;
					overflow: hidden;

					box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
					-webkit-animation-name: animatetop;
					-webkit-animation-duration: 0.4s;
					animation-name: animatetop;
					animation-duration: 0.4s
				}
				/*/ Add Animation /*/
				@-webkit-keyframes animatetop {
					from {top: -300px; opacity: 0}
					to {top: 0; opacity: 1}
				}
				@keyframes animatetop {
					from {top: -300px; opacity: 0}
					to {top: 0; opacity: 1}
				}
				.ss-btn-green{
					background-color: #009E38;
				}

				.ss-btn-red{
					background-color: #D70019;
				}
				.ss-button {
					border: 0;
					border-radius: 2px;
					padding: 8px 25px;
					text-decoration: none;
					color: #fff;
					font-size: 14px;
				}
				.ss-button:hover {
					/*background: #06D85F;*/
					opacity: 0.8;
					color: #FFF;
				}
				.sp-table tr:nth-child(even) {background: #f5f5f5;}
				.sp-table tr:nth-child(odd) {background: transparent;}
			</style>
		<?php
	}
	protected function styleCss()
	{ ?>
		<style>
			.user-fb-wrapper{
				background: #d2d2d2;
				padding: 9px 3px;
				margin-top: -7px;
				margin: 4px;
				border-radius: 4px;
			}
			.user-fb-wrapper .fb-logout{
				background:url('http://livewebchatcode.com/facebook/logout.png') no-repeat;
				position: absolute;
				height: 16px;
				background-size: cover;
				border: none;
				top: -1px;
				right: 7px;
				width: 14px;
			}
			.user-fb-wrapper .fb-details{margin: -5px 0 0 -29px;}
			.user-fb-wrapper .fb-details b{
				position: relative;
				top: 6px;
			}
			.fbimg { height: 19px !important; }
			.user-fb-wrapper .fb-logout:hover{background-position: 0 -18px;}
			.user-fb-wrapper .facebook-icon{
				position: absolute;
				top: 18px;
				left: 7px;
			}
			.user-fb-wrapper .fb-profile{
				border-radius: 4px;
				width: 65%;
			}
			.user-fb-wrapper .facebook-icon img{
				width: 14px;
			}
			.form-size-textarea{height:65px!important;max-width:286px;}
		</style>
	<?php
	}
	// session and wp_option
	public function getFaceBookUserId()
	{
		return (!empty($this->getWpOptionFaceBookUserId())) ? $this->getWpOptionFaceBookUserId() : $this->getSessionFaceBookUserId();
	}
	public function getFaceBookEmail()
	{
		return (!empty($this->getWpOptionFaceBookEmail())) ? $this->getWpOptionFaceBookEmail() : $this->getSessionFaceBookEmail();
	}
	public function getFaceBookName()
	{
		return (!empty($this->getWpOptionFaceBookName())) ? $this->getWpOptionFaceBookName() : $this->getSessionFaceBookName();
	}
	public function getFaceBookProfilePicPath()
	{
		return (!empty($this->getWpOptionFaceBookProfilePicPath())) ? $this->getWpOptionFaceBookProfilePicPath() : $this->getSessionFaceBookProfilePicPath();
	}
	public function getFaceBookIsAuthenticated()
	{
		return (!empty($this->getWpOptionFaceBookIsAuthenticated())) ? $this->getWpOptionFaceBookIsAuthenticated() : $this->getSessionFaceBookIsAuthenticated();
	}
	public function deleteFaceBookInformation()
	{
		$this->deleteFaceBookInformationFromWpOptionAll();
		$this->deleteSessionFaceBookInformationAll();

		return true;
	}
	// businness profile pic
	protected function uPP_getBusinessProfilePicPath()
	{
		$host    = "db640728737.db.1and1.com";
		$database   = "db640728737";
		$user    = "dbo640728737";
		$password   = "1qazxsw2!QAZXSW@";

		$businessID = 77514;
		try{
			$WP_CON = new PDO('mysql:host='.$host.';dbname='.$database.';', $user, $password);
			$WP_CON->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $ERR){
			echo $ERR->getMessage();
			exit();
		}
		try{
			$QUESTRING_GETNAIMG = "SELECT * FROM wp_user_imguploads";
			$GETNAIMG_RESULT = $WP_CON->query($QUESTRING_GETNAIMG);
			$GETNAIMG_LISTS  = $GETNAIMG_RESULT->fetch();
		}catch(PDOException $ERR){
			echo $ERR->getMessage();
			exit();
		}

		global $featured_image,$status;
		$sql = $WP_CON->prepare('SELECT ui_URL AS url,ui_STATUS AS status FROM wp_user_imguploads WHERE uid_PartnerID = :parnerID');
		$sql->execute(array(':parnerID' => $businessID));
		$result = $sql->fetchObject();
		$status=$result->status;
		if(!empty($result->url)) {
			$featured_image=$result->url;
		}
		switch($status){
			case 0  :
				$class_watermark='class="water-wrapper water-mark"';
				$featured = $featured_image;

				break;
			case 1  :
				$class_watermark='class="water-wrapper"';
				$featured = $featured_image;
				break;
			default :
				$class_watermark='class="water-wrapper"';
				//$featured   = get_stylesheet_directory_uri().'/images/default-logo.jpg';
				break;
		}
		print "<br>featured image "  .  $featured;
		print "<br>class watermark " .  $class_watermark;
	}

}


// Helper

