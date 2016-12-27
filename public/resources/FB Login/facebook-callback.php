<?php
	if (!session_id()) {
		session_start();
	}



	require_once('../../../../../../wp-load.php');
	require_once('../../../includes/UPPUmbrellaPersonalProfile.php');
	require_once('../facebook/src/Facebook/autoload.php');
	$uPPUmbrellaPersonalProfile = new App\UPPUmbrellaPersonalProfile();



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
		  } else {
			header('HTTP/1.0 400 Bad Request');
			echo 'Bad request';
		  }
		  //exit;
		}
		$oAuth2Client = $fb->getOAuth2Client();

		// Get the access token metadata from /debug_token
		$tokenMetadata = $oAuth2Client->debugToken($accessToken);
		
		// Validation (these will throw FacebookSDKException's when they fail)
		$tokenMetadata->validateAppId('1809109289321551'); // Replace {app-id} with your app id
		// If you know the user ID this access token belongs to, you can validate it here
		
		$tokenMetadata->validateExpiration();

		if (! $accessToken->isLongLived()) {
		  // Exchanges a short-lived access token for a long-lived one
		  try {
			$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
		  } catch (Facebook\Exceptions\FacebookSDKException $e) {
			echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
			exit;
		  }

		}

		$acctoken = (string) $accessToken;
		
		try {
		  // Returns a `Facebook\FacebookResponse` object
		  $response = $fb->get('/me?fields=id,name,email,first_name,last_name, gender, birthday,picture',$acctoken);
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  echo 'Graph returned an error: ' . $e->getMessage();
		  exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  exit;
		}

		$user = $response->getGraphUser();

		$faceBookInformation = [
				'fb_id' => $user['id'],
				'fb_email' => $user['email'],
				'fb_fn' => $user['name'],
				'fb_profile_pic' => "http://graph.facebook.com/" . $user['id'] . "/picture",
				'fb_authenticated' => true,
				'fb_image_index' => 1,
		];

		$uPPUmbrellaPersonalProfile->setSessionFacebookProfileVariables($faceBookInformation);
		$uPPUmbrellaPersonalProfile->saveFaceBookInformationToWpOption($faceBookInformation);


	 	// print "<pre>";  
	 	// 	print_r($_SESSION['personal_profile']);  
	 	// print "</pre>";  

	}
?> 
<script language="javascript">
	window.close();
</script>