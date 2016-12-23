<?php
/*
*Template Name:Business Profile
*/
get_header(); 
global $currentAmount;
?>
<div id="page-content">
	<h2><?php the_content();?></h2>
	<div class="business-profile">
		<div id="Profile">
			<?php
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
			//$postargs = "http://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'marvin.romagos@yahoo.com'&searchNotes=true"; 
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
			//var_dump($getName);
			$businessID	= $getName->data[0]->id;
			$currentAmount	= $getName->data[0]->f1547;
			$arg= array( 
				'post_type'  	 	=> 'client',
				'meta_query'   		=> array(
					array(
						'key'       => 'partner_id',					
						//'value'     => '77514',
						'value'     => $businessID,
						'compare'   => 'IN',
					)
				)
			);
			$the_query = new WP_Query($arg);
			//var_dump($the_query);
				if ( $the_query->have_posts() ) {
					while ( $the_query->have_posts() ) { $the_query->the_post();
						?>
						<form id="businessForm" method="post" action="" enctype="multipart/form-data">
							<div class="profile-info business-profile-wrapper">
								<div class="business-profile-right">
									<input type="hidden" name="businessID" value="<?php echo $businessID;?>" id="BusinessID">
									<h2>Company Details</h2>
									<div class="full-name">
										<?php
										if( get_field('hide_display_name','option') ){
											?>
												<label>Name</label>
											<?php
											if( get_field('hide_edit_name','option') ){
											?>
												<input type="text" name="bpname" value="<?php the_title();?>" id="bpName" disabled>
											<?php
											}else{
											?>
												<input type="text" value="<?php the_title();?>" disabled>
											<?php
											}
										}
										?>
									</div>
									<div class="address-line-1">
										<?php
										if( get_field('hide_display_company_address_1','option') ){
											?>
												<label>Address Line 1</label>
											<?php
											if( get_field('edit_display_name_company_address_line_1','option') ){
											?>
												<input type="text" name="bpaddressone" value="<?php echo get_field('company_address_line_1');?>" id="bpAddressone" maxlength="50">
											<?php
											}else{
											?>
												<input type="text" value="<?php echo get_field('company_address_line_1');?>" disabled>
											<?php
											}
										}
										?>
									</div>
									
									<div class="address-line-2">										
										<?php
										if( get_field('hide_display_company_address_2','option') ){
											?>
												<label>Address Line 2</label>
											<?php
											if( get_field('edit_display_name_company_address_line_2','option') ){
											?>
												<input type="text" name="bpaddresstwo" value="<?php echo get_field('company_address_line_2');?>" id="bpAddresstwo" maxlength="50">
											<?php
											}else{
											?>
											<input type="text"  value="<?php echo get_field('company_address_line_2');?>" disabled>
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
													if( get_field('hide_display_company_town','option') ){
														?>
															<label>Town</label>
														<?php
														if( get_field('edit_display_name_company_town','option') ){
														?>
															<input type="text" name="bptown" value="<?php echo get_field('company_town');?>" id="bpTown" maxlength="50">
														<?php
														}else{
														?>
														<input type="text"  value="<?php echo get_field('company_town');?>" disabled>
														<?php
														}
													}
													?>
												</div>	
											</li>	
											<li>	
												<div class="city">
													<?php
													if( get_field('hide_display_company_city','option') ){
														?>
															<label>City</label>
														<?php
														if( get_field('edit_display_name_company_city','option') ){
														?>
															<input type="text" name="bpcity" value="<?php echo get_field('company_city');?>" id="bpCity" maxlength="50">
														<?php
														}else{
														?>
														<input type="text"  value="<?php echo get_field('company_city');?>" disabled>
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
												<div class="country">
													<?php
													//if( get_field('hide_display_company_town','option') ){
														?>
															<label>County</label>
														<?php
														//if( get_field('edit_display_name_company_town','option') ){
														?>
															<input type="text" name="bpcountry" value="<?php echo get_field('country');?>" id="bpCountry" maxlength="50">
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
													if( get_field('hide_display_company_postcode','option') ){
														?>
															<label>Postcode</label>
														<?php
														if( get_field('edit_display_name_company_postcode','option') ){
														?>
															<input type="text" name="bppostcode" value="<?php echo get_field('company_postcode');?>" id="bpPostcode" maxlength="50">
														<?php
														}else{
														?>
														<input type="text"  value="<?php echo get_field('company_postcode');?>" disabled>
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
										if( get_field('hide_display_company_busines_email','option') ){
											?>
											<label>Business Email Address</label>
											<?php
											if( get_field('edit_display_name_company_business_email','option') ){
											?>
												<input type="text" name="bpbusinessEmail" value="<?php echo get_field('company_business_email');?>" id="bpBusinessEmail" maxlength="50" disabled>
											<?php
											}else{
											?>
											<input type="text"  value="<?php echo get_field('company_postcode');?>" disabled>
											<?php
											}
										}
										?>
									</div>
									<div class="company-name">
										<?php
										//if( get_field('hide_display_company_busines_email','option') ){
											?>
											<label>Business Name</label>
											<?php
											//if( get_field('edit_display_name_company_business_email','option') ){
											?>
												<input type="text" name="companyName" value="<?php echo get_field('company_name');?>" id="companyName" maxlength="50">
											<?php
											//}else{
											?>
											<!----<input type="text"  value="<?php //echo get_field('company_postcode');?>" disabled>--->
											<?php
											//}
										//}
										?>
									</div>
									<div class="company-number">
										<?php
										//if( get_field('hide_display_company_busines_email','option') ){
											?>
											<label>Company Number</label>
											<?php
											//if( get_field('edit_display_name_company_business_email','option') ){
											?>
												<input type="text" name="companyNumber" value="<?php echo get_field('company_number');?>" id="companyNumber" maxlength="50">
											<?php
											//}else{
											?>
											<!----<input type="text"  value="<?php //echo get_field('company_postcode');?>" disabled>--->
											<?php
											//}
										//}
										?>
									</div>
									<div class="company-website">
										<?php
										//if( get_field('hide_display_company_busines_email','option') ){
											?>
											<label>Website</label>
											<?php
											//if( get_field('edit_display_name_company_business_email','option') ){
											?>
												<input type="text" name="companyWebsite" value="<?php echo get_field('company_website');?>" id="companyWebsite">
											<?php
											//}else{
											?>
											<!----<input type="text"  value="<?php //echo get_field('company_postcode');?>" disabled>--->
											<?php
											//}
										//}
										?>
									</div>
									<div class="clear-both">
										<ul>
											<li>
											
												<div class="home-phone">
													<?php
													if( get_field('hide_display_company_office_phone','option') ){
														?>														
														<label>Office Phone</label>
														<?php
														if( get_field('edit_display_name_company_office_phone','option') ){
														?>
															<input type="text" name="bpofficePhone" value="<?php echo get_field('company_office_phone');?>" id="bpOfficePhone">
														<?php
														}else{
														?>
														<input type="text"  value="<?php echo get_field('company_office_phone');?>" disabled>
														<?php
														}
													}
													?>
												</div>
											</li>
											<li>								
												<div class="mobile-phones">
													<?php
													if( get_field('hide_display_company_mobile_phone','option') ){
														?>														
														<label>Mobile Phone</label>
														<?php
														if( get_field('edit_display_name_company_mobile_phone','option') ){
														?>
															<input type="text" name="bpmobilePhone" value="<?php echo get_field('company_mobile_phone');?>" id="bpMobilePhone" maxlength="50">
														<?php
														}else{
														?>
														<input type="text"  value="<?php echo get_field('company_mobile_phone');?>" disabled>
														<?php
														}
													}
													?>													
												</div>
											</li>
										</ul>
									</div>
									<div class="company-description">										
										<?php
										//if( get_field('hide_display_company_address_2','option') ){
											?>
												<label>Business Description</label>
											<?php
											
											//if( get_field('edit_display_name_company_address_line_2','option') ){
												$companyDescription = get_field('company_description');
											?>
											   <textarea name="company_field" maxlength="685"><?php if($companyDescription)echo trim($companyDescription);?></textarea>
											   <span class="keypress"></span>
											<?php
											//}else{
											?>
											<!--<input type="text"  value="<?php //echo get_field('company_address_line_2');?>" disabled>-->
											<?php
											//}
										//}
										?>
									</div>
								</div>
								<div class="business-profile-left">
									<div class="logo-wrapper">
										 <div class="file-upload btn btn-primary">	
											 <span>Update logo</span>
											 <input type="file" name="thumbnailImage" id="inputFile" accept="image/*">
										 </div>										 
										 <input type="hidden" name="post_id" id="post_id" value="<?php echo get_the_ID();?>" />
										<?php wp_nonce_field( 'thumbnailImage', 'thumbnailImage_nonce' ); ?>
										<?php
										global $featured_image,$status;
										$sql = $WP_CON->prepare('SELECT ui_URL AS url,ui_STATUS AS status FROM wp_user_imguploads WHERE uid_PartnerID = :parnerID');
										$sql->execute(array(':parnerID' => $businessID));
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
											<img id="imageUploadPreview" src="<?php if(!empty($featured)){echo $featured;}else{ echo get_stylesheet_directory_uri().'/images/default-logo.jpg';}?>" alt="" />
										</div> 
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
									<h2>Package Details</h2>
									<div class="clear-both">
										<ul>
											<li>
											
												<div class="partner-id">
													<?php
													if( get_field('hide_display_partner_id','option') ){
														?>														
														<label>Partner ID</label>
														<?php
														if( get_field('edit_display_name_partner_id','option') ){
														?>
															<input type="text" name="bpPartnerID" value="<?php echo get_field('partner_id');?>" id="bppartnerID" maxlength="50">
														<?php
														}else{
														?>
														<input type="text" name="bpPartnerID" value="<?php echo get_field('partner_id');?>" disabled>
														<?php
														}
													}
													?>
												</div>
											</li>
											<li>								
												<div class="account-manager">
													<?php
													if( get_field('hide_display_account_manager','option') ){
														?>														
														<label>Account Manager</label>
														<?php
														if( get_field('edit_display_name_account_manager','option') ){
														?>
															<input type="text" name="accountmanager" value="<?php echo get_field('account_manager');?>" id="accountManager" maxlength="50">
														<?php
														}else{
														?>
														<input type="text"  value="<?php echo get_field('account_manager');?>" disabled>
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
											
												<div class="package-level">
													<label>Package Level</label>
													<?php
													(isset($_POST["package"])) ? $company = $_POST["package"] : $company=get_field('package');
													 
													?>
													<input type="text" name="package" value="<?php echo $company;?>" disabled>
													<!--<select id="level" name="level" disabled>
														<option value="Level 1" <?php //selected( $company, 'Level 1' ); ?>>Level 1</option>
														<option value="Level 2" <?php //selected( $company, 'Level 2' ); ?>>Level 2</option>
														<option value="Level 3" <?php //selected( $company, 'Level 3' ); ?>>Level 3</option>
														<option value="Level 4" <?php //selected( $company, 'Level 4' ); ?>>Level 4</option>
														<option value="Level 5" <?php //selected( $company, 'Level 5' ); ?>>Level 5</option>
													</select>--->
												</div>
											</li>
											<li>								
												<div class="account-balance">
													<?php
													if( get_field('hide_display_account_account_balance','option') ){
														?>														
														<label>Account Balance</label>
														<?php
														if( get_field('edit_display_name_account_balance','option') ){
														?>
															<input type="text" name="bpaccountBalance" value="<?php echo $currentAmount; //get_field('account_balance');?>" id="bpaccountBalance" maxlength="50">
														<?php
														}else{
														?>
														<input type="text"  value="<?php echo $currentAmount;//get_field('account_balance');?>" disabled>
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
											
												<div class="free-phone-calls">
													<?php
													if( get_field('hide_display_free_phone_calls','option') ){
														?>														
														<label>Free Phone Calls</label>
														<?php
														if( get_field('edit_display_name_free_phone_calls','option') ){
														?>
															<input type="text" name="bpfreephoneCalls" value="<?php echo get_field('free_phone_calls');?>" id="bpfreephoneCalls" maxlength="50">
														<?php
														}else{
														?>
															<input type="text"  value="<?php echo get_field('free_phone_calls');?>" disabled>
														<?php
														}
													}
													?>
												</div>
											</li>
											<li>								
												<div class="free-live-chat">
													<?php
													if( get_field('hide_display_free_live_chat_msgs','option') ){
														?>														
														<label>Free Live Chat Msgs</label>
														<?php
														if( get_field('edit_display_name_free_live_chat','option') ){
														?>
															<input type="text" name="bpfreeliveChat" value="<?php echo get_field('free_live_chat_msgs');?>" id="bpfreeliveChat" maxlength="50">
														<?php
														}else{
														?>
															<input type="text"  value="<?php echo get_field('free_live_chat_msgs');?>" disabled>
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
												<div class="remaining-calls">
													<?php
													if( get_field('hide_display_remaining_calls_this_month','option') ){
														?>														
														<label>Remaining Calls This Month</label>
														<?php
														if( get_field('edit_display_name_remaining_call_this_month','option') ){
														?>
															<input type="text" name="bpremainingCall" value="<?php echo get_field('remaining_calls_this_month');?>" id="bpremainingCall" maxlength="50">
														<?php
														}else{
														?>
														<input type="text"  value="<?php echo get_field('remaining_calls_this_month');?>" disabled>
														<?php
														}
													}
													?>																			
												</div>
											</li>
											<li>								
												<div class="remaining-chat">
													<?php
													if( get_field('hide_display_remaining_live_chats_this_month','option') ){
														?>														
														<label>Remaining Live Chats This Month</label>
														<?php
														if( get_field('edit_display_name_remaining_live_chat_this_month','option') ){
														?>
															<input type="text" name="bpremainingChat" value="<?php echo get_field('remaining_live_chats_this_month');?>" id="bpremainingChat">
														<?php
														}else{
														?>
														<input type="text"  value="<?php echo get_field('remaining_live_chats_this_month');?>" disabled>
														<?php
														}
													}
													?>
												</div>
											</li>
										</ul>
									</div>
									<div class="rating">										
										<?php
										//if( get_field('hide_display_company_address_2','option') ){
											?>
												<label>Business Bullet Points</label>
											<?php
											//if( get_field('edit_display_name_company_address_line_2','option') ){
											?>
												<input type="text" name="firstRating" value="<?php echo get_field('first_rating');?>" id="firstRating" maxlength="22">
												<span class="keypress-rating1"></span>
												<input type="text" name="secondRating" value="<?php echo get_field('second_rating');?>" id="secondRating" maxlength="22">
												<span class="keypress-rating2"></span>
												<input type="text" name="thirdRating" value="<?php echo get_field('third_rating');?>" id="thirdRating" maxlength="22">
												<span class="keypress-rating3"></span>
												<input type="text" name="fourthRating" value="<?php echo get_field('fourth_rating');?>" id="fourthRating" maxlength="22">
												<span class="keypress-rating4"></span>
												<input type="text" name="fifthRatings" value="<?php echo get_field('fifth_rating');?>" id="fifthRatings" maxlength="22">
												<span class="keypress-rating5"></span>
											<?php
											
											//}else{
											?>
											<!--<input type="text"  value="<?php //echo get_field('company_address_line_2');?>" disabled>-->
											<?php
											//}
										//}
										?>
									</div>
								</div>
								<div class="submit-button">
									<input type="hidden" name="emailAddress" id="emailAddress" value="<?php echo get_field('email_address');?>" >
									<input type="hidden" name="fullName" id="fullName" value="<?php the_title();?>">
									<input type="hidden" name="action"  value="businessProfileProcess">
									<input type="submit" class="profile-button" id="business-profile-process" value="Update Business Profile >">
								</div>
								<div class="business-profile-process-message"></div>
							</div>
						</form>
						<script>
							jQuery(document).ready(function($){
								// jQuery Limiter
								$('textarea').keyup(function() {
									var textarea_val = $('textarea').val();
									var length = textarea_val.length;
									var maxLength=$('textarea').attr('maxLength');
									$(".keypress").text(length);
									if(length<=maxLength){
										$(".keypress").html(maxLength-length);
									}
								});
								$('textarea').focus(function() {
									$('.keypress').addClass("limiter");
								});
								$('textarea').focusout(function() {
									$('.keypress').removeClass("limiter");
								});
								
								$('#firstRating').keyup(function() {
									var firstRating_val = $('#firstRating').val();
									var length1 = firstRating_val.length;
									var maxLength1=$('#firstRating').attr('maxLength');
									$(".keypress-rating1").text(length1);
									if(length1<=maxLength1){
										$(".keypress-rating1").html(maxLength1-length1);
									}
								});
								$('#firstRating').focus(function() {
									$('.keypress-rating1').addClass("limiter");
								});
								$('#firstRating').focusout(function() {
									$('.keypress-rating1').removeClass("limiter");
								});
								
								$('#secondRating').keyup(function() {
									var secondRating_val = $('#secondRating').val();
									var length = secondRating_val.length;
									var maxLength=$('#secondRating').attr('maxLength');
									$(".keypress-rating2").text(length);
									if(length<=maxLength){
										$(".keypress-rating2").html(maxLength-length);
									}
								});
								$('#secondRating').focus(function() {
									$('.keypress-rating2').addClass("limiter");
								});
								$('#secondRating').focusout(function() {
									$('.keypress-rating2').removeClass("limiter");
								});
								$('#thirdRating').keyup(function() {
									var secondRating_val = $('#thirdRating').val();
									var length = secondRating_val.length;
									var maxLength=$('#thirdRating').attr('maxLength');
									$(".keypress-rating3").text(length);
									if(length<=maxLength){
										$(".keypress-rating3").html(maxLength-length);
									}
								});
								$('#thirdRating').focus(function() {
									$('.keypress-rating3').addClass("limiter");
								});
								$('#thirdRating').focusout(function() {
									$('.keypress-rating3').removeClass("limiter");
								});
								$('#fourthRating').keyup(function() {
									var secondRating_val = $('#thirdRating').val();
									var length = secondRating_val.length;
									var maxLength=$('#thirdRating').attr('maxLength');
									$(".keypress-rating4").text(length);
									if(length<=maxLength){
										$(".keypress-rating4").html(maxLength-length);
									}
								});
								$('#fourthRating').focus(function() {
									$('.keypress-rating4').addClass("limiter");
								});
								$('#fourthRating').focusout(function() {
									$('.keypress-rating4').removeClass("limiter");
								});
								$('#fifthRatings').keyup(function() {
									var secondRating_val = $('#fifthRatings').val();
									var length = secondRating_val.length;
									var maxLength=$('#fifthRatings').attr('maxLength');
									$(".keypress-rating5").text(length);
									if(length<=maxLength){
										$(".keypress-rating5").html(maxLength-length);
									}
								});
								$('#fifthRatings').focus(function() {
									$('.keypress-rating5').addClass("limiter");
								});
								$('#fifthRatings').focusout(function() {
									$('.keypress-rating5').removeClass("limiter");
								});
								//jQuery business form
								$("#businessForm").on('submit',(function(e) {
									e.preventDefault();
									var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
									//var formData = new FormData(this);		
									//alert(formData);
									$.ajax({
										url: ajaxurl, // Url to which the request is send
										type: "POST",             // Type of request to be send, called as method
										data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
										contentType: false,       // The content type used when sending data to the server.
										cache: false,             // To unable request pages to be cached
										processData:false,        // To send DOMDocument or non processed data file it is set to false
										success: function(data)   // A function to be called if request succeeds
										{
											//$('#loading').hide();
											$(".business-profile-process-message").html(data);
											window.top.location.reload();
										}
									});
								}));
							 });	
						</script>	
						<?php
					}
				}else{
					echo wpautop( 'Sorry, no posts were found' );
				}
			?>
		</div>
	</div>
</div>
<?php get_footer(); ?>