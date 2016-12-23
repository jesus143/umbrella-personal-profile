<?php
/*
*Template Name:Pdf File
*/
get_header();

/** FETCHING PARAMATERS **/
/*
	--PARAMETER NAMES--
	add_charge
	account_number; 
	account_manager; 
	company_name; 

*/
	$result = $wpdb->get_results ( "SELECT * FROM ".$wpdb->prefix."billings" );
	$current_user	= wp_get_current_user();
	?>
	<div id="page-content">
		<?php
		if( ! is_user_logged_in() ){
			while ( have_posts() ) : the_post();
				?>
				<h2><?php the_title();?></h2>
				<?php
				the_content();
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			endwhile; // End of the loop.
		}else{
			$current_user	= wp_get_current_user();
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
			$response = curl_exec( $session ); 
			curl_close( $session );
			//header("Content-Type: text");
			//echo "CODE: " . $response;
			$getName 	= json_decode( $response );  
			$date		= date( "n", $getName->data[0]->date );
			$ydate		= date( "Y", $getName->data[0]->date );
			$vToday 	= date( 'Y-m-d H:i:s' );
			$pDate 		= date( "Y-m-d H:i:s", $getName->data[0]->date );
			$begin 		= new DateTime( $pDate );
			$end 		= new DateTime($ydate);
			$interval 	= DateInterval::createFromDateString( '1 month' );
			$period 	= new DatePeriod( $begin, $interval, $end);
			$counter=0;
			foreach( $period as $dt ) {
				$counter++;
			}
			switch( $counter ){
				case 0 : $total = $date; break;
				default: $total = $counter+$date; break;
			}
			?>
			<div class="billing-wrapper">
			<?php
			$acount_id=$getName->data[0]->id;
			if(empty($acount_id)){
				while ( have_posts() ) : the_post();
					?>
					<h2><?php the_title();?></h2>
					<?php
					the_content();
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				endwhile; // End of the loop.
			}
			else{
			?>
				<table >
					<thead>
						<tr>
						  <th>Month</th>
						  <th>Account Number</th>
						  <th>Business</th>
						  <th>Monthly Bill</th>
						</tr>
					</thead>
					<tbody>
					<?php
						for ($m=$date; $m<=$total; $m++) {
							$month 		= date( 'F Y', mktime( 0,0,0,$m, 1, $ydate ) );
							$pdf_month 	= date('F', mktime( 0,0,0,$m, 1, $ydate ) );
							$pdf_year 	= date('Y', mktime( 0, 0, 0, $m, 1, $ydate) );
							?>
							<tr>
							  <td><?echo $month; ?></td>
							  <td><?php echo $getName->data[0]->id; ?></td>
							  <td><?php echo $getName->data[0]->company; ?></td>
							  <td><a href="<?php echo get_permalink(280).$pdf_year.'/'.$pdf_month.'/'.$getName->data[0]->id.'-'. substr(md5($getName->data[0]->firstname),0,6).'/pdf'; ?>" target="_blank">PDF here >></td>
							</tr>
							
						<?php
						}
					?>	
					</tbody>
				</table>
			<?php 
			}
			?>	
			</div>
			<?php
		}
		?>
	</div>
<?php get_footer();?>