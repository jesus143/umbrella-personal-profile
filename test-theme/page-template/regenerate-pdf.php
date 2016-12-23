<?php
/*
*Template Name: View data
*/
//get_header();
function pdf_regenerate(){
	ob_start();
	if(	isset($wp_query->query_vars['pdf_year']) && 
		isset($wp_query->query_vars['pdf_month']) &&
		isset($wp_query->query_vars['pdf_accounts']) &&
		isset($wp_query->query_vars['pdf_md5']) &&
		isset($wp_query->query_vars['pdf_file'])) { //Checks if these parameters are queried.
		
		$pdf_year	 	= urldecode($wp_query->query_vars['pdf_year']);
		$pdf_month 		= urldecode($wp_query->query_vars['pdf_month']);
		$pdf_account 	= urldecode($wp_query->query_vars['pdf_accounts']);
		$pdf_md5		= urldecode($wp_query->query_vars['pdf_md5']);
		$pdf_file 		= urldecode($wp_query->query_vars['pdf_file']);
	}
    global $wpdb;
	$customAPIKEY  = get_field('custom_api_key','option');// name of the admin
	$customAPIID  = get_field('custom_api_id','option');// Email Title for the admin
	//$postargs 	= "http://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'".$current_user->user_email."'&searchNotes=true";
	$postargs 		= "http://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'testing@umbrellasupport.co.uk'&searchNotes=true";
	$request		= "";
	$session 		= curl_init();
	curl_setopt ( $session, CURLOPT_RETURNTRANSFER, true );
	curl_setopt ( $session, CURLOPT_URL, $postargs );
	//curl_setopt ($session, CURLOPT_HEADER, true);
	curl_setopt ( $session, CURLOPT_HTTPHEADER, array(
	 'Api-Appid:'.$customAPIID,
	 'Api-Key:'.$customAPIKEY
	));
	$response 		= curl_exec( $session ); 
	curl_close( $session );
	//header("Content-Type: text");
	//echo "CODE: " . $response;
	$getName 		= json_decode( $response ); 
    $result 		= $wpdb->get_results ( "SELECT * FROM ".$wpdb->prefix."billings WHERE tbl_account_number='".$getName->data[0]->id."' AND UPPER(DATE_FORMAT(FROM_UNIXTIME(tbl_billing_date), '%M %Y')) = '".$pdf_month.' '.$pdf_year."'" );
	$current_user	= wp_get_current_user();
?>
<style>
.pdf-content{
	width: 1117px;
    margin: 0 auto;
	font-family: sans-serif;
}
.pdf-content .logo {
    float: left;
	padding-top: 17px;
}
.pdf-content .logo img {
    width: 356px;
}
.pdf-content .logo-content {
    width: 326px;
    float: right;
	font-family: sans-serif;
}
.pdf-content .logo-content h3{
	color: #c10000;
    font-size: 17px;
}
.pdf-content .logo-content p{margin: 0;}
.table-content {
    clear: both;
	padding-top: 19px;
}
.table-content table{
	width: 100%;text-align: center;
	font-family: sans-serif;
	border-spacing: 0;
    border-collapse: collapse;
}
.table-content th,
.table-content td{
	padding: 5px 1px;
	font-family: sans-serif;
	border: 1px solid #9E9E9E;
}
</style>
<div class="pdf-content">
	<div class="logo-wrapper">
		<div class="logo">
			sample
			<a href="<?php bloginfo('home'); ?>"><img src="<?php echo get_option('of_logo') ?>" alt="<?php bloginfo('name'); ?>"></a>
		</div>
		<div class="logo-content">
			<h3>Month :<?php echo ucfirst($pdf_month).' '.$pdf_year;?> </h3>
			<p><?php 
					/*echo $pdf_year.'<br>';	
					echo $pdf_month.'<br>'; 		
					echo $pdf_account.'<br>'; 		
					echo $pdf_md5.'<br>'; 		
					echo $pdf_file.'<br>';*/
				?> 
			</p>
			<?php 
			echo '<p> Business Name :'	.$getName->data[0]->company.'</p>';
			echo '<p> Account Number :'	.$getName->data[0]->id.'</p>';			
			echo '<p> Account Manager :'.$getName->data[0]->firstname.' '.$getName->data[0]->lastname.'</p>';
			?>
		</div>
	</div>
	<div class="table-content">
		<table colspan="0" rowspan="0" border="0">
			<tr>
			 <th>Date</th>
			 <th>Time(GMT)</th>
			 <th>Services Name</th>
			 <th>Charge</th>
			 <th>Quantity</th>
			 <th>Description</th>
			 <th>Total</th>
			 <th>Account Balance</th>
			</tr>
			<tr>
			  <?php	
				foreach ( $result as $print )   {
					$date_time		=date('F d,Y',$print->tbl_billing_date);
					$charge			=$print->tbl_payment_type;
					switch($charge){
						case 'credit' : 
							$charge_color	= '00a651';
							$charge_value	= $print->tbl_credit ;
						break;
						case 'charge' : 
							$charge_color	= 'a60008';
							$charge_value	= $print->tbl_charge;
						break;
						default:
							$charge_color	=" " ;
						break;
						
					}
					$currency=$print->tbl_currency;
					switch($currency){
						case 'POUND' : $symbol='£';	break;
						default		 : $symbol='£';break;			
					}
					echo '<tr>';	
					echo '<td>'				 .$date_time.									 '</td>';
					?>
					<td>				
					<?php
					date_default_timezone_set('Europe/London');
					echo $mydate	= date('P');
					?>
					</td>
					<?php
					echo '<td>'				 .$print->tbl_services.							 '</td>';
					echo '<td style="color:#'. $charge_color.';" >'.$symbol.''.number_format($charge_value,2).'</td>';
					echo '<td>'				 .$print->tbl_quantity.							 '</td>';
					echo '<td>'				 .$print->tbl_billing_description.				 '</td>';
					echo '<td style="color:#'. $charge_color.';">'.$symbol.''.number_format($print->tbl_total,2).'</td>';
					echo '<td>'				 .$symbol.''.number_format($print->tbl_account_balance,2).'</td>';
					echo '</tr>';
				}
			  ?>
			  
			</tr>               
		</table>
	</div>
</div>
<?php
return ob_end_clean();
}
error_reporting(0);
require_once( get_stylesheet_directory() . '/pdf/fpdf.php' );
$image1 	=get_option('of_logo') ;
//include( PATH .get_template_part().'/pdf/fpdf.php' );
/*$pdf 	= new FPDF('L','mm','A4');
$pdf	-> AddFont( 'Courier','','courier.php');
$pdf	-> AddPage( );
$pdf	-> SetFont( 'Courier','',14 );
$pdf	-> Image( $image1, 10, 10,100 );
$pdf	-> Output();*/
class PDF extends FPDF
{
	// Simple table
function BasicTable($header, $data)
{
    // Header
    foreach($header as $col)
        $this->Cell(32,7,$col,1);
    $this->Ln();
    // Data
    foreach($data as $row)
    {
        foreach($row as $col)
            $this->Cell(40,6,$col,1);
        $this->Ln();
    }	
}
}
$pdf 	 = new PDF('L','mm','A4');
/*$header	 = array('Date', 'Time(GMT)', 'Services Name', 'Services Name', 'Charge', 'Quantity', 'Description', 'Total','Account Balance');
$data	 = array(
	          array('1', 'Item-1', '5000'),
	          array('2', 'Item-2', '1200'),
	          array('3', 'Item-3', '1800'),
	         );*/
$pdf	->SetFont('Arial','',12);
$pdf	->AddPage();
$pdf	-> Image( $image1, 10, 10,100 );

$pdf	->BasicTable($header,$data);
$pdf	->Output();
?>