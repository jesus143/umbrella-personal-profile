<?php
/**
 * Template Name: Leads Ping Page
 */
	date_default_timezone_set ('Europe/London');
	
	global $wpdb;
	
	$WP_HOST	= 'db639369002.db.1and1.com';
	$WP_USER 	= 'dbo639369002';
	$WP_PASS 	= '1qazxsw2!QAZXSW@';
	$WP_DBSE 	= 'db639369002';
	
	$PART_ID	= $wp_query->query_vars['partnerid'];
	$LEAD_OWN	= $wp_query->query_vars['leadowner'];
	$AGENT		= $wp_query->query_vars['agent'];
	$DATE_SUB1	= $wp_query->query_vars['date'];
	$TAGS		= $wp_query->query_vars['tags'];
	$DATE_SUB	= time();
	$TIME_SUB	= time();
	
	//error_log('Partner ID: ' . $PART_ID . ' Lead Owner: ' . $LEAD_OWN . ' TAGS: ' . $TAGS, 1, 'arvinsalvador.official@gmail.com');
	//die();
	//echo 'Part ID: ' . $PART_ID . '<br />';
	//echo 'Owner: ' . $LEAD_OWN . '<br />';
	//echo 'AGENT: ' . $AGENT . '<br />';
	//echo 'Tag: ' . $TAGS . '<br />';
	//echo $TAGS;
	preg_match_all ( '#<li>(.+?)</li>#', $TAGS, $parts );
	
	foreach($parts[1] as $R){
		switch($R){
			case 'Umbrella Agent Converts Lead':{
				processing_leads($PART_ID, $LEAD_OWN, "Umbrella Converted", "Umbrella", "Umbrella Agent Converts Lead", "Umbrella Converted", "Umbrella Agent");
				break;
			}
			case 'Umbrella Partner Buys Lead':{
				processing_leads($PART_ID, $LEAD_OWN, "Umbrella Converted", "Umbrella", "Umbrella Partner Buys Lead", "Umbrella Converted", "Umbrella Partner");
				break;
			}
			case 'Umbrella Affiliate Buys Lead':{
				processing_leads($PART_ID, $LEAD_OWN, "Umbrella Converted", "Umbrella", "Umbrella Affiliate Buys Lead", "Umbrella Converted", "Umbrella Affiliate");
				break;
			}
			case 'Umbrella System Marks as Unconvertible':{
				processing_leads($PART_ID, $LEAD_OWN, "Umbrella Unconvertible", "Umbrella", "Umbrella System Marks as Unconvertible", "Umbrella Unconvertible", "Umbrella System");
				break;
			}
			case 'Umbrella Agent Marks as Unconvertible':{
				processing_leads($PART_ID, $LEAD_OWN, "Umbrella Unconvertible", "Umbrella", "Umbrella Agent Marks as Unconvertible", "Umbrella Unconvertible", "Umbrella Agent");
				break;
			}
		}
	}

	$current_user = wp_get_current_user();
	
	
	if($parts[1][0] == 'New Umbrella Enquiry'){
		if(empty($PART_ID)){
			echo 'We cannot fetch the Partner ID. Please contact Administrator';
			exit();
		}
		if(empty($LEAD_OWN)){
			echo 'We cannot fetch the Lead Owner. Please contact Administrator';
			exit();
		}
		$API_URL	= 'http://api.ontraport.com/1/objects?';
		
		$API_DATA	= array(
			'objectID'		=> 0,
			'performAll'	=> 'true',
			'sortDir'		=> 'asc',
			'condition'		=> 'id='.$PART_ID,
			'searchNotes'	=> 'true'
		);

		
		$API_KEY 	= get_field('custom_api_key','option');
		$API_ID		= get_field('custom_api_id','option');
		
		$GETINFOURI = $API_URL . urldecode(http_build_query($API_DATA));
		$GETINFO	= curl_init();
		curl_setopt ($GETINFO, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($GETINFO, CURLOPT_URL, $GETINFOURI);
		curl_setopt ($GETINFO, CURLOPT_HTTPHEADER, array('Api-Appid:' . $API_ID, 'Api-Key:' . $API_KEY));
					
		$RGETINFO = curl_exec($GETINFO); 
		curl_close($GETINFO);
		
		$VALUEJSON 	= json_decode($RGETINFO);
		
		//var_dump($VALUEJSON);
		if($VALUEJSON->data[0]->zip){
			$LOC_TYPE = 'ZIP';
			$LOCATION = $VALUEJSON->data[0]->zip;			
		}elseif($VALUEJSON->data[0]->IPAddress_489){
			$LOC_TYPE = 'IP';
			$LOCATION = $VALUEJSON->data[0]->IPAddress_489;
		}else{
			$LOC_TYPE = 'NONE';
			$LOCATION = 'NONE';
		}
		//var_dump($VALUEJSON->data[0]);
		//var_dump($VALUEJSON->data[0]);
		//echo $GETINFOURI;
		
		$insert_data = array(
			'le_LEADID'		=> $PART_ID,
			'le_LEADOWNER'	=> $LEAD_OWN,
			'le_AGENT'		=> $AGENT,
			'le_DATE'		=> $DATE_SUB,
			'le_TIME'		=> $TIME_SUB,
			'le_NAME'		=> $VALUEJSON->data[0]->firstname . ' ' . $VALUEJSON->data[0]->lastname,
			'le_METHOD'		=> $VALUEJSON->data[0]->f1568,
			'le_LOCATION'	=> $LOCATION,
			'le_LOCTYPE'	=> $LOC_TYPE,
			'le_ENQUIRY'	=> $VALUEJSON->data[0]->f1561,
			'le_STATUS'		=> 'Enquiry',
			'le_FSTATUS'	=> '0'
		);
		
		//var_dump($insert_data);
		
		$insert_format = array(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%d'
		);
		
		$query_insert = $wpdb->insert($wpdb->prefix . 'leads_enquiries', $insert_data, $insert_format);
		
		if($query_insert){
			
			$API_UPDATE_DATA	= array(
				'objectID'	=> 0,
				'id'		=> $PART_ID,
				'f1555'		=> 'Enquiry'
			);
			
			$ch = curl_init( );
			curl_setopt($ch, CURLOPT_URL, $API_URL);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen(json_encode($API_UPDATE_DATA)), 'Api-Appid:' . $API_ID, 'Api-Key:' . $API_KEY));
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($API_UPDATE_DATA));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	
			$response  = curl_exec($ch);
			curl_close($ch);
			
			if( $response == "Object not found" ){
				echo 'FAILED TO PUT/UPDATE DATA!';
				die( );
			}
			echo 'SUCCESS!';
			return true;
		}else{
			echo 'FAILED!';
			return false;
		}
		$wpdb->show_errors();
		$wpdb->print_error();
	}
	
	
	if($parts[1][0] == 'New Umbrella Lead'){
		if(empty($PART_ID)){
			echo 'We cannot fetch the Partner ID. Please contact Administrator';
			exit();
		}
		if(empty($LEAD_OWN)){
			echo 'We cannot fetch the Lead Owner. Please contact Administrator';
			exit();
		}
		$API_URL	= 'http://api.ontraport.com/1/objects?';
		
		$API_DATA	= array(
			'objectID'		=> 0,
			'performAll'	=> 'true',
			'sortDir'		=> 'asc',
			'condition'		=> 'id='.$PART_ID,
			'searchNotes'	=> 'true'
		);

		
		$API_KEY 	= get_field('custom_api_key','option');
		$API_ID		= get_field('custom_api_id','option');
		
		$GETINFOURI = $API_URL . urldecode(http_build_query($API_DATA));
		$GETINFO	= curl_init();
		curl_setopt ($GETINFO, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($GETINFO, CURLOPT_URL, $GETINFOURI);
		curl_setopt ($GETINFO, CURLOPT_HTTPHEADER, array('Api-Appid:' . $API_ID, 'Api-Key:' . $API_KEY));
					
		$RGETINFO = curl_exec($GETINFO); 
		curl_close($GETINFO);
		
		$VALUEJSON 	= json_decode($RGETINFO);
		
		//var_dump($VALUEJSON);
		if($VALUEJSON->data[0]->zip){
			$LOC_TYPE = 'ZIP';
			$LOCATION = $VALUEJSON->data[0]->zip;			
		}elseif($VALUEJSON->data[0]->IPAddress_489){
			$LOC_TYPE = 'IP';
			$LOCATION = $VALUEJSON->data[0]->IPAddress_489;
		}else{
			$LOC_TYPE = 'NONE';
			$LOCATION = 'NONE';
		}
		//var_dump($VALUEJSON->data[0]);
		//var_dump($VALUEJSON->data[0]);
		//echo $GETINFOURI;
		
		$insert_data = array(
			'll_LEADID'		=> $PART_ID,
			'll_LEADOWNER'	=> $LEAD_OWN,
			'll_AGENT'		=> $AGENT,
			'll_DATE'		=> $DATE_SUB,
			'll_TIME'		=> $TIME_SUB,
			'll_NAME'		=> $VALUEJSON->data[0]->firstname . ' ' . $VALUEJSON->data[0]->lastname,
			'll_LOCATION'	=> $LOCATION,
			'll_LOCTYPE'	=> $LOC_TYPE,
			'll_ENQUIRY'	=> $VALUEJSON->data[0]->f1561,
			'll_STATUS'		=> 'Enquiry',
			'll_FSTATUS'	=> '0'
		);
		
		//var_dump($insert_data);
		
		$insert_format = array(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%d'
		);
		
		$query_insert = $wpdb->insert($wpdb->prefix . 'leads_list', $insert_data, $insert_format);
		
		if($query_insert){
			
			$API_UPDATE_DATA	= array(
				'objectID'	=> 0,
				'id'		=> $PART_ID,
				'f1555'		=> 'Enquiry'
			);
			
			$ch = curl_init( );
			curl_setopt($ch, CURLOPT_URL, $API_URL);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen(json_encode($API_UPDATE_DATA)), 'Api-Appid:' . $API_ID, 'Api-Key:' . $API_KEY));
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($API_UPDATE_DATA));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	
			$response  = curl_exec($ch);
			curl_close($ch);
			
			if( $response == "Object not found" ){
				echo 'FAILED TO PUT/UPDATE DATA!';
				die( );
			}
			echo 'SUCCESS!';
			return true;
		}else{
			echo 'FAILED!';
			return false;
		}
		$wpdb->show_errors();
		$wpdb->print_error();
	}