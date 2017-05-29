<?php
ob_start();
/**
 * Template Name: Chat PDF Page
 */
	global $wpdb;
	$current_user = wp_get_current_user();
	
	if(!isset($wp_query->query_vars['chat_id']) && !isset($wp_query->query_vars['partner_id']) && !isset($wp_query->query_vars['hash_id']) && !isset($wp_query->query_vars['session_hash']) && !isset($wp_query->query_vars['file_type'])){
		get_header();
		echo '<div id="page-content" style="min-height:300px">';
		echo do_shortcode('[alert type="error"]Page cannot be loaded. Please do not change or update any details in the URL.[/alert]');
		//wp_safe_redirect(home_url() . '/wp-admin/'); 
		//exit();
		echo '</div>';
		get_footer();
		exit();
	}
	
	if(!is_user_logged_in()){
		get_header();
		echo '<div id="page-content" style="min-height:300px">';
		echo do_shortcode('[alert type="error"]You are not logged in! Please <a href="'.home_url().'/wp-admin/">Login</a> before you can view transcripts.[/alert]');
		//wp_safe_redirect(home_url() . '/wp-admin/'); 
		//exit();
		echo '</div>';
		get_footer();
		exit();
	}
	
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
	
	if(strlen($getName->data[0]->id) < 3){
		get_header();
		echo '<div id="page-content">';
		echo do_shortcode('[alert type="error"]We were unable to fetch your Partner ID. Please contact us for help regarding this matter.[/alert]');
		//wp_safe_redirect(home_url() . '/wp-admin/'); 
		//exit();
		echo '</div>';
		get_footer();
		exit();
	}
	
	if(md5($wp_query->query_vars['partner_id']) != $wp_query->query_vars['hash_id']){
		get_header();
		echo '<div id="page-content">';
		echo do_shortcode('[alert type="error"]We were unable to get your records. Please refrain from updating information from the URL.[/alert]');
		//wp_safe_redirect(home_url() . '/wp-admin/'); 
		//exit();
		echo '</div>';
		get_footer();
		exit();
	}
	
	if($wp_query->query_vars['partner_id'] != $getName->data[0]->id){
		get_header();
		echo '<div id="page-content">';
		echo do_shortcode('[alert type="error"]You are not allowed to get someone else\'s chat transcripts.[/alert]');
		//wp_safe_redirect(home_url() . '/wp-admin/'); 
		//exit();
		echo '</div>';
		get_footer();
		exit();
	}
	
	if($wp_query->query_vars['file_type'] != 'pdf'){
		get_header();
		echo '<div id="page-content">';
		echo do_shortcode('[alert type="error"]We did not recognize the file type. Only PDF is allowed to be generated.[/alert]');
		//wp_safe_redirect(home_url() . '/wp-admin/'); 
		//exit();
		echo '</div>';
		get_footer();
		exit();
	}
	
	
	
		
	$F_HOST		= 'db639654160.db.1and1.com';
	$F_USER 	= 'dbo639654160';
	$F_PASS 	= 'LhC2016@Umbrella';
	$F_DBSE 	= 'db639654160';
	
	try{
		$F_CON		= new PDO('mysql:host='.$F_HOST.';dbname='.$F_DBSE.';', $F_USER, $F_PASS);
		$F_CON->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}catch(PDOException $ERR){
		get_header();
		echo '<div id="page-content">';
		echo do_shortcode('[alert type="error"]We were unable to connect to the Chat Server. Please contact System Administrator.[/alert]');
		//wp_safe_redirect(home_url() . '/wp-admin/'); 
		//exit();
		echo '</div>';
		get_footer();
		exit();
	}
	
	try{
		$FETCH_STRING 	= "SELECT * FROM lh_chat WHERE id='".$wp_query->query_vars['chat_id']."' AND accountno='".$getName->data[0]->id."' ORDER BY time DESC";
		
		$F_RESULT		= $F_CON->query($FETCH_STRING);
		$F_LISTS		= $F_RESULT->fetch();
		
		if($F_RESULT->rowCount() > 0){
			try{
				$F_AGENT_STR 	= "SELECT * FROM lh_users WHERE id = " . $F_LISTS['user_id'];
				$F_AGENT_RES	= $F_CON->query($F_AGENT_STR);
				$F_AGENT_REC	= $F_AGENT_RES->fetch();
				
				
			}catch(PDOException $E){
				$F_AGENT_REC 	= '';
			}
		}else{
			$F_LISTS = '';
			$F_AGENT_REC = '';
		}
	}catch(PDOException $E){
		get_header();
		echo '<div id="page-content">';
		echo do_shortcode('[alert type="error"]We were unable to fetch Chat Transcripts. Please contact System Administrator.[/alert]');
		//wp_safe_redirect(home_url() . '/wp-admin/'); 
		//exit();
		echo '</div>';
		get_footer();
		exit();
	}
	
	
	
