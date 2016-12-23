<?php
if (!session_id()) {
	session_start();
}
/*
*Template Name:Personal Profile
*/
get_header(); 
global $post_id,$currentAmount;
?>
<div id="page-content"> 
	<h3> This is the personal profle coding </h3> 
	<?php
		do_shortcode('[umbrella_person_profile_page]');
		$uPPUmbrellaPersonalProfile = new App\UPPUmbrellaPersonalProfile();
		if($uPPUmbrellaPersonalProfile->getFaceBookIsAuthenticated()):

			print "<br> user id " . $uPPUmbrellaPersonalProfile->getCurrentUserId();
			print "<br> name " . $uPPUmbrellaPersonalProfile->getFaceBookName();
			print "<br> email " . $uPPUmbrellaPersonalProfile->getFaceBookEmail();
			print "<br> profile path " . $uPPUmbrellaPersonalProfile->getFaceBookProfilePicPath();
			print "<br> authenticated " . $uPPUmbrellaPersonalProfile->getFaceBookIsAuthenticated();
			$uPPUmbrellaPersonalProfile->htmlPrintFacebookInfoIncludingPicture();
			$uPPUmbrellaPersonalProfile->htmlDesignForFaceBookRemovePopup();
		endif;

	?> 
	<h2><?php the_title();?></h2>
	<?php
		if( ! is_user_logged_in() ){
			
			while ( have_posts() ) : the_post();
				?>
				<?php
				the_content();
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			endwhile; // End of the loop.
		}else{
	?>
	<div class="business-profile">
			<?php 
			if(  is_user_logged_in() ){
				$current_user = wp_get_current_user();
				$customAPIKEY  = get_field('custom_api_key','option');// name of the admin
				$customAPIID  = get_field('custom_api_id','option');// Email Title for the admin
				//echo "Email Address: " . $current_user->user_email; 
				//$postargs = "http://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'testing@umbrellasupport.co.uk'&searchNotes=true";
				$postargs = "http://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'".$current_user->user_email."'&searchNotes=true";
				$session = curl_init();
				curl_setopt ($session, CURLOPT_RETURNTRANSFER, true);
				curl_setopt ($session, CURLOPT_URL, $postargs);
				//curl_setopt ($session, CURLOPT_HEADER, true);
				curl_setopt ($session, CURLOPT_HTTPHEADER, array(
				  'Api-Appid:'.$customAPIID,
				  'Api-Key:'.$customAPIKEY
				));
				$response = curl_exec($session); 
				curl_close($session);
				//header("Content-Type: text");
				//echo "CODE: " . $response;
				$getName 	= json_decode($response);  
				$profileID	= $getName->data[0]->id;
				$currentAmount	= $getName->data[0]->f1547;
				//var_dump($getName);
			}
			//echo '<br /><br />Name: '. $getName->data[0]->f1549;
			$arg= array( 
				'post_type'  	 	=> 'client',
				'meta_query'   		=> array(
					array(
						'key'       => 'partner_id',					
						'value'     => $profileID,
						//'value'     => $profileID,
						'compare'   => 'IN',
					)
				)
			);
			$the_query = new WP_Query($arg);
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) { $the_query->the_post();
						$post_id=get_the_ID();
						?>
						<form method="post" action="">	
							<div class="profile-info">
								<input type="hidden" value="<?php the_ID();?>" name="personalID" id="pesonal-id">
								<input type="hidden" value="<?php echo $profileID; ?>" name="profileID" id="profile-id">
								<h2>Personal Details</h2>
								<div class="full-name">
									<?php
									if( get_field('hide_display_name','option') ){
										?>
											<label>Name</label>
										<?php
										if( get_field('hide_edit_name','option') ){
										?>
											<input type="text" name="fname" value="<?php the_title();?>" id="name">
										<?php
										}else{
										?>
											<p><?php the_title();?></p>
										<?php
										}
									}
									?>
								</div>
								
								<div class="address-line-1">
									<?php
									if( get_field('hide_diplay_first_personal_address','option') ){
										?>
											<label>Address Line 1</label>
										<?php
										if( get_field('edit_first_personal_address','option') ){
										?>
											<input type="text" name="f_address" value="<?php echo get_field('address_line_1');?>" id="first-address">
										<?php
										}else{
										?>
										<p><?php echo get_field('address_line_1');?></p>
										<?php
										}
									}
									?>
								</div>
								<div class="address-line-2">
									<?php
									if( get_field('hide_display_second_personal_address','option') ){
										?>
											<label>Address Line 2</label>
										<?php
										if( get_field('edit_second_personal_address_copy','option') ){
										?>
											<input type="text" name="s_address" value="<?php echo get_field('address_line_2');?>" id="second-address">
										<?php
										}else{
										?>
										<p><?php echo get_field('address_line_2');?></p>
										<?php
										}
									}
									?>
								</div>
								<div class="clear-both">
									<ul>
										<li>
											<div class="town">
												<?php
												if( get_field('hide_display_name_town','option') ){
													?>
														<label>Town</label>
													<?php
													if( get_field('edit_display_name_town','option') ){
													?>
														<input type="text" name="town" value="<?php echo get_field('town');?>" id="town">
													<?php
													}else{
													?>
													<p><?php echo get_field('town');?></p>
													<?php
													}
												}
												?>
											</div>
										</li>
										<li>
											<div class="city">
												<?php
												if( get_field('hide_display_name_city','option') ){
													?>
													<label>City</label>
													<?php
													if( get_field('edit_display_name_city','option') ){
													?>
														<input type="text" name="city" value="<?php echo get_field('city');?>" id="city">
													<?php
													}else{
													?>
														<p><?php echo get_field('city');?></p>
													<?php
													}
												}
												?>
											</div>
										</li>
									</ul>
								</div>															
								<div class="clear-both">
									<ul>
										<li>
											<div class="county">
												<?php
												//if( get_field('hide_display_company_town','option') ){
													?>
														<label>County</label>
													<?php
													//if( get_field('edit_display_name_company_town','option') ){
													?>
														<input type="text" name="county" value="<?php echo get_field('country');?>" id="County" maxlength="50">
													<?php
													//}else{
													?>
													<!---<input type="text"  value="<?php //echo get_field('company_town');?>" disabled>--->
													<?php
													//}
												//}
												?>
											</div>	 		
										</li>
										<li>
											<div class="postcode">
												<?php
												if( get_field('hide_display_postcode','option') ){
													?>
													<label>Postcode</label>
													<?php
													if( get_field('edit_display_name_postcode','option') ){
													?>
														<input type="text" name="postcode" value="<?php echo get_field('postcode');?>" id="postcode">
													<?php
													}else{
													?>
														<p><?php echo get_field('postcode');?></p>
													<?php
													}
												}
												?>
											</div>
										</li>
									</ul>
								</div>
								<div class="email-address">
									<?php
									if( get_field('hide_display_email_address','option') ){
										?>
											<label>Email Address</label>
										<?php
										if( get_field('edit_display_name_email_address','option') ){
										?>
											<input type="text" name="emailaddress" value="<?php echo get_field('email_address');?>" id="emailaddress" disabled>
										<?php
										}else{
											?>
											<p><?php echo get_field('email_address');?></p>
										<?php
										}
									}
									?>
								</div>
								<div class="clear-both">
									<ul>
										<li>									
											<div class="home-phone">
											<?php
												if( get_field('hide_display_email_address','option') ){
													?>
														<label>Home Phone</label>
													<?php
													if( get_field('edit_display_name_home_phone','option') ){
													?>
														<input type="text" name="home_phone" value="<?php echo get_field('home_phone');?>" id="home-phone">
													<?php
													}else{
													?>
														<p><?php echo get_field('home_phone');?></p>
													<?php
													}
												}
											?>
											</div>
										</li>
										<li>								
											<div class="mobile-phones">
												<?php
													if( get_field('hide_display_home_phone','option') ){
														?>
															<label>Mobile Phone</label>
														<?php
														if( get_field('edit_display_name_mobile_phone','option') ){
														?>
															<input type="text" name="mobile_phone" value="<?php echo get_field('mobile_phone');?>" id="mobile-phone">
														<?php
														}else{
														?>
															<p><?php echo get_field('mobile_phone');?></p>
														<?php
														}
													}
												?>
											</div>
										</li>
									</ul>
								</div>
								<?php
								if( get_field('hide_edit_name','option')
								  ||get_field('edit_first_personal_address','option')
								  ||get_field('edit_second_personal_address_copy','option')
								  ||get_field('edit_display_name_town','option')
								  ||get_field('edit_display_name_city','option')
								  ||get_field('edit_display_name_postcode','option')
								  ||get_field('edit_display_name_email_address','option')
								  ||get_field('edit_display_name_home_phone','option')
								  ||get_field('edit_display_name_mobile_phone','option')
								){
									?>
									<div class="submit-button">
										<input type="submit" class="profile-button" value="Update Personal Profile >" id="personal-profiles">
									</div>
									<?php
								}else{
									
								}
								?>
							</div>
						</form>
						<div class="personal-log">
						</div>
						<script>
						jQuery(document).ready(function($){
							$("#personal-profiles").click(function(){
								var pesonalID = $("#pesonal-id").val();
								var profileID = $("#profile-id").val();
								var name = $("#name").val();
								var emailAddress= $("#emailaddress").val();
								var firstAddress= $("#first-address").val();
								var secondAddress= $("#second-address").val();
								var city= $("#city").val();
								var county= $("#County").val();
								var town= $("#town").val();
								var postcode = $("#postcode").val();
								var homePhone = $("#home-phone").val();
								var mobilePhone = $("#mobile-phone").val();
								var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
								// Returns successful data submission message when the entered information is stored in database.
								if(name==''){
									$( ".personal-log" ).append("<p class="+'personal-error-message'+"><b>✘</b>Your name must not be empty!</p>");
								}else{
									// AJAX Code To Submit Form.
									$.ajax({
										type: "POST",
										url: ajaxurl,
										timeout: 3000,
										data:{
										   action: "personalProfile",
										   pesonalID :pesonalID,
										   profileID :profileID,
										   name:name,
										   emailAddress:emailAddress,
										   firstAddress:firstAddress,
										   secondAddress:secondAddress,
										   city:city,
										   town:town,
										   postcode:postcode,
										   homePhone:homePhone,
										   mobilePhone:mobilePhone,
										   county:county,
										},
										cache: false,
										success: function(result){
											$(".personal-log").html(result);
											window.top.location.reload();
										}
									});
								}
								return false;
							});
						 });	
						</script>
						<?php
					}
				}else{
					echo wpautop( 'Sorry, no posts were found' );
				}
			?>
	</div>
	<?php
	}
	?>
</div>
<?php get_footer(); ?>