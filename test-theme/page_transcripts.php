<?php
/**
 * Template Name: Transcript Page
 */
	get_header();
	
	if(!is_user_logged_in()){
		echo '<center><h1>Please login to view this page</h1></center>'; 
	}else{
		$current_user = wp_get_current_user();
		
		global $wpdb;
		$queryB 	= "SELECT * FROM ".$wpdb->prefix."posts WHERE post_title = 'chatsicon-select-button'";
		$selBut		= $wpdb->get_row($queryB);
		$queryB2 	= "SELECT * FROM ".$wpdb->prefix."posts WHERE post_title = 'chatsicon-select-button-2'";
		$selBut2	= $wpdb->get_row($queryB2);
		
		
		
		//$postargs 	= "http://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'".$current_user->user_email."'&searchNotes=true";
		
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
		
		//print_r("RESULT " . $API_RESULT);
		//header("Content-Type: text");
		//echo "CODE: " . $response;
		
		$getName 	= json_decode($API_RESULT);		
		
		$F_HOST		= 'db639654160.db.1and1.com';
		$F_USER 	= 'dbo639654160';
		$F_PASS 	= 'LhC2016@Umbrella';
		$F_DBSE 	= 'db639654160';
		try{
			$F_CON		= new PDO('mysql:host='.$F_HOST.';dbname='.$F_DBSE.';', $F_USER, $F_PASS);
			$F_CON->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $ERR){
			echo $ERR->getMessage();
		}
		
		try{
			$FETCH_STRING 	= "SELECT * FROM lh_chat WHERE accountno='".$getName->data[0]->id."' ORDER BY id DESC";
			
			$F_RESULT		= $F_CON->query($FETCH_STRING);
			$F_LISTS		= $F_RESULT->fetchAll(PDO::FETCH_ASSOC);
		}catch(PDOException $E){
			echo $E->getMessage();
		}
?>
		<div id="page-content">
			
			<center>
				<?php echo get_field('ts-transcript-title','option'); ?>
			</center>
			<?php echo get_field('ts-header-content','option'); ?>
			<table id="example" class="display" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th bgcolor="#CCCCCC">ID</th>
						<th class="no-sort" bgcolor="#CCCCCC">Date</th>
						<th class="no-sort" bgcolor="#CCCCCC">Time(GMT)</th>
						<th bgcolor="#CCCCCC">Agent</th>
						<th bgcolor="#CCCCCC">Visitor</th>
						<th bgcolor="#CCCCCC">Website</th>
						<th class="no-sort" bgcolor="#CCCCCC">Transcript</th>
					</tr>
				</thead>
				<tbody>
					<?
						if(count($F_LISTS)>0){
							foreach($F_LISTS as $R){
								try{
									$F_AGENT_STR 	= "SELECT * FROM lh_users WHERE id = " . $R['user_id'];
									$F_AGENT_RES	= $F_CON->query($F_AGENT_STR);
									$F_AGENT_REC	= $F_AGENT_RES->fetch();
									
									
								}catch(PDOException $E){
									echo $E->getMessage();
								}
								echo '<tr>';
								echo '<td align="center">LC'.$R['id'].'</td>';
								echo '<td align="center">'.date('j', $R['time']).'<sup>'.date('S', $R['time']).'</sup> '.date('M Y', $R['time']).'</td>';
								echo '<td align="center">'.date('h:i a', $R['time']).'</td>';
								echo '<td align="center">'.$F_AGENT_REC['name'].' '.$F_AGENT_REC['surname'].'</td>';
								echo '<td align="center">'.$R['nick'].'</td>';
								echo '<td>'.parse_url($R['referrer'], PHP_URL_HOST).'</td>';
								echo '<td align="center"><a target="_blank" href="http://testing.umbrellasupport.co.uk/chatpdf/'.$R['id'].'/'.$getName->data[0]->id.'/'.md5($getName->data[0]->id).'/'.md5(date("Y/m/d")).'/pdf/">View >></a></td>';
								echo '</tr>';
								
							}
						}else{
							echo '<tr>';
							echo '<td colspan="6">NO CHAT RECORDS</td>';
							echo '</tr>';
						}
					
					?>
				</tbody>
			</table>
			
			<?php echo get_field('ts-footer-content','option'); ?>
		</div><!-- #content -->
		
		<script type="text/javascript" src="//code.jquery.com/jquery-1.12.3.js"></script>
			<script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" language="javascript" class="init">
			jQuery.noConflict();
			jQuery('#example').DataTable({
					responsive: true,
					"iDisplayLength": 25,
					"bPaginate": true,
					"bSort": false,
				    "bLengthChange": true,
				    "bFilter": true,
				    "bInfo": true,
				    "bAutoWidth": true,
					"columnDefs": [ {
						  "targets": 'no-sort',
						  "orderable": false,
					} ]
			});
			$('.dataTables_info').css('display','none');
			$('.dataTables_length').css('display','none');
			$('.dataTables_filter').css('padding-bottom','10px');
	</script>
<?php
	}
	get_footer();