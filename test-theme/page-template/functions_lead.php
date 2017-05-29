<?php
	function getleads(){
		
		global $wpdb;
		
		$current_user = wp_get_current_user();
		
		$API_URL	= 'http://api.ontraport.com/1/objects?';
		
		$API_DATA	= array(
			'objectID'		=> 0,
			'performAll'	=> 'true',
			'sortDir'		=> 'asc',
			'condition'		=> "email='".$current_user->user_email."'",
			'searchNotes'	=> 'true'
		);

		$API_KEY 	= get_field('custom_api_key','option');
		$API_ID		= get_field('custom_api_id','option');
		
		//$API_RESULT	= query_api_call($postargs, $API_ID, $API_KEY);
		
		$API_RESULT = op_query($API_URL, 'GET', $API_DATA, $API_ID, $API_KEY);
	
		$getName 	= json_decode($API_RESULT);
		
		$QUESTRING_GETLEADS = 'SELECT * FROM ' . $wpdb->prefix . 'leads_list WHERE ll_FSTATUS = 0 AND ll_LEADOWNER = \''.$getName->data[0]->id.'\' ORDER BY ll_DATE DESC';
		$RESULT_GETLEADS	= $wpdb->get_results($QUESTRING_GETLEADS, OBJECT);
		
		
		foreach($RESULT_GETLEADS as $R){
			echo '
				<li>
					<span class="tickerspan">From:</span> <span class="tickervalue">'.$R->ll_NAME.'</span>
					<div class="thumb-lead-container">
						<div class="thumb-lead-img">
							<img src="http://testing.umbrellasupport.co.uk/wp-content/uploads/2016/09/new.png" width="70" height="70" />
							<center>'.$R->ll_LOCATION.'</center>
						</div>
						<div class="thumb-lead-content">
							'.$R->ll_ENQUIRY.' 
						</div>
					</div>
				</li>
				';
				
				if(is_user_logged_in()){
					echo '
						<li>
							<div class="custom-navigation"> 
								<a href="http://testing.umbrellasupport.co.uk/my-leads/'.$R->ll_ID.'" class="flex-next"  style="float:right; margin-left:3px;">View Lead</a> 
								<a href="http://testing.umbrellasupport.co.uk/my-leads/'.$R->ll_ID.'" class="flex-prev" style="float:right">Sell Lead</a>
							</div>
						</li>
					';
				}else{
					echo '
						<li>
							<div class="custom-navigation" style="float:right"> 
								Login to view buttons.
							</div>
						</li>
					';
				}
				echo '
					<li>
						<div class="custom-navigation"> 
						 &nbsp;
						</div>
					</li>
				';
				
			if($count == 10){
				echo '<li><center><a class="flex-next" href="http://testing.umbrellasupport.co.uk/my-leads/" style="display:block; width: 60%; padding: 5px;">View all Leads >></a></center></li>';
				break;
			}
			$count++;
		}
		
		die();
	}
	add_action('wp_ajax_getleads', 'getleads');
	//add_action('wp_ajax_nopriv_getleads', 'getleads');
	
	function processing_leads($ID, $LID, $ACTION, $METHOD, $TAG, $TSTATUS, $AFFIL){
		
		global $wpdb;

		$TRANS_ID		= $ID;
		
		$GETLEADQUE		= "SELECT * FROM ".$wpdb->prefix."leads_list WHERE ll_LEADID = ".$TRANS_ID." AND ll_LEADOWNER = ".$LID;
		$RESULTGETLEAD	= $wpdb->get_row($GETLEADQUE);
		
		$INFO_LEADID	= $RESULTGETLEAD->ll_LEADID;
		$INFO_LEADOWNER	= $RESULTGETLEAD->ll_LEADOWNER;
		
		$INFO_NAME		= $RESULTGETLEAD->ll_NAME;
		$INFO_LOC		= $RESULTGETLEAD->ll_LOCATION;
		$INFO_ENQUIRY	= $RESULTGETLEAD->ll_ENQUIRY;
		$INFO_TRANSID	= $RESULTGETLEAD->ll_ID;
		$INFO_LOCTYPE	= $RESULTGETLEAD->ll_LOCTYPE;
		
		$TRANS_ACTION 	= $ACTION;
		$TRANS_METHOD	= $METHOD;
		$TRANS_TIME		= time();
		$TRANS_DATE		= time();
		
		switch($AFFIL){
			case 'Umbrella Agent': 
			case 'Umbrella Partner':
			case 'Umbrella Affiliate': 
				$INFO_AGENT		= $RESULTGETLEAD->ll_AGENT;
				$TRANS_PROCBY	= $INFO_AGENT;
				break;
			
			case 'Umbrella System': {
				$INFO_AGENT		= 'N/A';
				$TRANS_PROCBY	= 'N/A';
				break;
			}			
			
			default: {
				$INFO_AGENT		= 'N/A';
				$TRANS_PROCBY	= 'N/A';
				break;
			}
		}
		
		$TRANS_TAG		= $TAG;
		$TRANS_STATUS	= 1;
		$TRANS_TSTATUS	= $TSTATUS;
		
		$CHECKLEADQUE	= "SELECT * FROM " . $wpdb->prefix . "leads_process WHERE lp_LISTID = " . $INFO_TRANSID;
		$RESULTCHECK	= $wpdb->get_row($CHECKLEADQUE);
		
		if($RESULTCHECK){
			
			$UPDATE_LDATA 	= array(
				'lp_ACTION'		=> $TRANS_ACTION,
				'lp_TAG'		=> $TRANS_TAG,
				'lp_PROCESSBY'	=> $TRANS_PROCBY,
				'lp_DATE'		=> $TRANS_DATE,
				'lp_TIME'		=> $TRANS_TIME,
				'lp_TEXTSTATUS'	=> $TRANS_TSTATUS,				
				'lp_STATUS'		=> '1'
				
			);
			$UPDATE_LWHERE 	= array(
				'lp_LISTID'			=> $INFO_TRANSID
			);
			$UPDATE_LDFORMAT = array(
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d'
			);
			$UPDATE_LWFORMAT = array(
				'%d'
			);
			
			$QUERY_LUPDATE 	= $wpdb->update($wpdb->prefix.'leads_process', $UPDATE_LDATA, $UPDATE_LWHERE, $UPDATE_LDFORMAT, $UPDATE_LWFORMAT);
			
			if($QUERY_LUPDATE){
				$UPDATE_DATA 	= array(
					'll_STATUS'		=> $TRANS_TSTATUS,
					'll_FSTATUS'	=> 1
				);
				$UPDATE_WHERE 	= array(
					'll_ID'			=> $INFO_TRANSID
				);
				$UPDATE_DFORMAT = array(
					'%s',
					'%d'
				);
				$UPDATE_WFORMAT = array(
					'%d'
				);
				
				$QUERY_UPDATE 	= $wpdb->update($wpdb->prefix.'leads_list', $UPDATE_DATA, $UPDATE_WHERE, $UPDATE_DFORMAT, $UPDATE_WFORMAT);
			}
		}else{
			$INSERT_DATA	= array(
				'lp_LISTID'		=> $INFO_TRANSID,
				'lp_LEADID'		=> $INFO_LEADID,
				'lp_LEADOWNER'	=> $INFO_LEADOWNER,
				'lp_NAME'		=> $INFO_NAME,
				'lp_ENQUIRY'	=> $INFO_ENQUIRY,
				'lp_AGENT'		=> $INFO_AGENT,
				'lp_PROCESSBY'	=> $TRANS_PROCBY,
				'lp_ACTION'		=> $TRANS_ACTION,
				'lp_METHOD'		=> $TRANS_METHOD,
				'lp_DATE'		=> $TRANS_DATE,
				'lp_TIME'		=> $TRANS_TIME,
				'lp_TAG'		=> $TRANS_TAG,
				'lp_LOCATION'	=> $INFO_LOC,
				'lp_LOCTYPE'	=> $INFO_LOCTYPE,
				'lp_STATUS'		=> $TRANS_STATUS,
				'lp_TEXTSTATUS'	=> $TRANS_TSTATUS
			);
			
			$INSERT_FORMAT	= array(
				'%d',
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
				'%s',
				'%s',
				'%d',
				'%s'
			);
			
			$QUERY_INSERT = $wpdb->insert($wpdb->prefix.'leads_process', $INSERT_DATA, $INSERT_FORMAT);
			
			if($QUERY_INSERT){
				$UPDATE_DATA 	= array(
					'll_STATUS'		=> $TRANS_TSTATUS,
					'll_FSTATUS'	=> '1'
				);
				$UPDATE_WHERE 	= array(
					'll_ID'			=> $INFO_TRANSID
				);
				$UPDATE_DFORMAT = array(
					'%s',
					'%d'
				);
				$UPDATE_WFORMAT = array(
					'%d'
				);
				
				$QUERY_UPDATE 	= $wpdb->update($wpdb->prefix.'leads_list', $UPDATE_DATA, $UPDATE_WHERE, $UPDATE_DFORMAT, $UPDATE_WFORMAT);
			}
		}
		
		
		
		if($QUERY_UPDATE){
			$API_URL	= 'http://api.ontraport.com/1/objects?';

			$API_KEY 	= get_field('custom_api_key','option');
			$API_ID		= get_field('custom_api_id','option');
			
	
			$API_UPDATE_DATA	= array(
				'objectID'	=> 0,
				'id'		=> $INFO_LEADID,
				'f1555'		=> $TSTATUS
			);
			$API_UPDATE_RESULT 	= op_query($API_URL, 'PUT', $API_UPDATE_DATA, $API_ID, $API_KEY);

		}
	}
	
	function process_leads(){
		global $wpdb;
		
		$TRANSACTION 	= $_POST['transProc'];
		$TRANS_ID		= $_POST['transID'];
		
		$GETLEADQUE		= "SELECT * FROM ".$wpdb->prefix."leads_list WHERE ll_ID = ".$TRANS_ID;
		$RESULTGETLEAD	= $wpdb->get_row($GETLEADQUE);
		
		$INFO_LEADID	= $RESULTGETLEAD->ll_LEADID;
		$INFO_LEADOWNER	= $RESULTGETLEAD->ll_LEADOWNER;
		$INFO_AGENT		= $RESULTGETLEAD->ll_AGENT;
		$INFO_NAME		= $RESULTGETLEAD->ll_NAME;
		$INFO_LOC		= $RESULTGETLEAD->ll_LOCATION;
		$INFO_ENQUIRY	= $RESULTGETLEAD->ll_ENQUIRY;
		$INFO_TRANSID	= $RESULTGETLEAD->ll_ID;
		$INFO_LOCTYPE	= $RESULTGETLEAD->ll_LOCTYPE;
		
		switch($TRANSACTION){
			case 'CONVERT': {
				$TRANS_ACTION 	= 'Converted';
				$TRANS_METHOD	= 'Portal';
				$TRANS_TIME		= time();
				$TRANS_DATE		= time();
				$TRANS_PROCBY	= $INFO_NAME;
				$TRANS_TAG		= 'Partner Converted Lead via Portal';
				$TRANS_STATUS	= 1;
				$TRANS_TSTATUS	= 'Partner Converted';
				
				$INSERT_DATA	= array(
					'lp_LISTID'		=> $INFO_TRANSID,
					'lp_LEADID'		=> $INFO_LEADID,
					'lp_LEADOWNER'	=> $INFO_LEADOWNER,
					'lp_NAME'		=> $INFO_NAME,
					'lp_ENQUIRY'	=> $INFO_ENQUIRY,
					'lp_AGENT'		=> $INFO_AGENT,
					'lp_PROCESSBY'	=> $TRANS_PROCBY,
					'lp_ACTION'		=> $TRANS_ACTION,
					'lp_METHOD'		=> $TRANS_METHOD,
					'lp_DATE'		=> $TRANS_DATE,
					'lp_TIME'		=> $TRANS_TIME,
					'lp_TAG'		=> $TRANS_TAG,
					'lp_LOCATION'	=> $INFO_LOC,
					'lp_LOCTYPE'	=> $INFO_LOCTYPE,
					'lp_STATUS'		=> $TRANS_STATUS,
					'lp_TEXTSTATUS'	=> $TRANS_TSTATUS
				);
				
				$INSERT_FORMAT	= array(
					'%d',
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
					'%s',
					'%s',
					'%d',
					'%s'
				);
				
				$QUERY_INSERT = $wpdb->insert($wpdb->prefix.'leads_process', $INSERT_DATA, $INSERT_FORMAT);
				
				$UPDATE_DATA 	= array(
					'll_STATUS'		=> $TRANS_TSTATUS,
					'll_FSTATUS'	=> '1'
				);
				$UPDATE_WHERE 	= array(
					'll_ID'			=> $TRANS_ID
				);
				$UPDATE_DFORMAT = array(
					'%s',
					'%d'
				);
				$UPDATE_WFORMAT = array(
					'%d'
				);
				
				$QUERY_UPDATE 	= $wpdb->update($wpdb->prefix.'leads_list', $UPDATE_DATA, $UPDATE_WHERE, $UPDATE_DFORMAT, $UPDATE_WFORMAT);
				
				if($QUERY_INSERT && $QUERY_UPDATE){
					$API_URL	= 'http://api.ontraport.com/1/objects?';
					$API_DATA	= array(
						'objectID'		=> 14,
						'performAll'	=> 'true',
						'sortDir'		=> 'asc',
						'condition'		=> "tag_name='".$TRANS_TAG."'",
						'searchNotes'	=> 'true'
					);

					$API_KEY 	= get_field('custom_api_key','option');
					$API_ID		= get_field('custom_api_id','option');
					
					//$API_RESULT	= query_api_call($postargs, $API_ID, $API_KEY);
					
					$API_RESULT = op_query($API_URL, 'GET', $API_DATA, $API_ID, $API_KEY);
				
					$GetTagID 	= json_decode($API_RESULT);
					
					$TAG_ID		= $GetTagID->data[0]->tag_id;
					
					
					$INSERT_TAG_URL = 'http://api.ontraport.com/1/objects/tag';
					$INSERT_TAG_DATA = array(
						'objectID'	=> 0,
						'add_list'	=> $TAG_ID,
						'ids'		=> $INFO_LEADID,
						'condition'	=> 'id='.$INFO_LEADID
					);
					
					$INSERT_TAG_RESULT = op_query($INSERT_TAG_URL, 'PUT', $INSERT_TAG_DATA, $API_ID, $API_KEY);
					
					$API_UPDATE_DATA	= array(
						'objectID'	=> 0,
						'id'		=> $INFO_LEADID,
						'f1555'		=> 'Partner Converted'
					);
					$API_UPDATE_RESULT 	= op_query($API_URL, 'PUT', $API_UPDATE_DATA, $API_ID, $API_KEY);
					
					echo 'Successfully Processed!';
				}else{
					echo 'Failed to Process Convert Lead!';
				}
				break;
			}
			case 'SELL': {
				$TRANS_ACTION 	= 'Sell';
				$TRANS_METHOD	= 'Portal';
				$TRANS_TIME		= time();
				$TRANS_DATE		= time();
				$TRANS_PROCBY	= $INFO_NAME;
				$TRANS_TAG		= 'Sell Lead Request via Portal';
				$TRANS_STATUS	= 1;
				$TRANS_TSTATUS	= 'Partner Sold';
				
				$INSERT_DATA	= array(
					'lp_LISTID'		=> $INFO_TRANSID,
					'lp_LEADID'		=> $INFO_LEADID,
					'lp_LEADOWNER'	=> $INFO_LEADOWNER,
					'lp_NAME'		=> $INFO_NAME,
					'lp_ENQUIRY'	=> $INFO_ENQUIRY,
					'lp_AGENT'		=> $INFO_AGENT,
					'lp_PROCESSBY'	=> $TRANS_PROCBY,
					'lp_ACTION'		=> $TRANS_ACTION,
					'lp_METHOD'		=> $TRANS_METHOD,
					'lp_DATE'		=> $TRANS_DATE,
					'lp_TIME'		=> $TRANS_TIME,
					'lp_TAG'		=> $TRANS_TAG,
					'lp_LOCATION'	=> $INFO_LOC,
					'lp_LOCTYPE'	=> $INFO_LOCTYPE,
					'lp_STATUS'		=> $TRANS_STATUS,
					'lp_TEXTSTATUS'	=> $TRANS_TSTATUS
				);
				
				$INSERT_FORMAT	= array(
					'%d',
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
					'%s',
					'%s',
					'%d',
					'%s'
				);
				
				$QUERY_INSERT = $wpdb->insert($wpdb->prefix.'leads_process', $INSERT_DATA, $INSERT_FORMAT);
				
				$UPDATE_DATA 	= array(
					'll_STATUS'		=> $TRANS_TSTATUS,
					'll_FSTATUS'	=> '1'
				);
				$UPDATE_WHERE 	= array(
					'll_ID'			=> $TRANS_ID
				);
				$UPDATE_DFORMAT = array(
					'%s',
					'%d'
				);
				$UPDATE_WFORMAT = array(
					'%d'
				);
				
				$QUERY_UPDATE 	= $wpdb->update($wpdb->prefix.'leads_list', $UPDATE_DATA, $UPDATE_WHERE, $UPDATE_DFORMAT, $UPDATE_WFORMAT);
				
				if($QUERY_INSERT && $QUERY_UPDATE){
					$API_URL	= 'http://api.ontraport.com/1/objects?';
					$API_DATA	= array(
						'objectID'		=> 14,
						'performAll'	=> 'true',
						'sortDir'		=> 'asc',
						'condition'		=> "tag_name='".$TRANS_TAG."'",
						'searchNotes'	=> 'true'
					);

					$API_KEY 	= get_field('custom_api_key','option');
					$API_ID		= get_field('custom_api_id','option');
					
					//$API_RESULT	= query_api_call($postargs, $API_ID, $API_KEY);
					
					$API_RESULT = op_query($API_URL, 'GET', $API_DATA, $API_ID, $API_KEY);
				
					$GetTagID 	= json_decode($API_RESULT);
					
					$TAG_ID		= $GetTagID->data[0]->tag_id;
					
					
					$INSERT_TAG_URL = 'http://api.ontraport.com/1/objects/tag';
					$INSERT_TAG_DATA = array(
						'objectID'	=> 0,
						'add_list'	=> $TAG_ID,
						'ids'		=> $INFO_LEADID,
						'condition'	=> 'id='.$INFO_LEADID
					);
					
					$INSERT_TAG_RESULT = op_query($INSERT_TAG_URL, 'PUT', $INSERT_TAG_DATA, $API_ID, $API_KEY);
					
					$API_UPDATE_DATA	= array(
						'objectID'	=> 0,
						'id'		=> $INFO_LEADID,
						'f1555'		=> 'Partner Sold'
					);
					$API_UPDATE_RESULT 	= op_query($API_URL, 'PUT', $API_UPDATE_DATA, $API_ID, $API_KEY);
					
					echo 'Successfully Processed!';
				}else{
					echo 'Failed to Process Sell Lead!';
				}
				break;
			}
			case 'UNCONVERT': {
				$TRANS_ACTION 	= 'Unconvertible';
				$TRANS_METHOD	= 'Portal';
				$TRANS_TIME		= time();
				$TRANS_DATE		= time();
				$TRANS_PROCBY	= $INFO_NAME;
				$TRANS_TAG		= 'Partner Marks Lead as Unconvertible via Portal';
				$TRANS_STATUS	= 1;
				$TRANS_TSTATUS	= 'Partner Unconvertible';
				
				$INSERT_DATA	= array(
					'lp_LISTID'		=> $INFO_TRANSID,
					'lp_LEADID'		=> $INFO_LEADID,
					'lp_LEADOWNER'	=> $INFO_LEADOWNER,
					'lp_NAME'		=> $INFO_NAME,
					'lp_ENQUIRY'	=> $INFO_ENQUIRY,
					'lp_AGENT'		=> $INFO_AGENT,
					'lp_PROCESSBY'	=> $TRANS_PROCBY,
					'lp_ACTION'		=> $TRANS_ACTION,
					'lp_METHOD'		=> $TRANS_METHOD,
					'lp_DATE'		=> $TRANS_DATE,
					'lp_TIME'		=> $TRANS_TIME,
					'lp_TAG'		=> $TRANS_TAG,
					'lp_LOCATION'	=> $INFO_LOC,
					'lp_LOCTYPE'	=> $INFO_LOCTYPE,
					'lp_STATUS'		=> $TRANS_STATUS,
					'lp_TEXTSTATUS'	=> $TRANS_TSTATUS
				);
				
				$INSERT_FORMAT	= array(
					'%d',
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
					'%s',
					'%s',
					'%d',
					'%s'
				);
				
				$QUERY_INSERT = $wpdb->insert($wpdb->prefix.'leads_process', $INSERT_DATA, $INSERT_FORMAT);
				
				$UPDATE_DATA 	= array(
					'll_STATUS'		=> $TRANS_TSTATUS,
					'll_FSTATUS'	=> '1'
				);
				$UPDATE_WHERE 	= array(
					'll_ID'			=> $TRANS_ID
				);
				$UPDATE_DFORMAT = array(
					'%s',
					'%d'
				);
				$UPDATE_WFORMAT = array(
					'%d'
				);
				
				$QUERY_UPDATE 	= $wpdb->update($wpdb->prefix.'leads_list', $UPDATE_DATA, $UPDATE_WHERE, $UPDATE_DFORMAT, $UPDATE_WFORMAT);
				
				if($QUERY_INSERT && $QUERY_UPDATE){
					$API_URL	= 'http://api.ontraport.com/1/objects?';
					$API_DATA	= array(
						'objectID'		=> 14,
						'performAll'	=> 'true',
						'sortDir'		=> 'asc',
						'condition'		=> "tag_name='".$TRANS_TAG."'",
						'searchNotes'	=> 'true'
					);

					$API_KEY 	= get_field('custom_api_key','option');
					$API_ID		= get_field('custom_api_id','option');
					
					//$API_RESULT	= query_api_call($postargs, $API_ID, $API_KEY);
					
					$API_RESULT = op_query($API_URL, 'GET', $API_DATA, $API_ID, $API_KEY);
				
					$GetTagID 	= json_decode($API_RESULT);
					
					$TAG_ID		= $GetTagID->data[0]->tag_id;
					
					
					$INSERT_TAG_URL = 'http://api.ontraport.com/1/objects/tag';
					$INSERT_TAG_DATA = array(
						'objectID'	=> 0,
						'add_list'	=> $TAG_ID,
						'ids'		=> $INFO_LEADID,
						'condition'	=> 'id='.$INFO_LEADID
					);
					
					$INSERT_TAG_RESULT = op_query($INSERT_TAG_URL, 'PUT', $INSERT_TAG_DATA, $API_ID, $API_KEY);
					
					$API_UPDATE_DATA	= array(
						'objectID'	=> 0,
						'id'		=> $INFO_LEADID,
						'f1555'		=> 'Partner Unconvertible'
					);
					$API_UPDATE_RESULT 	= op_query($API_URL, 'PUT', $API_UPDATE_DATA, $API_ID, $API_KEY);
					
					echo 'Successfully Processed!';
				}else{
					echo 'Failed to Process Unconvertible Lead!';
				}
				break;
			}
			default: {
				echo 'Unable to Process Lead. Please contact System Administrator.';
				break;
			}
		}
		die();
	}
	add_action('wp_ajax_process_leads', 'process_leads');
	//add_action('wp_ajax_nopriv_getleads', 'getleads');
?>