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
<!--	<h3> This is the personal profle coding </h3>-->
	<?php
    	do_shortcode('[umbrella_person_profile_page]');
		$uPPUmbrellaPersonalProfile = new App\UPPUmbrellaPersonalProfile();

//	print "<pre>";
//	print_r($_POST);
//	PRINT "</pre>";

//	$uPPUmbrellaPersonalProfile->htmlPrintFbButton();
//	$uPPUmbrellaPersonalProfile->htmlPrintFacebookInfoIncludingPicture();

//		if($uPPUmbrellaPersonalProfile->getFaceBookIsAuthenticated()):
//			print "<br> user id " . $uPPUmbrellaPersonalProfile->getCurrentUserId();
//			print "<br> name " . $uPPUmbrellaPersonalProfile->getFaceBookName();
//			print "<br> email " . $uPPUmbrellaPersonalProfile->getFaceBookEmail();
//			print "<br> profile path " . $uPPUmbrellaPersonalProfile->getFaceBookProfilePicPath();
//			print "<br> authenticated " . $uPPUmbrellaPersonalProfile->getFaceBookIsAuthenticated();
//			print "<br>partner id from ontraport " . $uPPUmbrellaPersonalProfile->getPartnerIdFromOntraport();
//			print "<br> get facebook index " . $uPPUmbrellaPersonalProfile->getFaceBookImageIndex();
//			$uPPUmbrellaPersonalProfile->htmlPrintFacebookInfoIncludingPicture();
//			print "<br> ontraport facebook email tag : f1583  " . $uPPUmbrellaPersonalProfile->getOntraportFaceBookEmailTag();
//		endif;
//
//	print "<br> get facebook index " . $uPPUmbrellaPersonalProfile->getFaceBookImageIndex();
//	print " ontraport fb email " . $uPPUmbrellaPersonalProfile->getOntraportFaceBookEmailTag();


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
				$host  		= "db640728737.db.1and1.com";
				$database   = "db640728737";
				$user  		= "dbo640728737";
				$password   = "1qazxsw2!QAZXSW@";
				try{
				$WP_CON	= new PDO('mysql:host='.$host.';dbname='.$database.';', $user, $password);
				$WP_CON->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				}catch(PDOException $ERR){
					echo $ERR->getMessage();
					exit();
				}
				try{
					$QUESTRING_GETNAIMG = "SELECT * FROM wp_user_imguploads";
					$GETNAIMG_RESULT	= $WP_CON->query($QUESTRING_GETNAIMG);
					$GETNAIMG_LISTS		= $GETNAIMG_RESULT->fetch();
				}catch(PDOException $ERR){
					echo $ERR->getMessage();
					exit();
				}	
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
						<form method="post" action="" id="personalForm">	
							<div class="profile-info profile-left">
								<input type="hidden" value="<?php the_ID();?>" name="personalID" id="personalID">
								<input type="hidden" value="<?php echo $profileID; ?>" name="profileID" id="profileID">
								<h2>Personal Details</h2>
								<script>
										function readURL(input) {
											if (input.files && input.files[0]) {
												var reader = new FileReader();

												reader.onload = function (e) {
													$('#imageUploadPreview').attr('src', e.target.result);
												}

												reader.readAsDataURL(input.files[0]);
											}
										}

										$("#inputFile").change(function () {
											readURL(this);
										});
								</script>
								<div class="full-name">
									<?php
									if( get_field('hide_display_name','option') ){
										?>
											<label>Name</label>
										<?php
										if( get_field('hide_edit_name','option') ){
										?>
											<input type="text" name="name" value="<?php the_title();?>" id="name">
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
											<input type="text" name="firstAddress" value="<?php echo get_field('address_line_1');?>" id="firstAddress">
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
											<input type="text" name="secondAddress" value="<?php echo get_field('address_line_2');?>" id="secondAddress">
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
														<input type="text" name="county" value="<?php echo get_field('country');?>" id="county" maxlength="50">
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
											<input type="text" name="emailAddress" value="<?php echo get_field('email_address');?>" id="emailAddress" disabled>
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
														<input type="text" name="homePhone" value="<?php echo get_field('home_phone');?>" id="homePhone">
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
															<input type="text" name="mobilePhone" value="<?php echo get_field('mobile_phone');?>" id="mobilePhone">
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
										<input type="hidden" name="action"  value="personalProfile">
										<input type="submit" class="profile-button" value="Update Personal Profile >" id="personal-profiles">
										<div id="personal-loading" style="display:none;">
											<img src="<?php echo get_stylesheet_directory_uri().'/images/loading.gif';?>">
										</div>
									</div>
									<?php
								}else{
									
								}
								?>
								<div class="personal-log">
								</div>
							</div>
							<div class="profile-right">

								<div class="profile-image-wrapper">
									<div class="logo-wrapper">
										 <div class="file-upload btn btn-primary personal-profile-button">	
											 <span>Change Profile Picture ></span>
											 <input type="file" name="thumbnailProfile" id="inputFile" accept="image/*">
										 </div>										 
										 <input type="hidden" name="post_id" id="post_id" value="<?php echo get_the_ID();?>" />
										<?php wp_nonce_field( 'thumbnailProfile', 'thumbnailProfile_nonce' ); ?>
										<?php
										global $featured_image,$status;
											$sql = $WP_CON->prepare('SELECT profileURL AS url,ui_STATUS AS status FROM wp_user_imguploads WHERE uid_PartnerID = :parnerID');
											$sql->execute(array(':parnerID' => $profileID));
											$result = $sql->fetchObject();
											$status=$result->status;
											if(!empty($result->url)) {
												$featured_image=$result->url;
											}
											switch($status){
											  case 0  : 
												$class_watermark='class="water-wrapper water-mark"';
												$featured	= $featured_image;
												
											  break;
											  case 1  : 
												$class_watermark='class="water-wrapper"';
												$featured	= $featured_image;
											  break;
											  default : 
												$class_watermark='class="water-wrapper"';
												//$featured   = get_stylesheet_directory_uri().'/images/default-logo.jpg';
											  break;
											}
										?>
										<div <?php echo $class_watermark; ?> >
											<?php if($uPPUmbrellaPersonalProfile->getFaceBookIsAuthenticated()):
													if($uPPUmbrellaPersonalProfile->getFaceBookImageIndex() == 1) {
														print "<img id='imageUploadPreview' src='".$uPPUmbrellaPersonalProfile->getFaceBookProfilePicPath()."?height=150&width=150' alt=''  />";
													} else { ?>
														<img id="imageUploadPreview" src="<?php if(!empty($featured)){echo $featured;}else{ echo get_stylesheet_directory_uri().'/images/avatar.png';}?>" alt="" />
													 <?php
													}
												?>
											<?php else: ?>
												<img id="imageUploadPreview" src="<?php if(!empty($featured)){echo $featured;}else{ echo get_stylesheet_directory_uri().'/images/avatar.png';}?>" alt="" />
											<?php endif; ?>
										</div>
										<script>
											function readURL(input) {
												if (input.files && input.files[0]) {
													var reader = new FileReader();

													reader.onload = function (e) {
														$('#imageUploadPreview').attr('src', e.target.result);
													}

													reader.readAsDataURL(input.files[0]);
												}
											}

											$("#inputFile").change(function () {
												readURL(this);
											});
									</script>
									</div> 
								</div>

								<?php
									if($uPPUmbrellaPersonalProfile->getFaceBookIsAuthenticated()):
									   print '<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">';
										print '<div style="margin-top:34px;">';

											$uPPUmbrellaPersonalProfile->htmlPrintFacebookInfoIncludingPicture();

										print '</div>';
								  	else:
										print '<div style="margin-top:34px;margin-left: 42px;">';

											$uPPUmbrellaPersonalProfile->htmlPrintFbButton();

										print '</div>';
								  	endif;
								?>
							</div>
						</form>
						<script>
						jQuery(document).ready(function($){
							$("#personalForm").on('submit',(function(e) {
								e.preventDefault();
								var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
								//var formData = new FormData(this);		
								//alert(formData);
								$('#personal-loading').show();
								$.ajax({
									url: ajaxurl, // Url to which the request is send
									type: "POST",             // Type of request to be send, called as method
									data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
									contentType: false,       // The content type used when sending data to the server.
									cache: false,             // To unable request pages to be cached
									processData:false,        // To send DOMDocument or non processed data file it is set to false
									success: function(data)   // A function to be called if request succeeds
									{
										$('#personal-loading').hide();
										$(".personal-log").html(data);
										window.top.location.reload();
									}
								});
							}));
							/*$("#personal-profiles").click(function(){
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
							});*/
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