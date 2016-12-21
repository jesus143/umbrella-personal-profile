<?php
	if (!session_id()) {
		session_start();
	}
	require_once('facebook/src/Facebook/autoload.php');
	$fb = new Facebook\Facebook([
			'app_id' => '1126710417414825', // Replace {app-id} with your app id
			'app_secret' => '1a2140fae9e93082e3a2a536435da45e',
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
		$tokenMetadata->validateAppId('1126710417414825'); // Replace {app-id} with your app id
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
		$user 		= $response->getGraphUser();
		$_SESSION['fb_id']	= $user['id'];
		$_SESSION['fb_ea']	= $user['email'];
		$_SESSION['fb_fn']	= $user['name'];
		//$_SESSION['fb_pp']	= $user['picture'];
		//header('Location: facebook-view.php');
		
	}
?> 
<script language="javascript">
	window.close();
</script>