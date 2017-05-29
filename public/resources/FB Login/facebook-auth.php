<?php
	if (!session_id()) {
		session_start();
	}


	require_once ('config.php');
	require_once('../facebook-php-graph-sdk-5.5/src/Facebook/autoload.php');
 
 
	$fb = new Facebook\Facebook([
		'app_id' => $con['app_id'], // Replace {app-id} with your app id
		'app_secret' => $con['app_secret'],
		'default_graph_version' => 'v2.9',
	]);
	$helper = $fb->getRedirectLoginHelper();
	$permissions = ['email', 'public_profile', 'user_birthday']; // Optional permissions
	$loginUrl = $helper->getLoginUrl('https://testing.umbrellasupport.co.uk/wp-content/plugins/umbrella-personal-profile/public/resources/FB%20Login/facebook-callback.php', $permissions);

	header('Location: ' . $loginUrl);
	//echo '<a href="' . htmlspecialchars($loginUrl) . '"><img src="/facebook/facebook.png"></a>';

?>


