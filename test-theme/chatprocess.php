<?php
/**
 * Template Name: Chat Processingz
 */
	header('Access-Control-Allow-Origin: *');
	
	$LC_HOST	= 'db639654160.db.1and1.com';
	$LC_USER 	= 'dbo639654160';
	$LC_PASS 	= 'LhC2016@Umbrella';
	$LC_DBSE 	= 'db639654160';
	
	$WP_HOST	= 'db639369002.db.1and1.com';
	$WP_USER 	= 'dbo639369002';
	$WP_PASS 	= '1qazxsw2!QAZXSW@';
	$WP_DBSE 	= 'db639369002';
	
	$ACC_NUM	= $_POST['clientid'];
	$DOMAINN	= $_POST['domainn'];
	$ACC_HASH	= $_POST['hashid'];

	$IMAGESRC	= '';
	$HTMLAPPC	= '';
	
	if(empty($ACC_NUM)){
		echo 'Failed to validate Partner ID. Please contact Umbrella Support Centre.';
		exit();
	}
	
	if(empty($DOMAINN)){
		echo 'Failed to get client web domain. Please contact Umbrella Support Centre.';
		exit();
	}
	
	if(empty($ACC_HASH)){
		echo 'Failed to get client Hash ID. Please contact Umbrella Support Centre.';
		exit();
	}
	
	$API_URL	= 'http://api.ontraport.com/1/objects?';
		
	$API_DATA	= array(
		'objectID'		=> 0,
		'performAll'	=> 'true',
		'sortDir'		=> 'asc',
		'condition'		=> "id='".$ACC_NUM."'",
		'searchNotes'	=> 'true'
	);
	
	$API_KEY 	= 'fY4Zva90HP8XFx3';
	$API_ID		= '2_7818_AFzuWztKz';

	$GETINFOURI = $API_URL . urldecode(http_build_query($API_DATA));
	$GETINFO	= curl_init();
	curl_setopt ($GETINFO, CURLOPT_RETURNTRANSFER, true);
	curl_setopt ($GETINFO, CURLOPT_URL, $GETINFOURI);
	curl_setopt ($GETINFO, CURLOPT_HTTPHEADER, array('Api-Appid:' . $API_ID, 'Api-Key:' . $API_KEY));
				
	$RGETINFO = curl_exec($GETINFO); 
	curl_close($GETINFO);
	//header("Content-Type: text");
	//echo "CODE: " . $response;
	$getName = json_decode($RGETINFO); 
	
	$PA_GETSTAT	= 'http://livewebchatcode.com/index.php/restapi/isonline';
	
	$GETSTATUS 	= curl_init(); 
	curl_setopt ($GETSTATUS, CURLOPT_RETURNTRANSFER, true);
	curl_setopt ($GETSTATUS, CURLOPT_URL, $PA_GETSTAT);
	//curl_setopt ($session, CURLOPT_HEADER, true);
	$RGETSTATUS = curl_exec($GETSTATUS); 
	curl_close($GETSTATUS);
	
	$chat_status = json_decode($RGETSTATUS);
	//print_r("RESULT " . $API_RESULT);
	//header("Content-Type: text");
	//echo "CODE: " . $response;

	if(strlen($getName->data[0]->id) < 3){
		echo 'Failed to validate Partner ID. Please contact Umbrella Support Centre.';
		exit();
	}
	
	
	try{
		$LC_CON	= new PDO('mysql:host='.$LC_HOST.';dbname='.$LC_DBSE.';', $LC_USER, $LC_PASS);
		$LC_CON->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch(PDOException $ERR){
		echo $ERR->getMessage();
		exit();
	}
	
	try{
		$WP_CON	= new PDO('mysql:host='.$WP_HOST.';dbname='.$WP_DBSE.';', $WP_USER, $WP_PASS);
		$WP_CON->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch(PDOException $ERR){
		echo $ERR->getMessage();
		exit();
	}
	
	
	try{
		$QUESTRING_GETNAIMG = "SELECT * FROM wp_posts WHERE post_title = 'NotAllowed'";
		$GETNAIMG_RESULT	= $WP_CON->query($QUESTRING_GETNAIMG);
		$GETNAIMG_LISTS		= $GETNAIMG_RESULT->fetch();
	}catch(PDOException $ERR){
		echo $ERR->getMessage();
		exit();
	}
	
	try{
		$QUESTRING_GETSITE	= "SELECT * FROM wp_clientsites WHERE s_accountidhash = '".$ACC_HASH."' AND s_website LIKE '%".$DOMAINN."%' AND s_status = 1";
		$GETSITE_RESULT		= $WP_CON->query($QUESTRING_GETSITE);
		$GETSITE_LISTS		= $GETSITE_RESULT->fetch();

		if($GETSITE_RESULT->rowCount() > 0){
			try{
				$QUESTRING_GETWOPT 	= "SELECT * FROM wp_widgetoptions WHERE wid_accountid = '".$ACC_NUM."'";
				$GETWOPT_RESULT		= $WP_CON->query($QUESTRING_GETWOPT);
				$GETWOPT_LIST		= $GETWOPT_RESULT->fetch();
				
				if($GETWOPT_RESULT->rowCount()>0){
					if($GETWOPT_LIST['wid_chattype'] == 0){
						if($chat_status->isonline == true){
							$HTMLAPPC = '<a href="#" onClick="return lh_inst.lh_openchatWindow()" style="display:block; width:200px; height:100px; top:0; left:0; margin:0; background:url('.$GETWOPT_LIST['wid_imgpathon'].') no-repeat;"></a>';
						}else{
							$HTMLAPPC = '<a href="#" onClick="return lh_inst.lh_openchatWindow()" style="display:block; width:200px; height:100px; top:0; left:0; margin:0; background:url('.$GETWOPT_LIST['wid_imgpathoff'].') no-repeat;"></a>';
						}
						
					}else{
						if($chat_status->isonline == true){
							$HTMLAPPC = '<a href="#" onClick="return trigger_click()" style="display:block; width:200px; height:100px; top:0; left:0; margin:0; background:url('.$GETWOPT_LIST['wid_imgpathon'].') no-repeat;"></a>';
						}else{
							$HTMLAPPC = '<a href="#" onClick="return trigger_click()" style="display:block; width:200px; height:100px; top:0; left:0; margin:0; background:url('.$GETWOPT_LIST['wid_imgpathoff'].') no-repeat;"></a>';
						}
					}
					
					echo $HTMLAPPC;
					exit();
				}else{
					echo 'CHAT SETUP INCOMPLETE!';
					exit();
				}
			}catch(PDOException $ERR){
				echo $ERR->getMessage();
				exit();
			}
		}else{
			$HTMLAPPC 	= '<img src="'.$GETNAIMG_LISTS['guid'].'">';
			echo $HTMLAPPC;
			exit();
		}
	}catch(PDOException $ERR){
		echo $ERR->getMessage();
		exit();
	}
	
	
	
?>