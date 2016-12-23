<?php
/**
 * Template Name: Widget Page
 */

	
	get_header(); 
	
	if(!is_user_logged_in()){
		echo '<center><h1>Please login to view this page</h1></center>'; 
	}else{
		
		global $wpdb;
		
		$current_user = wp_get_current_user();
		
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

		
		$getName = json_decode($API_RESULT);		
		
		$getUserW	= "SELECT * FROM ".$wpdb->prefix."widgetoptions WHERE wid_accountid = '".$getName->data[0]->id."'";
		$getRowsW	= $wpdb->get_row($getUserW);

?>
	
	<script type="text/javascript">
		//$('#managechat').submit(ajaxSubmit);
		
		function ajaxSubmit(){
			var managechat = $('form').serialize();		
			$('#preloader').show();
			jQuery.ajax({
			   type: "POST", // HTTP method POST or GET
			   url: "<?php echo admin_url('admin-ajax.php'); ?>", //Where to make Ajax calls
			   //dataType:"text", // Data type, HTML, json etc.
			   data:managechat,
			   success:function(response){
				 //responseaction
				$('#error_container').html(response);
				get_script();
			   },
			   error:function (xhr, ajaxOptions, thrownError){
				alert("Error: " + thrownError);
			   },
			   complete: function(){
				$('#preloader').hide();
			   }
			});
			return false;
		}
		
		function get_script(){
			var Vclientid 	= <?php echo $getName->data[0]->id ?>;
			var Vdomainn 	= '<?php echo get_field('chat_domain','option'); ?>';
			var Vscripturl	= '<?php echo get_field('chat_script_url','option'); ?>';
			var Vproactive	= $('input[name=p_cpactive]:checked').val();
			var Vexitpop	= $('input[name=p_cexitpop]:checked').val();
			var Vaction		= 'processscript';
			jQuery.ajax({
			   type: "POST", // HTTP method POST or GET
			   url: "<?php echo admin_url('admin-ajax.php'); ?>", //Where to make Ajax calls
			   //dataType:"text", // Data type, HTML, json etc.
			   data:{
				   clientid:Vclientid,
				   domainn:Vdomainn,
				   scripturl:Vscripturl,
				   proactive:Vproactive,
				   exitpopup:Vexitpop,
				   action:Vaction
			   },
			   success:function(response){
				 //responseaction
				$('#scriptcontainer').html(response);
				$('#copyclipbtns').show();
				$('#emailcode').show();
			   },
			   error:function (xhr, ajaxOptions, thrownError){
				alert("Error: " + thrownError);
			   }
			});
			return false;
		}
	</script>
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.5.0/styles/default.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.12/clipboard.min.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
	<script type="text/javascript">							 
		$(document).ready(function() {
			jQuery.fn.selectText = function(){
				var doc = document;
				var element = this[0];
				console.log(this, element);
				if (doc.body.createTextRange) {
					var range = document.body.createTextRange();
					range.moveToElementText(element);
					range.select();
				} else if (window.getSelection) {
					var selection = window.getSelection();        
					var range = document.createRange();
					range.selectNodeContents(element);
					selection.removeAllRanges();
					selection.addRange(range);
				}
			};
			var clipboard = new Clipboard('#copyclipbtns');
			<?php 
				if(count($getRowsW) < 1){
					echo 'disablefields();';
				}
			?>
			$("#scriptcontainer").on('click', function(){
				$("#scriptcontainer").selectText();
			}); 
		});
		
		function disablefields(){
			$("#p_ctxt1").prop('disabled',true);
			$("#p_ctxt1").val(null);
			$("#p_ctxt2").prop('disabled',true);
			$("#p_ctxt2").val(null); 
			$("#S_OB_L1").prop('checked', true);
			$("#S_OB_R1").prop('checked', false);
		}
		
		function process_copy(id){
			alert('Copied Successfully!');
			
		}
		
		function sendemail(){
			var Vaction 		= "sendmailscript";
			var Vcontents 		= $('#p_clientid').val();
			jQuery.ajax({
			   type: "POST", // HTTP method POST or GET
			   url: "<?php echo admin_url('admin-ajax.php'); ?>", //Where to make Ajax calls
			   //dataType:"text", // Data type, HTML, json etc.
			   data:{
				   contentz:Vcontents,
				   action:Vaction
			   },
			   success:function(response){
				 //responseaction
				 if(response == "SUCCESS"){
					 alert('Email Sent Successfully!');
				 }else{
					 alert('Failed to Send Email!');
				 }
			   },
			   error:function (xhr, ajaxOptions, thrownError){
				alert("Error: " + thrownError);
			   }
			});
			return false;
		}
		
		function SelectsAll(){
			document.getElementById('scriptcontainer').focus();
			document.getElementById('scriptcontainer').select();
		}
		
		
		function enablefields(){
			$("#p_ctxt1").prop('disabled',false);			
			$("#p_ctxt2").prop('disabled',false);
			<?php
				if($getRowsW->wid_buttontype != "CUSTOM"){
					echo '$("#p_ctxt1").val(null);';
					echo '$("#p_ctxt2").val(null);';
				}else{
					echo '$("#p_ctxt1").val(\''.$getRowsW->wid_imgpathon.'\');';
					echo '$("#p_ctxt2").val(\''.$getRowsW->wid_imgpathoff.'\');';
				}
			?>
			$(".switch-input").prop('checked', false);
		}
	</script>
	<script src="https://use.fontawesome.com/8f917bffe6.js"></script>
	<style type="text/css" media="screen"> 
		.email-button ,
		.copyclipbtn {
			width: 29%;
			min-width: 100px;
			background-color: #be0000;
			border: none;
			color: #FFFFFF;
			padding:10px 15px;
			text-align: center;
			-webkit-transition-duration: 0.4s;
			transition-duration: 0.4s;
			margin: 1px 0 !important;
			text-decoration: none;
			font-size: 17px;
			border-radius: 2px ;
			border-bottom: 3px solid #a30000;
			outline: none;
		}
		.email-button:hover,
		.copyclipbtn:hover {
			background-color: #a30000;
		}

		.switch {
		  position: relative;
		  margin: 20px auto;
		  height: 30px;
		  width: 120px;
		  background: url(<?php echo $selBut->guid; ?>) no-repeat center;
		  background-size: 120px 30px;
		}

		.switch-label {
		  position: relative;
		  z-index: 2;
		  float: left;
		  width: 118px;
		  line-height: 26px;
		  font-size: 11px;
		  color: #000;
		  text-align: center;
		  cursor: pointer;
		  opacity: 0.1;
		}
		.switch-label:active {
		  font-weight: bold;
		}

		.switch-label-off {
		  padding-left: 2px;
		}

		.switch-label-on {
		  padding-right: 2px;
		}

		/*
		 * Note: using adjacent or general sibling selectors combined with
		 *       pseudo classes doesn't work in Safari 5.0 and Chrome 12.
		 *       See this article for more info and a potential fix:
		 *       http://css-tricks.com/webkit-sibling-bug/
		 */
		.switch-input {
		  display: none;
		}
		.switch-input:checked + .switch-label {
		  font-weight: bold;
		  color: rgba(0, 0, 0, 0.65);
		  text-shadow: 0 1px rgba(255, 255, 255, 0.25);
		  -webkit-transition: 0.15s ease-out;
		  -moz-transition: 0.15s ease-out;
		  -o-transition: 0.15s ease-out;
		  transition: 0.15s ease-out;
		}
		.switch-input:checked + .switch-label-on ~ .switch-selection {
		  left:1px;
		  
		  background: url(http://testing.umbrellasupport.co.uk/wp-content/uploads/2016/07/2000px-Yes_Check_Circle.svg_.png), url(<?php echo $selBut2->guid; ?>);
		  background-repeat: no-repeat, no-repeat;
		  background-position: left, center;
		  background-size: 20px auto, 120px 30px; 
		  background-position: 4px 5px, 0px 0px; 
		  /* Note: left: 50% doesn't transition in WebKit */ 
		}

		.switch-selection {
		  display: block;
		  position: absolute;
		  z-index: 1;  
		  width: 120px;
		  height: 30px; 
		  border: 0px solid #F00;
		}
		
		
		.e3ve-live-chat-icons { }
		.e3ve-live-chat-icons ul { border: 1px solid #ccc; display: inline-block; padding: 2px; }
		.e3ve-live-chat-list-heading { text-align: center; text-transform: uppercase; font-size: 21px; font-family: Open Sans; margin: 3px; background: rgb(215, 9, 10) none repeat scroll 0px 0px; color: rgb(255, 255, 255); border: 1px solid rgb(204, 204, 204); }
		.e3ve-live-chat-list-heading h2 { margin: 0px auto; padding: 10px 0px; }
		.e3ve-live-chat-icons ul li { border: 1px solid #ccc; display: inline-block; float: left; margin: 3px; width: 434px; }
		.e3ve-live-chat-icons ul li img{ margin:5px; }
		.switch-field {
		  font-family: "Lucida Grande", Tahoma, Verdana, sans-serif;
		  padding: 10px;
			overflow: hidden;
		}

		.switch-title {
		  margin-bottom: 6px;
		}

		.switch-field input {
		  display: none;
		}

		.switch-field label {
		  float: left;
		}

		.switch-field label {
		  display: inline-block;
		  width: 60px;
		  background-color: #e4e4e4;
		  color: rgba(0, 0, 0, 0.6);
		  font-size: 14px;
		  font-weight: normal;
		  text-align: center;
		  text-shadow: none;
		  padding: 6px 14px;
		  border: 1px solid rgba(0, 0, 0, 0.2);
		  -webkit-box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
		  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px rgba(255, 255, 255, 0.1);
		  -webkit-transition: all 0.1s ease-in-out;
		  -moz-transition:    all 0.1s ease-in-out;
		  -ms-transition:     all 0.1s ease-in-out;
		  -o-transition:      all 0.1s ease-in-out;
		  transition:         all 0.1s ease-in-out;
		}

		.switch-field label:hover {
			cursor: pointer;
		}

		.switch-field input:checked + label {
		  background-color: #A5DC86;
		  -webkit-box-shadow: none;
		  box-shadow: none;
		}

		.switch-field label:first-of-type {
		  border-radius: 4px 0 0 4px;
		}

		.switch-field label:last-of-type {
		  border-radius: 0 4px 4px 0;
		}
		
		.urltext {
			display: inline-block;
			width: 250px;
			background-color: #e4e4e4;
			color: rgba(0, 0, 0, 0.6);
			font-size: 14px;
			font-weight: normal;
			text-align: left;
			text-shadow: none;
			padding: 6px 14px;
			border: 1px solid rgba(0, 0, 0, 0.2);
		}
		
		input#bigbutton {
			background-color: #be0000;
			border: none;
			color: #FFFFFF;
			padding: 15px 121px;
			text-align: center;
			-webkit-transition-duration: 0.4s;
			transition-duration: 0.4s;
			margin: 1px 0 !important;
			text-decoration: none;
			font-size: 17px;
			border-radius: 5px;
			border-bottom: 3px solid #a30000;
			outline: none;
		}
			
			/***SET THE BUTTON'S HOVER AND FOCUS STATES***/
		input#bigbutton:hover, input#bigbutton:focus {
			background-color: #a30000;
		}
		
		.error{
			padding: 5px 9px;
			border: 1px solid red;
			color: red;
			border-radius: 3px;
		}

		.success{
			padding: 5px 9px;
			border: 1px solid green;
			color: green;
			border-radius: 3px;
		}
</style>
		<div id="page-content">
			<form id="managechat" type="post" action="">

				
				<?php echo get_field('lc-content-header','option'); ?>
				<div class="e3ve-live-chat-icons">
					<div class="e3ve-live-chat-list-heading"> 
						<?php echo get_field('lc-table-button-title','option'); ?>
					</div>
					<input type="hidden" id="p_clientid" name="p_clientid" value="<?php echo $getName->data[0]->id; ?>" />
					<?php 
					
						//echo photo_gallery(6); 
						
						$query 	= "SELECT * FROM ".$wpdb->prefix."posts WHERE post_title LIKE '%CHATICON%' ORDER BY post_title DESC"; 
						$images = $wpdb->get_results($query);
						
						
						
						
						if(count($images)>0){
							echo '<table border="1" cellpadding="3" cellspacing="0" align="center">';
								
								$reccount 	= 0;
								$incnum		= 1;
								foreach($images as $img){	
									$reccount++;
									if($reccount == 1){
										echo '<tr style="height:100px;">';
										echo '<td align="center" style="padding:10px; width:420px; vertical-align:middle;">';
									}								
									
									echo '<img src="'.$img->guid.'"> ';
									
									if($reccount==2){
										echo '<section class="container">';
										echo '<div class="switch switch-blue">';
										echo '<input onClick="disablefields()" type="radio" class="switch-input" id="S_CI_'.$incnum.'" name="p_cicon" value="'.$img->guid.'" '.($getRowsW->wid_buttontype == "DEFAULT" && $getRowsW->wid_imgpathoff == $img->guid ? "checked" : "").'>';
										echo '<label for="S_CI_'.$incnum.'" class="switch-label switch-label-on">Select</label>';
										echo '<span class="switch-selection"></span>';
										echo '</div>';
										echo '</section>';
										echo '</td>';
										echo '<td align="center" style="padding:10px; width:420px; vertical-align:middle;">';
									}								
									if($reccount == 4){
										//echo '<div class="funkyradio">';
										//echo '<div class="funkyradio-success">';
										//echo '<input type="radio" id="S_CI_'.$incnum.'" name="p_cicon" value="1"/>';
										//echo '<label for="S_CI_'.$incnum.'">Choose Icon</label>';
										echo '<section class="container">';
										echo '<div class="switch switch-blue">';
										echo '<input  onClick="disablefields()" type="radio" class="switch-input" id="S_CI_'.$incnum.'" name="p_cicon" value="'.$img->guid.'" '.($getRowsW->wid_buttontype == "DEFAULT" && $getRowsW->wid_imgpathoff == $img->guid ? "checked" : "").'>'; 
										echo '<label for="S_CI_'.$incnum.'" class="switch-label switch-label-on">Select</label>';
										echo '<span class="switch-selection"></span>';
										echo '</div>';
										echo '</section>';
										//echo '</div>';
										//echo '</div>';

										echo '</td>';
										echo '</tr>';
										$reccount = 0;
									}
									$incnum++;
								}
							echo '</table>';
						}else{
							echo "<h2>There are no images found in the gallery.</h2>";
						}
					?>   		 		  
				</div>
				<p style="text-align: left;"><?php echo get_field('lc-have-own-buttons-label','option'); ?></p>
				<ul>
					<li style="text-align: left; padding:5px;">
						<div class="switch-field">
							<input onClick="disablefields()" type="radio" id="S_OB_L1" name="p_obut" value="0" <?php echo ($getRowsW->wid_buttontype == "DEFAULT" ? "checked" : ""); ?> required/>
							<label for="S_OB_L1">No</label>&nbsp;&nbsp;
							<input onClick="enablefields()" type="radio" id="S_OB_R1" name="p_obut" value="1" <?php echo ($getRowsW->wid_buttontype == "CUSTOM" ? "checked" : ""); ?> />
							<label for="S_OB_R1">Yes</label>
						</div>
					</li>
					<li style="text-align: left; padding:5px;"><span style="color: #008000;"><strong>Online Image:</strong></span> <input type="text" class="urltext" name="p_cctxt1" id="p_ctxt1" value="<?php echo ($getRowsW->wid_buttontype == "CUSTOM" && $getRowsW->wid_imgpathon ? $getRowsW->wid_imgpathon : ""); ?>"> Example: <i>http://domain.com/images/online.gif</i></li>
					<li style="text-align: left; padding:5px;"><strong><span style="color: #ff0000;">Offline Image:</span> </strong> <input type="text" class="urltext" name="p_cctxt2" id="p_ctxt2" value="<?php echo ($getRowsW->wid_buttontype == "CUSTOM" && $getRowsW->wid_imgpathoff ? $getRowsW->wid_imgpathoff : ""); ?>"> Example: <i>http://domain.com/images/offline.gif</i></li>
				</ul> 
				<p><?php echo get_field('lc-live-chat-features-label','option'); ?></p>
					
					<div class="switch-field">
					  <div class="switch-title">Type of Window Chat:</div>
					  <input type="radio" id="S_TWC_L1" name="p_cwchat" value="1" <?php echo ($getRowsW->wid_chattype == 1 ? "checked" : ""); ?> required/>
					  <label for="S_TWC_L1">Pop-up</label>&nbsp;&nbsp;
					  <input type="radio" id="S_TWC_R1" name="p_cwchat" value="0" <?php echo ($getRowsW->wid_chattype == 0 ? "checked" : ""); ?>/>
					  <label for="S_TWC_R1">Window</label>
					</div>
					<div class="switch-field">
					  <div class="switch-title">Activate Pro-Active Popup Invitation:</div>
					  <input type="radio" id="S_TWC_L2" name="p_cpactive" value="1" <?php echo ($getRowsW->wid_proactive == 1 ? "checked" : ""); ?> required/>
					  <label for="S_TWC_L2">Yes</label>&nbsp;&nbsp;
					  <input type="radio" id="S_TWC_R2" name="p_cpactive" value="0" <?php echo ($getRowsW->wid_proactive == 0 ? "checked" : ""); ?> />
					  <label for="S_TWC_R2">No</label>
					</div>
					<div class="switch-field">
					  <div class="switch-title">Activate &#8220;Don&#8217;t Exit Website, Chat to Us&#8221; Popup:</div>
					  <input type="radio" id="S_TWC_L3" name="p_cexitpop" value="1" <?php echo ($getRowsW->wid_exitpop == 1 ? "checked" : ""); ?> required/>
					  <label for="S_TWC_L3">Yes</label>&nbsp;&nbsp;
					  <input type="radio" id="S_TWC_R3" name="p_cexitpop" value="0" <?php echo ($getRowsW->wid_exitpop == 0 ? "checked" : ""); ?> />
					  <label for="S_TWC_R3">No</label>
					</div>
				<p><?php echo get_field('lc-code-generation-label','option'); ?></p>
				<blockquote>
					<center>
						<div id="error_container"></div><br />
						<input type="hidden" name="action" value="processwidget"/>
						<div id="preloader" style="display:none;"><img src="http://testing.umbrellasupport.co.uk/wp-content/uploads/2016/07/preload.gif" /></div>
						<input id="bigbutton" type="button" onClick="ajaxSubmit()" name="p_submit" value="Save and Generate Code" />
					</center>
					
				</blockquote>
					<table border="1" width="100%" cellpadding="3" cellspacing="3">
						<tr>
							<td align="center">
								<h4><b>Paste this where you want your chat script appear.</b></h4> 
							</td>
						</tr>
						<tr>
							<td align="center">
								<span style="text-align:left">
									<b>Note</b>: You may copy the generated script manually or you may press the button to copy it directly to your clipboard.
								</span>
								<pre class="no-whitespace-normalization" style="width:850px"><code class="language-html" id="scriptcontainer"><br />Code appear here..<br /></code></pre><br />
								<button type="button" onClick="process_copy('scriptcontainer')" style="display:none" class="copyclipbtn" data-clipboard-target="#scriptcontainer" id="copyclipbtns">Copy to Clipboard</button>
								<button type="button" onClick="sendemail()" id="emailcode" class="email-button" style="display:none">Email Code</button>
							</td>
						</tr> 
					</table>
				<?php echo get_field('lc-content-footer','option'); ?>
				<div style="clear:both"></div>
			</form>
		</div><!-- #content -->

<?php
	}
get_footer();