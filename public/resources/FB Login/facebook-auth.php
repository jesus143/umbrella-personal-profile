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
	$permissions = ['email','public_profile','user_birthday']; // Optional permissions
	$loginUrl = $helper->getLoginUrl('http://livewebchatcode.com/facebook-callback.php', $permissions);
	header('Location: '.$loginUrl);
	//echo '<a href="' . htmlspecialchars($loginUrl) . '"><img src="/facebook/facebook.png"></a>';
?> 