?>
	<style type="text/Css">
	<!--
		::selection { background-color: #E13300; color: white; }
		::-moz-selection { background-color: #E13300; color: white; }

		body {
			background-color: #fff;
			margin: 40px;
			font-size: 11px;
			color: #000000;
			font-family: 'Open Sans', sans-serif;
		}

		a {
			color: #003399;
			background-color: transparent;
			font-weight: normal;
		}

		h1 {
			color: #444;
			background-color: transparent;
			border-bottom: 1px solid #D0D0D0;
			font-size: 19px;
			font-weight: normal;
			margin: 0 0 15px 0;
			padding: 15px 15px 10px 15px;
		}

		code {
			font-family: Consolas, Monaco, Courier New, Courier, monospace;
			font-size: 11px;
			background-color: #f9f9f9;
			border: 1px solid #D0D0D0;
			color: #002166;
			display: block;
			margin: 15px 0 15px 0;
			padding: 12px 10px 12px 10px;
		}

		table {
			width: 100%;
			border-collapse: collapse;
		}

		.tbl, .tbl th, .tbl td {
		   border: 1px solid #EFEFEF;
		   padding: 5px;
		}

		#body {
			margin: 0 15px 0 15px;
		}

		p.footer {
			text-align: right;
			font-size: 11px;
			border-top: 1px solid #D0D0D0;
			line-height: 32px;
			padding: 0 10px 0 10px;
			margin: 20px 0 0 0;
		}

		#container {
			margin: 10px;
			border: 1px solid #D0D0D0;
			box-shadow: 0 0 8px #D0D0D0;
		}
	-->
	</style>
	<page style="font-size: 11px">

		<page_header>
			<div style="text-align:right;font-size:11px;">Page [[page_cu]] of [[page_nb]]</div>
		</page_header>
		
		<table cellspacing="0" border="0" cellspadding="0" style="width: 100%;">
			<tr>
				<td style="width: 72%;">
					<img src="http://portal.umbrellasupport.co.uk/wp-content/uploads/2016/03/logo-1.png" style="width: 300">
				</td>
				<td style="width: 28%;">
					
					<p 	style="color: red;"><strong>Month: <?php echo date('M Y', $F_LISTS['time']); ?></strong></p>
					Company: <?php echo $getName->data[0]->company; ?><br/>
					Website: <?php echo parse_url($F_LISTS['referrer'], PHP_URL_HOST); ?><br/>
					Country: <?php echo ucfirst($F_LISTS['country_name']); ?><br/>
					Partner ID: <?php echo $F_LISTS['accountno']; ?><br/>
					Agent Name: <?php echo isset($F_AGENT_REC) ? $F_AGENT_REC['name'].' '.$F_AGENT_REC['surname'] : 'N/A'; ?><br/>

				</td>
			</tr>
		</table>

		<br/><br/>

		<table cellspacing="0" cellspadding="0" style="width: 100% !important;">
			<tr style="background-color: #d7090a;">
				<td style="border: 1px solid #d7090a;color:#ffffff;text-align:center;padding: 5px;width: 5%"><strong>ID</strong></td>
				<td style="border: 1px solid #d7090a;color:#ffffff;text-align:center;padding: 5px;width: 10%"><strong>Date</strong></td>
				<td style="border: 1px solid #d7090a;color:#ffffff;text-align:center;padding: 5px;width: 10%"><strong>Time (GMT)</strong></td>
				<td style="border: 1px solid #d7090a;color:#ffffff;text-align:center;padding: 5px;width: 15%"><strong>IP Address</strong></td>
				<td style="border: 1px solid #d7090a;color:#ffffff;text-align:center;padding: 5px;width: 40%"><strong>Message</strong></td>
				<td style="border: 1px solid #d7090a;color:#ffffff;text-align:center;padding: 5px;width: 20%"><strong>Sender</strong></td>
			</tr>

			<?php
				if(isset($F_LISTS)){
					try{
						$FETCH_CHAT 	= "SELECT * FROM lh_msg WHERE chat_id='".$F_LISTS['id']."'";
						
						$FC_RESULT		= $F_CON->query($FETCH_CHAT);
						$FC_LISTS		= $FC_RESULT->fetchAll(PDO::FETCH_ASSOC);
						
					}catch(PDOException $E){
						$FC_LISTS = '';
					}
					
					if($FC_RESULT->rowCount()>0){
						$count = 1;
						foreach($FC_LISTS as $M){
							echo '<tr>';
							echo '<td style="border: 1px solid #EFEFEF;padding: 5px;text-align:center;">'.$count.'</td>';
							echo '<td style="border: 1px solid #EFEFEF;padding: 5px;text-align:center;">'.date("d/m/Y", $M['time']).'</td>';
							echo '<td style="border: 1px solid #EFEFEF;padding: 5px;text-align:center;">'.date("h:i a", $M['time']).'</td>';
							echo '<td style="border: 1px solid #EFEFEF;padding: 5px;text-align:center;">'.$F_LISTS['ip'].'</td>';
							echo '<td style="border: 1px solid #EFEFEF;padding: 5px;text-align:left;">'.$M['msg'].'</td>';
							
							switch($M['user_id']){
								case -1: { echo '<td style="border: 1px solid #EFEFEF;padding: 5px; color:#F00; text-align:left;">System</td>'; break; }
								case 0: { echo '<td style="border: 1px solid #EFEFEF;padding: 5px;text-align:left;">'.$F_LISTS['nick'].'</td>'; break; }
								default: { echo '<td style="border: 1px solid #EFEFEF;padding: 5px; color:#00008B; text-align:left;">'.$F_AGENT_REC['name'].' '.$F_AGENT_REC['surname'].'</td>'; break; }
								
							}
							
							echo '</tr>';
							$count++;
						}
					}else{
						echo '<tr>';
						echo '<td colspan="6">NO CHAT MESSAGES</td>';
						echo '</tr>';
					}
				}else{
					echo '<tr>';
					echo '<td colspan="6">NO CHAT MESSAGES</td>';
					echo '</tr>';
				}
			?>
			
			
		</table>

		<page_footer>
			<div style="text-align:center;font-size:11px;">
				© 2016 All Rights Reserved, Umbrella Business Support Ltd, Zeal House, 8 Deer Park Road, London, SW19 3UU. Company Number: 08708480<br/>
				Tel: 0203 0120 251 Fax: 0203 0120 254 Email: enquiries@umbrellasupport.co.uk Website: <a href="http://www.umbrellasupport.co.uk">www.UmbrellaSupport.co.uk</a>
			</div>
		</page_footer>
		
	</page>

<?php
	$content = ob_get_clean();
	
	require_once(dirname(__FILE__).'/customclass/htmltopdf/vendor/autoload.php');
	
	try{
		$html2pdf = new HTML2PDF('L', 'A4', 'fr', 'UTF-8');
        //$html2pdf->setModeDebug();
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($content);
		$html2pdf->Output($getName->data[0]->id . '-C'.substr(md5(date('Y-m-d h:i a')),0,10).'.pdf');
	}catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }

?>