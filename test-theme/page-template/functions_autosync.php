<?php
function autosyncData( ){
	global $wpdb; // this is how you get access to the database
	/*$host  		= "db640728737.db.1and1.com";
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
		$QUESTRING_GETNAIMG = "SELECT * FROM wp_user_imguploads";
		$GETNAIMG_RESULT	= $WP_CON->query($QUESTRING_GETNAIMG);
		$GETNAIMG_LISTS		= $GETNAIMG_RESULT->fetch();
	}catch(PDOException $ERR){
		echo $ERR->getMessage();
		exit();
	}*/
	$autosyncID = isset( $_POST['autosyncID'] ) ? $_POST['autosyncID'] : '';
	echo 'Succesfully autosave!';
	die( ); // this is required to return a proper result
}
add_action( 'wp_ajax_autosyncData', 'autosyncData' );
