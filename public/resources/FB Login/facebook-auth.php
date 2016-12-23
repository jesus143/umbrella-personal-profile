<?php
	if (!session_id()) {
		session_start();
	}
	require_once('../facebook/src/Facebook/autoload.php');
	$fb = new Facebook\Facebook([
			'app_id' => '1809109289321551', // Replace {app-id} with your app id
			'app_secret' => 'c708e1816369948058edebc76df52d9d',
			'default_graph_version' => 'v2.7',
		]);
	$helper = $fb->getRedirectLoginHelper();
	$permissions = ['email','public_profile','user_birthday']; // Optional permissions
	$loginUrl = $helper->getLoginUrl('http://testing.umbrellasupport.co.uk/wp-content/plugins/umbrella-personal-profile/public/resources/FB%20Login/facebook-callback.php', $permissions);
	header('Location: '.$loginUrl);
	//echo '<a href="' . htmlspecialchars($loginUrl) . '"><img src="/facebook/facebook.png"></a>';
?>


