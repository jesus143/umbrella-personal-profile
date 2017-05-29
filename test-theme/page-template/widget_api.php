<?php
/**
 * Template Name: Widget API
 */
?>
<html>
	<head>
		<style>
			body{
				margin:0;
			}
		</style>
	</head>
	<body>
<?php
	if(isset($wp_query->query_vars['accountid'])) {
		
		global $wpdb;
		$current_user = wp_get_current_user();
		
		$account_id = urldecode($wp_query->query_vars['accountid']);
		$web_url	= get_domain($_SERVER['HTTP_HOST']);
		
		$getNAImg 	= "SELECT * FROM ".$wpdb->prefix."posts WHERE post_title = 'NotAllowed'";
		$getNAImgR	= $wpdb->get_row($getNAImg);
		
		$check_account = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "clientsites WHERE s_accountidhash = '".$account_id."' AND s_website LIKE '%".$web_url."%' AND s_status = 1");
		
		if(count($check_account) < 1){
			echo '<img src="'.$getNAImgR->guid.'" />';
		}else{
			$getClientInfo = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."widgetoptions WHERE wid_accountid = '".$check_account->s_accountid."'");
			
			$postargs = get_field('chat_rest_api','option');
			
			$session = curl_init(); 
			curl_setopt ($session, CURLOPT_RETURNTRANSFER, true);
			curl_setopt ($session, CURLOPT_URL, $postargs);
			//curl_setopt ($session, CURLOPT_HEADER, true);
			$response = curl_exec($session); 
			curl_close($session);
			
			$chat_status = json_decode($response);
			
			if(count($getClientInfo) > 0){
				if($getClientInfo->wid_chattype == 0){
					if($chat_status->isonline == true){
?>
						<a href="#" onClick="return window.parent.lh_inst.lh_openchatWindow()" style="display:block; width:200px; height:100px; top:0; left:0; margin:0; background:url(<?php echo $getClientInfo->wid_imgpathon; ?>) no-repeat;"></a><br /><br />
						
<?php
					}else{
?>
						<a href="#" onClick="return window.parent.lh_inst.lh_openchatWindow()" style="display:block; width:200px; height:100px; top:0; left:0; margin:0; background:url(<?php echo $getClientInfo->wid_imgpathoff; ?>) no-repeat;"></a><br /><br />
<?php
					}
?>
						<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
						<script type="text/javascript">
							function extractDomain(url) {
								var domain;
								//find & remove protocol (http, ftp, etc.) and get domain
								if (url.indexOf("://") > -1) {
									domain = url.split('/')[2];
								}
								else {
									domain = url.split('/')[0];
								}

								//find & remove port number
								domain = domain.split(':')[0];

								return domain;
							}
							$(document).ready(function() { 
								document.domain = (window.location != window.parent.location) ? document.referrer : document.location;
								var URLZ;
								var acct = <?php echo $check_account->s_accountid; ?>;
								var referrer = (document.referrer) ? encodeURIComponent(document.referrer.substr(document.referrer.indexOf('://')+1)) : '';
								var location  = (document.location) ? encodeURIComponent(window.location.href.substring(window.location.protocol.length)) : '';
								
								URLZ = '//livewebchatcode.com/index.php/chat/getstatus/(click)/internal/(position)/bottom_right/(ma)/br/(top)/350/(units)/pixels/(leaveamessage)/true<?php echo ($getClientInfo->wid_proactive == 0 ? '/(disable_pro_active)/true' : ''); ?>/(theme)/1?r='+referrer+'&l='+location+'&idn='+acct;
								window.parent.load_chat(URLZ);
							<?php
								if($getClientInfo->wid_exitpop == 1){
									echo 'window.parent.bind_exitpop();';
								}
							?>
							});
						</script>
<?php

				}else{
					if($chat_status->isonline == true){
?>
						<a href="#" onClick="return window.parent.trigger_click()" style="display:block; width:200px; height:100px; top:0; left:0; margin:0; background:url(<?php echo $getClientInfo->wid_imgpathon; ?>) no-repeat;"></a><br /><br />
						
<?php
					}else{
?>
						<a href="#" onClick="return window.parent.trigger_click()" style="display:block; width:200px; height:100px; top:0; left:0; margin:0; background:url(<?php echo $getClientInfo->wid_imgpathoff; ?>) no-repeat;"></a><br /><br />
<?php
					}
?>
						<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
						<script type="text/javascript">
							function extractDomain(url) {
								var domain;
								//find & remove protocol (http, ftp, etc.) and get domain
								if (url.indexOf("://") > -1) {
									domain = url.split('/')[2];
								}
								else {
									domain = url.split('/')[0];
								}

								//find & remove port number
								domain = domain.split(':')[0];

								return domain;
							}
							$(document).ready(function() {
								document.domain = (window.location != window.parent.location) ? document.referrer : document.location;
								var URLZ;
								var acct = <?php echo $check_account->s_accountid; ?>;
								var referrer = (document.referrer) ? encodeURIComponent(document.referrer.substr(document.referrer.indexOf('://')+1)) : '';
								var location  = (document.location) ? encodeURIComponent(window.location.href.substring(window.location.protocol.length)) : '';
								URLZ = '//livewebchatcode.com/index.php/chat/getstatus/(click)/internal/(position)/bottom_right/(ma)/br/(top)/350/(units)/pixels/(leaveamessage)/true<?php echo ($getClientInfo->wid_proactive == 0 ? '/(disable_pro_active)/true' : ''); ?>/(theme)/1?r='+referrer+'&l='+location+'&idn='+acct;
								window.parent.load_chat(URLZ);
							<?php
								if($getClientInfo->wid_exitpop == 1){
									echo 'window.parent.bind_exitpop();';    
								}
							?>
							});
						</script>
<?php
				}
			}else{
				echo 'CHAT NOT SETUP!';
			}
			
		}
		//echo "USER: " . $account_id . " SITE: " . $web_url . " REFERRER: " . $_SERVER['HTTP_HOST'];
	}

?>
	</body>
</html>