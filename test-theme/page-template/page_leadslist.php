<?php
/**
 * Template Name: Leads List Page
 */
	get_header();
	
	if(!is_user_logged_in()){
		echo '<center><h1>Please login to view this page</h1></center>'; 
	}else{
		
		$transactionid = $wp_query->query_vars['transactionid'];
		
		$QUESTRING_GETLEAD 	= 'SELECT * FROM ' . $wpdb->prefix . 'leads_list WHERE ll_ID = ' . $transactionid;
		$RESULT_GETLEAD		= $wpdb->get_row($QUESTRING_GETLEAD);
		
		$current_user = wp_get_current_user();
		
		$API_URL	= 'http://api.ontraport.com/1/objects?';
		
		$API_DATA_OWNER	= array(
			'objectID'		=> 0,
			'performAll'	=> 'true',
			'sortDir'		=> 'asc',
			'condition'		=> "email='".$current_user->user_email."'",
			'searchNotes'	=> 'true'
		);
		
		$API_DATA_LEAD	= array(
			'objectID'		=> 0,
			'performAll'	=> 'true',
			'sortDir'		=> 'asc',
			'condition'		=> "id=".$RESULT_GETLEAD->ll_LEADID,
			'searchNotes'	=> 'true'
		);
		
		$API_KEY 	= get_field('custom_api_key','option');
		$API_ID		= get_field('custom_api_id','option');
		
		/* $GETINFOURI = $API_URL . urldecode(http_build_query($API_DATA));
		$GETINFO	= curl_init();
		curl_setopt ($GETINFO, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($GETINFO, CURLOPT_URL, $GETINFOURI);
		curl_setopt ($GETINFO, CURLOPT_HTTPHEADER, array('Api-Appid:' . $API_ID, 'Api-Key:' . $API_KEY));
					
		$RGETINFO = curl_exec($GETINFO); 
		curl_close($GETINFO); */
		
		$LEAD_OWNER_RESULT 	= op_query($API_URL, 'GET', $API_DATA_OWNER, $API_ID, $API_KEY);
		$LEAD_INFO_RESULT	= op_query($API_URL, 'GET', $API_DATA_LEAD, $API_ID, $API_KEY);
		
		$LEADOWNERINFO 	= json_decode($LEAD_OWNER_RESULT);
		$LEADDATAINFO	= json_decode($LEAD_INFO_RESULT);
		//echo 'Lead Owner: ' . $RESULT_GETLEAD->ll_LEADOWNER . '<br />';
		//echo 'Lead ID: ' . $VALUEJSON->data[0]->id . '<br />';
		
		$GETPROCESSLEAD	= 'SELECT * FROM ' . $wpdb->prefix . 'leads_process WHERE lp_LISTID = ' . $transactionid;
		$RESULTGETPLEAD	= $wpdb->get_row($GETPROCESSLEAD);
		
		if(isset($transactionid)){
			if($RESULT_GETLEAD->ll_LEADOWNER != $LEADOWNERINFO->data[0]->id){
				echo '<center><h1>You are not allowed to view un-owned Leads.</h1></center>';
				get_footer();
				die();
			}
			
				echo '<div id="page-content" class="leadlist-wrapper">';	
			if($RESULTGETPLEAD->lp_ACTION == 'Umbrella Converted'){
				echo '<div class="leadlist-watermark"><div class="leads-image-uconverted"></div></div>';
				echo '<script type="text/javascript">';
				echo 'jQuery(document).ready(function($){';
				echo "$('.leadlist-wrapper').css('opacity','0.5');";
				echo '});';
				echo '</script>';
			}elseif($RESULTGETPLEAD->lp_ACTION == 'Unconvertible' || $RESULTGETPLEAD->lp_ACTION == 'Umbrella Unconvertible'){
				echo '<div class="leadlist-watermark"><div class="leads-image-unconvertible"></div></div>';
				echo '<script type="text/javascript">';
				echo 'jQuery(document).ready(function($){';
				echo "$('.leadlist-wrapper').css('opacity','0.5');";
				echo '});';
				echo '</script>';
			}elseif($RESULTGETPLEAD->lp_ACTION == 'Converted'){
				echo '<div class="leadlist-watermark"><div class="leads-image-converted"></div></div>';
				echo '<script type="text/javascript">';
				echo 'jQuery(document).ready(function($){';
				echo "$('.leadlist-wrapper').css('opacity','0.5');";
				echo '});';
				echo '</script>';
			}else{
				//echo '<div class="leadlist-watermark"><div class="leads-image"></div></div>';
			}
					if($RESULT_GETLEAD->ll_LOCTYPE == 'ZIP'){
				?>
				<script type="text/javascript" src="https://maps.google.com/maps/api/js?libraries=places&key=AIzaSyD7-1FUicaFAOsymF-WgrkQgcRAS_7QVN8"></script>
				<script src="<?php bloginfo('stylesheet_directory'); ?>/js/ukpostmap.js"></script>
				<?php
					} 
				?>
				
				<script type="text/javascript">
					
					$( document ).ready(function() {  
						

						<?php
							if($RESULT_GETLEAD->ll_LOCTYPE == 'IP'){
						?>
						$.getJSON('http://ipinfo.io/<?php echo $RESULT_GETLEAD->ll_LOCATION; ?>/geo', function(data){
						  console.log(data);
						  //alert(data.Loc);
						  //alert(data.Loc);
						  //$("#mapz").attr("src","https://maps.googleapis.com/maps/api/staticmap?center=" + data.loc + "&zoom=9&size=400x250&sensor=false");
							$('#map_container').prepend('<img alt="'+data.city+'; '+data.region+'" title="'+data.city+'" src="https://maps.googleapis.com/maps/api/staticmap?center=' + data.loc + '&zoom=9&size=400x250&sensor=false&key=AIzaSyD7-1FUicaFAOsymF-WgrkQgcRAS_7QVN8" />')
						});
						<?php
							}
						?>
					});
					setTimeout(function(){
					<?php
						//echo 'alert("'.$RESULT_GETLEAD->ll_LOCTYPE.'");';
						if($RESULT_GETLEAD->ll_LOCTYPE == 'ZIP'){
							
					?>
						postcode = '<?php echo $RESULT_GETLEAD->ll_LOCATION; ?>';
						usePointFromPostcode(postcode,placeMarkerAtPoint);
					<?php
						}
					?>
					}, 3500);
					
					function load_confirm(actions){
						$('#process_name').val(actions);
						setTimeout(function(){
							$('.cmodal').delay(5).addClass('loaded');
						}, 100);
						$('.btn-close, .btn').click( function() {
							$('.cmodal').removeClass('loaded');
						});
						$('.confirm-no').click(function(){
							$('.cmodal').removeClass('loaded');
						});
						$('.cmodal-dialog').css({
							'position' : 'fixed',
							'left' : '50%',
							'top' : '50%',
							'margin-left' : -$('.cmodal-dialog').outerWidth()/2,
							'margin-top' : -$('.cmodal-dialog').outerHeight()/2
						});
					}
					
					

					function process_leads(){
						var process = $('#process_name').val();
						$('#preloader').show();
						var tid = <?php echo $RESULT_GETLEAD->ll_ID; ?>;
						$.ajax({
						   type: "POST", // HTTP method POST or GET
						   url: "<?php echo admin_url('admin-ajax.php'); ?>", //Where to make Ajax calls
						   //dataType:"text", // Data type, HTML, json etc.
						   data:{transProc:process, transID:tid, action:'process_leads'},
						   success:function(response){
							//$('.vticker ul').html(response);
							alert(response);
							$('.cmodal').removeClass('loaded');
							location.reload();
						   },
						   error:function (xhr, ajaxOptions, thrownError){
							alert("Error: " + thrownError);
						   },
						   complete: function(){
							$('#preloader').hide();
						   }
						});
					}
				</script>
				<div class="leads-content">
					<div class="leadsrow">
						<div class="leadscol-12">
							<div class="leads-idcontainer">
								<span style="border:1px solid #000; padding: 5px;"><b>Lead ID:</b> <?php echo $RESULT_GETLEAD->ll_LEADID; ?></span>
							</div>
						</div>
					</div>
					<div class="leadsrow">
						<div class="leads-infocontainer">
							<div class="leadsrow">
								<div class="leadscol-5">
									Name:<b>
									<?php 
										if($RESULTGETPLEAD->lp_ACTION=="Umbrella Converted" || $RESULTGETPLEAD->lp_ACTION == "Umbrella Unconvertible"){
											echo substr(ucfirst($LEADDATAINFO->data[0]->firstname), 0, 2) . '*****'. ' ' .substr(ucfirst($LEADDATAINFO->data[0]->lastname), 0, 2) . '*****';
										}else{
											echo ucfirst($LEADDATAINFO->data[0]->firstname) . ' ' . ucfirst($LEADDATAINFO->data[0]->lastname);
										}
										 
									?></b>
								</div>
								<div class="leadscol-5">
									Email: <b>
									<?php 
										if($RESULTGETPLEAD->lp_ACTION=="Umbrella Converted" || $RESULTGETPLEAD->lp_ACTION == "Umbrella Unconvertible"){
											$prop=2;
											$domain = substr(strrchr($LEADDATAINFO->data[0]->email, "@"), 1);
											$mailname=str_replace($domain,'',$LEADDATAINFO->data[0]->email);
											$name_l=strlen($mailname);
											$domain_l=strlen($domain);
											for($i=0;$i<=$name_l/$prop-1;$i++){
												$start.='*';
											}

											for($i=0;$i<=$domain_l/$prop-1;$i++){
												$end.='*';
											}
											
											echo substr_replace($mailname, $start, 2, $name_l-3).substr_replace($domain, $end, 2, ($domain_l/$prop)-1);
										}else{
											echo $LEADDATAINFO->data[0]->email;
										}
										 
									?></b>
								</div>
							</div>
							<div class="leadsrow">
								<div class="leadscol-5">
									Mobile: <b>
									<?php 
										if($RESULTGETPLEAD->lp_ACTION=="Umbrella Converted" || $RESULTGETPLEAD->lp_ACTION == "Umbrella Unconvertible"){
											//echo substr(ucfirst($LEADDATAINFO->data[0]->firstname), 0, 2) . '*****'. ' ' .substr(ucfirst($LEADDATAINFO->data[0]->lastname), 0, 2) . '*****';
											echo (isset($LEADDATAINFO->data[0]->cell_phone) ? substr(ucfirst($LEADDATAINFO->data[0]->cell_phone), 0, 4) . '*****' : 'Unknown'); 
										}else{
											echo (isset($LEADDATAINFO->data[0]->cell_phone) ? $LEADDATAINFO->data[0]->cell_phone : 'Unknown'); 
										}
										
									?></b>
								</div>
								<div class="leadscol-5">
									Phone: <b>
									<?php 
										if($RESULTGETPLEAD->lp_ACTION=="Umbrella Converted" || $RESULTGETPLEAD->lp_ACTION == "Umbrella Unconvertible"){
											//echo substr(ucfirst($LEADDATAINFO->data[0]->firstname), 0, 2) . '*****'. ' ' .substr(ucfirst($LEADDATAINFO->data[0]->lastname), 0, 2) . '*****';
											echo (isset($LEADDATAINFO->data[0]->home_phone) ? substr(ucfirst($LEADDATAINFO->data[0]->home_phone), 0, 4) . '*****' : 'Unknown'); 
										}else{
											echo (isset($LEADDATAINFO->data[0]->home_phone) ? $LEADDATAINFO->data[0]->home_phone : 'Unknown'); 
										}
										//echo (isset($LEADDATAINFO->data[0]->home_phone) ? $LEADDATAINFO->data[0]->home_phone : 'No Phone'); 
									?></b>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="cmodal" id="modal-one" aria-hidden="true">
					<div class="cmodal-dialog">
						<div class="cmodal-header">
							<h2><img width="50%" height="auto" style="margin:0 auto; text-align:center; display:block" alt="umbrella support centre" src="<?php echo get_template_directory_uri().'/images/Umbrella-logo.png';?>"></h2>
							<a href="#" class="btn-close" araa-hidden="true">×</a> 
						</div>
					
						<div class="cmodal-body">
							<center>
								Are you sure you want to proceed with this action?<br />
								<input type="hidden" id="process_name" value="">
								<button onClick="return process_leads()" class="confirm-yes">YES</button> 
								<button type="submit" class="confirm-no">NO</button> 
							</center>
						</div>
					</div>
				</div>
				
				<div>
					<p>
						<table border="0" cellpadding="3" cellspacing="0" width="100%">
							<tr>
								<td width="550">Enquiry :</td>
								<td>Location</td>
							</tr>
							<tr>
								<td>
									<i><?php echo $LEADDATAINFO->data[0]->f1561; ?></i><br /><br />
									Post Code: <?php echo $LEADDATAINFO->data[0]->zip; ?> <br /><br />
									<table border="0" width="480" cellpadding="5" cellspacing="0" style="border:1px solid #CCC">
										<tr>
											<td colspan="2" align="center">
												<b>Lead Source</b>
											</center>
										</tr>
										<tr>
											<td align="center" width="100">
												<img style="" src="<?php echo get_stylesheet_directory_uri().'/images/fingerprint.png';?>" width="100" height="100">
											</td>
											<td>
												Time: <?php echo date('h:ia', $RESULT_GETLEAD->ll_TIME); ?><br />
												Date: <?php echo date('D', $RESULT_GETLEAD->ll_DATE) . ' ' .date('j', $RESULT_GETLEAD->ll_DATE).'<sup>'.date('S', $RESULT_GETLEAD->ll_DATE).'</sup> '.date('M Y', $RESULT_GETLEAD->ll_DATE);  ?><br /> 
												Source: <?php echo $LEADDATAINFO->data[0]->f1568; ?> <br />
												Agent Name: <?php echo (isset($RESULTGETPLEAD->lp_AGENT) ? $RESULTGETPLEAD->lp_AGENT : $RESULT_GETLEAD->ll_AGENT); ?> <br />
												Lead ID: <?php echo $RESULT_GETLEAD->ll_LEADID; ?> <br />
											</td>
										</tr>
									</table>
								</td>
								<td align="center">
									<?php
										if($RESULT_GETLEAD->ll_LOCTYPE == "IP"){
									?>
									<div id="map_container" style="height:250px;width:100%;max-width:100%;list-style:none; transition: none;overflow:hidden;">
										
									</div>
									
									<?php
											echo 'Approximate Location Based on their <br /> I.P. Address: ' . $LEADDATAINFO->data[0]->IPAddress_489 . ' <span style="font-size:10px">(may not be accurate)</span>';
										}elseif($RESULT_GETLEAD->ll_LOCTYPE == "ZIP"){
									?>
									<input name="tb_searchlocation" type="hidden" id="tb_searchlocation" size="50" />
									<div id="map_canvas" style="height:250px;width: 326px;max-width:100%;list-style:none; transition: none;overflow:hidden;">
										
									</div>
									<div id="div_address" align="center"></div>
									<?php
											//echo 'Based on this Post Code: '. $LEADDATAINFO->data[0]->zip;
										}else{
									?>
									<div style="height:250px;width:100%; background-color:#CCC; max-width:100%;list-style:none; transition: none;overflow:hidden;">
										&nbsp;
									</div>
									<?php
											echo 'Unknown Location';
										}
									?> 
								</td>
							</tr>
						
						</table>
					</p>
				</div>
				<div>
					<p>
						<table border="0" cellpadding="3" cellspacing="0" width="100%" style="text-align: center;">
							<?php
								if(!$RESULTGETPLEAD){
							?>
							<tr>
								<td>
									<button onClick="return load_confirm('CONVERT')" class="page-list-button mark-converted"></button>
								</td>
								<td>
									<button onClick="return load_confirm('SELL')" class="page-list-button sell-enquiry"></button>
								</td>
								<td>
									<button onClick="return load_confirm('UNCONVERT')" class="page-list-button mark-unconverted"></button>
								</td>
							</tr>
							<?php
								}else{
							?>
							<tr>
								<td>
									<?php
										if($RESULTGETPLEAD->lp_ACTION == 'Converted' || $RESULTGETPLEAD->lp_ACTION == 'Umbrella Converted'){
											echo '<button class="page-list-button mark-converted-active"></button>';
										}else{
											echo '<button class="page-list-button mark-converted-inactive" style="cursor:not-allowed"></button>';
										}
										
										if($RESULTGETPLEAD->lp_ACTION == 'Converted'){
											echo '<p style="font-size:10px; padding:2px !important; text-align:center;">';
											if($RESULTGETPLEAD->lp_PROCESSBY == $RESULT_GETLEAD->ll_NAME){
												echo 'You';
											}else{
												echo $RESULTGETPLEAD->lp_PROCESSBY;
											}
											
											echo ' '.$RESULTGETPLEAD->lp_ACTION.'<br />';
											echo date('h:ia', $RESULTGETPLEAD->lp_TIME) . ' on ' . date('d/m/Y', $RESULTGETPLEAD->lp_DATE) . '<br />';
											echo 'via ';
											
											if($RESULTGETPLEAD->lp_METHOD == 'Portal'){
												echo 'Support Centre';
											}else{
												echo $RESULTGETPLEAD->lp_METHOD;
											}
											echo '</p>';
										}
										
										if($RESULTGETPLEAD->lp_ACTION == 'Umbrella Converted'){
											echo '<p style="font-size:10px; padding:2px !important; text-align:center;">';
											echo $RESULTGETPLEAD->lp_ACTION.'<br />';
											echo date('h:ia', $RESULTGETPLEAD->lp_TIME) . ' on ' . date('d/m/Y', $RESULTGETPLEAD->lp_DATE) . '<br />';
											echo '</p>';
										}
									?>
									
								</td>
								<td>
									<?php
										if($RESULTGETPLEAD->lp_ACTION == 'Sell'){
											echo '<button class="page-list-button sell-enquiry-active"></button>';
										}else{
											echo '<button class="page-list-button sell-enquiry-inactive" style="cursor:not-allowed"></button>';
										}
										
										if($RESULTGETPLEAD->lp_ACTION == 'Sell'){
											echo '<p style="font-size:10px; padding:2px !important; text-align:center;">';
											echo date('h:ia', $RESULTGETPLEAD->lp_TIME) . ' on ' . date('d/m/Y', $RESULTGETPLEAD->lp_DATE) . '<br />';
											echo 'via ';
											
											if($RESULTGETPLEAD->lp_METHOD == 'Portal'){
												echo 'Support Centre';
											}else{
												echo $RESULTGETPLEAD->lp_METHOD;
											}
											echo '</p>';
										}
									?>
									
								</td>
								<td>
									<?php
										if($RESULTGETPLEAD->lp_ACTION == 'Unconvertible' || $RESULTGETPLEAD->lp_ACTION == 'Umbrella Unconvertible'){
											echo '<button class="page-list-button mark-unconverted-active"></button>';
										}else{
											echo '<button class="page-list-button mark-unconverted-inactive" style="cursor:not-allowed"></button>';
										}
										
										if($RESULTGETPLEAD->lp_ACTION == 'Unconvertible'){
											echo '<p style="font-size:10px; padding:2px !important; text-align:center;">';
											if($RESULTGETPLEAD->lp_PROCESSBY == $RESULT_GETLEAD->ll_NAME){
												echo 'You';
											}else{
												echo $RESULTGETPLEAD->lp_PROCESSBY;
											}
											
											echo ' Marked as ' . $RESULTGETPLEAD->lp_ACTION . '<br />';
											echo date('h:ia', $RESULTGETPLEAD->lp_TIME) . ' on ' . date('d/m/Y', $RESULTGETPLEAD->lp_DATE) . '<br />';
											echo 'via ';
											
											if($RESULTGETPLEAD->lp_METHOD == 'Portal'){
												echo 'Support Centre';
											}else{
												echo $RESULTGETPLEAD->lp_METHOD;
											}
											echo '</p>';
										}
										
										if($RESULTGETPLEAD->lp_ACTION == 'Umbrella Unconvertible'){
											echo '<p style="font-size:10px; padding:2px !important; text-align:center;">';
											echo 'Umbrella Marked as Unconvertible<br />';
											echo date('h:ia', $RESULTGETPLEAD->lp_TIME) . ' on ' . date('d/m/Y', $RESULTGETPLEAD->lp_DATE) . '<br />';

											echo '</p>';
										}
									?>
									
								</td>
							</tr>
							<?php
								}
							?>
							<tr>
								<td colspan="3" align="center">
									<center><div id="preloader" style="display:none;"><img src="http://testing.umbrellasupport.co.uk/wp-content/uploads/2016/07/preload.gif" /></div></center>
								</td>
							</tr>
						</table>
					</p>
				</div>
				<!--
				<div>
					<p>
						<table border="0" cellpadding="3" cellspacing="0" width="100%" class="confirmation-wrapper">
							<tr>
								<td>
									<button id="confirmbutton" class="history-list-button check"><img src="<?php echo get_stylesheet_directory_uri().'/images/checkpng.png';?>">Check</button><button class="history-list-button wrong" onClick="return load_confirm()"><img src="<?php echo get_stylesheet_directory_uri().'/images/wrong.png';?>">Wrong</button>
								</td>
							</tr>
						</table>
					</p>
				</div>
				-->
			</div>
			<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.js"></script>
			<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.simplemodal.js"></script>
			<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/confirm.js"></script>
<?php
		}else{
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
			
			//print_r("RESULT " . $API_RESULT);
			//header("Content-Type: text");
			//echo "CODE: " . $response;
			
			$getName 	= json_decode($API_RESULT);		
			
			$QUESTRING_GETLEADS = 'SELECT * FROM ' . $wpdb->prefix . 'leads_list WHERE ll_LEADOWNER = \''.$getName->data[0]->id.'\' ORDER BY ll_DATE DESC';
			$RESULT_GETLEADS	= $wpdb->get_results($QUESTRING_GETLEADS, OBJECT);
?>
			<div id="page-content">
				
				<center>
					<?php //echo get_field('ts-transcript-title','option'); ?>
				</center>
				<?php //echo get_field('ts-header-content','option'); ?>
				<table id="example" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th bgcolor="#CCCCCC">Time</th>
							<th bgcolor="#CCCCCC">Date</th>
							<th bgcolor="#CCCCCC">Name</th>
							<th bgcolor="#CCCCCC">Location</th>
							<th bgcolor="#CCCCCC">Enquiry</th>
							<th bgcolor="#CCCCCC">Status</th>
						</tr>
					</thead>
					<tbody>
						<?
							if(count($RESULT_GETLEADS)>0){
								foreach($RESULT_GETLEADS as $R){
									echo '<tr>';
									echo '<td align="center">'.date('h:i a', $R->ll_TIME).'</td>';
									echo '<td align="center">'.date('j', $R->ll_DATE).'<sup>'.date('S', $R->ll_DATE).'</sup> '.date('M Y', $R->ll_DATE).'</td>';
									echo '<td align="center">'.$R->ll_NAME.'</td>';
									echo '<td align="center">'.$R->ll_LOCATION.'</td>';
									echo '<td align="center">'.$R->ll_ENQUIRY.'</td>';
									echo '<td>'.$R->ll_STATUS.'</td>';
									echo '</tr>';
								}
							}else{
								echo '<tr>';
								echo '<td colspan="6">NO RECORDS</td>';
								echo '</tr>';
							}
						
						?>
					</tbody>
				</table>
				
				<?php //echo get_field('ts-footer-content','option'); ?>
			</div><!-- #content -->
			
			<script type="text/javascript" src="//code.jquery.com/jquery-1.12.3.js"></script>
			<script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
			<script type="text/javascript">
					
				jQuery.noConflict();
				jQuery('#example').DataTable({
						responsive: true,
						"bPaginate": true,
						"bLengthChange": true,
						"bFilter": true,
						"bSort": true,
						"bInfo": true,
						"bAutoWidth": true,
						"columnDefs": [ {
							  "targets": 'no-sort',
							  "orderable": false,
						} ]
				});

			</script>
<?php
		}
	}
	get_footer();