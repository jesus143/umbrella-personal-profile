<?php
get_header();
?>
<div id="home-content">	
<?php if(!is_user_logged_in()){ ?>
<div class="modal" id="modal-one" aria-hidden="true">
	 <div class="modal-dialog">
		<div class="modal-header">
		  <h2><img width="50%" height="auto" style="margin:0 auto; text-align:center; display:block" alt="umbrella support centre" src="<?php echo get_template_directory_uri().'/images/Umbrella-logo.png';?>"></h2>
		  <a href="#" class="btn-close" aria-hidden="true">×</a> 
		</div>
		<div class="modal-body">
			<div class="modal-body-left">
				<ul class="tab">
					<li><a onclick="openTabnav(event, 'Login')" class="tablinks active" href="#/">Login</a></li>
					<li><a onclick="openTabnav(event, 'SignUp')" class="tablinks" href="#/">Signup</a></li>
				</ul>
				<div style="display:block;" class="tabcontent"  id="Login">
					<div class="log"></div>
						<form action="" method="post">
						  <div class="e3ve-modal-container">
							<label><b>Email Address</b></label>
							<input type="text" placeholder="Enter Email Address" name="email" id="email" required="">
							<label><b>Password</b></label>
							<input type="password" placeholder="Enter Password" name="psw" id="password" required="">
							<button type="submit" value="register" id="submit">Login to Your Support Centre &gt;&gt;</button>
							<!--<input type="checkbox" checked="checked" class="e3ve-remember">
							Remember me --></div>
						</form>
						<script type="text/javascript">
						jQuery(document).ready(function($){
							$("#submit").click(function(){
								var email = $("#email").val();
								var password = $("#password").val();
								var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
								// Returns successful data submission message when the entered information is stored in database.
								if(email==''||password==''){
									$( ".log" ).append("<p class="+'error-message'+"><b>✘</b>Email and password should not be empty!</p>");
								}else{
									// AJAX Code To Submit Form.
									$.ajax({
										type: "POST",
										url: ajaxurl,
										timeout: 3000,
										data:{
										   action: "registrationRequest",
										   email:email,
										   password:password
										},
										cache: false,
										success: function(result){
											$(".log").html(result);
											$( "div.modal-dialog" ).addClass( "modal-login" );
											window.top.location.reload();
										}
									});
								}
								return false;
							});
						 });	
						</script>
					<div class="e3ve-modal-divider">OR</div>
					<div class="modal-body-right">
						<h3>Sign in with</h3>
						 <?php
							$permissions = ['email','public_profile','user_birthday']; // Optional permissions
							$loginUrl = $helper->getLoginUrl('http://testing.umbrellasupport.co.uk', $permissions);
							echo '<a href="' . htmlspecialchars($loginUrl) . '"><img src="'.get_stylesheet_directory_uri().'/images/facebook.png"></a>';

							if(isset($successLogin) && $successLogin == true){
								echo '<div class="facebook-message-success">';
								echo '<div style="position:relative;">';
								echo '<div style="text-align:left" class="facebook-message-right">';
								echo '<img src="'.get_template_directory_uri().'/images/Loading-Circle-Large-Red.gif">';	
								echo '</div>';
								echo '<div class="facebook-message-left">';
								echo '<h2><img alt="umbrella support centre" src="'.get_template_directory_uri().'/images/Umbrella-logo.png"></h2>';
								echo '<h3 >You have Successfully logged into Your Account.</h3>';
								echo '<h3 >Please wait for a few seconds...</h3>';
								//echo '<p>!</p>';
								echo '</div>';
								echo '</div>';
								echo '</div>';
						?>
								<script type="text/javascript">
									jQuery(document).ready(function($){
										$( "#home-content div.modal-dialog" ).addClass( "modal-login" );
									});
								</script>
						<?php
							}
						?>
					</div>
				</div> 
				<div style="" class="tabcontent" id="SignUp">
					<div class="e3ve-modal-container">
						<script type="text/javascript" src="//forms.ontraport.com/v2.4/include/formEditor/genbootstrap.php?method=script&uid=p2c7818f328&version=1"></script> 
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			$('#preloader').show();
			$.ajax({
			   type: "POST", // HTTP method POST or GET
			   url: "<?php echo admin_url('admin-ajax.php'); ?>", //Where to make Ajax calls
			   //dataType:"text", // Data type, HTML, json etc.
			   data:{action:'getleads'},
			   success:function(response){
				 //responseaction
				 //alert(response);
				 //process_tickers(response);
				jQuery('.vticker ul').html(response);
				//process_tickers(response);
				//load_ticker();
			   },
			   error:function (xhr, ajaxOptions, thrownError){
				alert("Error: " + thrownError);
			   },
			   complete: function(){
				$('#preloader').hide();
			   }
			});
		});
		
		jQuery( document ).ready( function() {
			setTimeout(function(){
			$('.modal').delay(500).addClass('loaded');
			}, 3000);
			$('.btn-close,  .btn').click( function() {
				$('.modal').removeClass('loaded');
			});
		});

		jQuery( document ).ready(function() {
			$('.modal-dialog').css({
				'position' : 'absolute',
				'left' : '50%',
				'top' : '50%',
				'margin-left' : -$('.modal-dialog').outerWidth()/2,
				'margin-top' : -$('.modal-dialog').outerHeight()/2
			});
		});

		function openTabnav(evt, tabnavName) {
			var i, tabcontent, tablinks;
			tabcontent = document.getElementsByClassName("tabcontent");
			for (i = 0; i < tabcontent.length; i++) {
				tabcontent[i].style.display = "none";
			}
			tablinks = document.getElementsByClassName("tablinks");
			for (i = 0; i < tablinks.length; i++) {
				tablinks[i].className = tablinks[i].className.replace(" active", "");
			}
			document.getElementById(tabnavName).style.display = "block";
			evt.currentTarget.className += " active";
		}

		$(document).ready(function() {
			$("#e3ve-lock-page-popup").click(function(){
				$(".e3ve-hidden-page-login").show();
			});
			$(".btn-close, .btn").click( function() {
				$(".e3ve-hidden-page-login").hide();
			});
		});
	</script>
<?php } ?>
</div><br/>
<div class="home-intro">
  <h1 style="line-height: 80px;"><?php echo get_option('of_welcometitle') ?></h1>
  <p style="margin-top: -25px;"><?php echo get_option('of_welcomecontent') ?></p>
</div>
<div id="main-buttons">
  <div class="main-button-wrapper">
    <div class="main-button-box1">
      <div class="main-button-box-inner"><img width="220" height="220" src="<?php echo get_option('of_serviceimage1') ?>" alt="<?php echo get_option('of_servicetitle1') ?> Image">
      	<a href="<?php echo get_option('of_service1link') ?>">
        <div class="main-button-box-content" style="padding: 1px 0 19px !important;">
          <h2><?php echo get_option('of_servicetitle1') ?></h2>
          <div class="boxbodycontent">
            <p><?php echo get_option('of_service1desc') ?></p>
          </div>
        </div>
        </a>
      </div>
    </div>
    <div class="main-button-box2">
      <div class="main-button-box-inner"><img width="220" height="220" src="<?php echo get_option('of_serviceimage2') ?>" alt="<?php echo get_option('of_servicetitle2') ?> Image">
     	<a href="<?php echo get_option('of_service2link') ?>">
        <div class="main-button-box-content" style="padding: 1px 0 19px !important;">
          <h2><?php echo get_option('of_servicetitle2') ?></h2>
          <div class="boxbodycontent">
            <p><?php echo get_option('of_service2desc') ?></p>
          </div>
        </div>
        </a>
      </div>
    </div>
    <div class="main-button-box3">
      <div class="main-button-box-inner"><img width="220" height="220" src="<?php echo get_option('of_serviceimage3') ?>" alt="<?php echo get_option('of_servicetitle3') ?> Image">
      	<a href="<?php echo get_option('of_service3link') ?>">
        <div class="main-button-box-content" style="padding: 1px 0 19px !important;">
          <h2><?php echo get_option('of_servicetitle3') ?></h2>
          <div class="boxbodycontent">
            <p><?php echo get_option('of_service3desc') ?></p>
          </div>
        </div>
        </a>
      </div>
    </div>
    <div class="main-button-box4">
      <div class="main-button-box-inner"><img width="220" height="220" src="<?php echo get_option('of_serviceimage4') ?>" alt="<?php echo get_option('of_servicetitle4') ?> Image">
      	<a href="<?php echo get_option('of_service4link') ?>">
        <div class="main-button-box-content" style="padding: 1px 0 19px !important;">
          <h2><?php echo get_option('of_servicetitle4') ?></h2>
          <div class="boxbodycontent">
            <p><?php echo get_option('of_service4desc') ?></p>
          </div>
        </div>
        </a>
      </div>
    </div>
    <div class="main-button-box5">
      <div class="main-button-box-inner"><img width="220" height="220" src="<?php echo get_option('of_serviceimage5') ?>" alt="<?php echo get_option('of_servicetitle5') ?> Image">
      	<a href="<?php echo get_option('of_service5link') ?>">
        <div class="main-button-box-content" style="padding: 1px 0 19px !important;">
          <h2><?php echo get_option('of_servicetitle5') ?></h2>
          <div class="boxbodycontent">
            <p><?php echo get_option('of_service5desc') ?></p>
          </div>
        </div>
        </a>
      </div>
    </div>
    <div class="main-button-box6">
      <div class="main-button-box-inner"><img width="220" height="220" src="<?php echo get_option('of_serviceimage6') ?>" alt="<?php echo get_option('of_servicetitle6') ?> Image">
      	<a href="<?php echo get_option('of_service6link') ?>">
        <div class="main-button-box-content" style="padding: 1px 0 19px !important;">
          <h2><?php echo get_option('of_servicetitle6') ?></h2>
          <div class="boxbodycontent">
            <p><?php echo get_option('of_service6desc') ?></p>
          </div>
        </div>
        </a>
      </div>
    </div>
    <div class="main-button-box7">
      <div class="main-button-box-inner"><img width="220" height="220" src="<?php echo get_option('of_serviceimage7') ?>" alt="<?php echo get_option('of_servicetitle7') ?> Image">
      	<a href="<?php echo get_option('of_service7link') ?>">
        <div class="main-button-box-content" style="padding: 1px 0 19px !important;">
          <h2><?php echo get_option('of_servicetitle7') ?></h2>
          <div class="boxbodycontent">
            <p><?php echo get_option('of_service7desc') ?></p>
          </div>
        </div>
        </a>
      </div>
    </div>
    <div class="main-button-box8">
      <div class="main-button-box-inner"><img width="220" height="220" src="<?php echo get_option('of_serviceimage8') ?>" alt="<?php echo get_option('of_servicetitle8') ?> Image">
      	<a href="<?php echo get_option('of_service8link') ?>">
        <div class="main-button-box-content" style="padding: 1px 0 19px !important;">
          <h2><?php echo get_option('of_servicetitle8') ?></h2>
          <div class="boxbodycontent">
            <p><?php echo get_option('of_service8desc') ?></p>
          </div>
        </div>
        </a>
      </div>
    </div>
  </div>
</div>
<div id="home-ranking">
  <div class="home-ranking-box">
    <?php
		if(is_user_logged_in()){
			$current_user = wp_get_current_user();
			//$customAPIKEY  = get_field('custom_api_key','option');// name of the admin
			//$customAPIID  = get_field('custom_api_id','option');// Email Title for the admin
			//echo "Email Address: " . $current_user->user_email; 
			//$postargs = "http://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'testing@umbrellasupport.co.uk'&searchNotes=true";
			$postargs = "http://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'".$current_user->user_email."'&searchNotes=true";
		
			$session = curl_init();
			curl_setopt ($session, CURLOPT_RETURNTRANSFER, true);
			curl_setopt ($session, CURLOPT_URL, $postargs);
			//curl_setopt ($session, CURLOPT_HEADER, true);
			curl_setopt ($session, CURLOPT_HTTPHEADER, array(
			  'Api-Appid:2_7818_AFzuWztKz',
			  'Api-Key:fY4Zva90HP8XFx3'
			));
			$response = curl_exec($session); 
			curl_close($session);
			//header("Content-Type: text");
			//echo "CODE: " . $response;
			$getName = json_decode($response);  

			//echo '<br /><br />Name: '. $getName->data[0]->f1549;
			$user_output =  get_user_meta($current_user->ID, 'level',true);
			switch($getName->data[0]->f1549){
			 case 1: { $output_level=esc_url(get_stylesheet_directory_uri().'/images/level-1.png'); break; }
			 case 2: { $output_level=esc_url(get_stylesheet_directory_uri().'/images/level-2.png'); break;}
			 case 3: { $output_level=esc_url(get_stylesheet_directory_uri().'/images/level-3.png'); break;}
			 case 4: { $output_level=esc_url(get_stylesheet_directory_uri().'/images/level-4.png'); break;}
			 case 5: { $output_level=esc_url(get_stylesheet_directory_uri().'/images/level-5.png'); break;}
			 default: {$output_level=esc_url(get_stylesheet_directory_uri().'/images/level-0.png'); break;}
			}
		}
		
		?>
    <div class="rank-heading" style="padding: 1px 0px 15px !important;">
      <h3 style="line-height: 0;"><?php echo get_option('of_rankheading') ?></h3>
    </div>
	<div class="rank-thermometer" style="background: transparent url('<?php echo $output_level;?>') no-repeat scroll 0 0 / contain;">
	</div>
    <div class="rank-footer">
      <p><?php echo get_option('of_rankfooter') ?></p>
    </div>
  </div>
</div>
<div id="social-section">
  <div id="home-social-1">
    <div class="latest-news">
      <div class="news-header">
        <h4><?php echo get_option('of_threecol3') ?></h4>
      </div>
      <div class="news-body">
        <div class="news-warpper">
          <div class="slider">
            <div class="flexslider">
              <ul class="slides">
                <?php $catquery = new WP_Query( array( 'category_name' => 'News', 'posts_per_page' => 5, 'offset' => 0 ) ); ?>
                <?php while($catquery->have_posts()) : $catquery->the_post(); ?>
                <li>
                  <h3><a href="<?php the_permalink() ?>" rel="bookmark">
                    <?php the_title(); ?>
                    </a></h3>
                  <div class="news-image-thumb"> <a href="<?php the_permalink(); ?>">
                    <?php if(has_post_thumbnail()) { $image_src = wp_get_attachment_image_src( get_post_thumbnail_id(),'full' );
         echo '<img  class="alignleft" src="' . $image_src[0]  . '" width="25%" height="auto"  />'; } ?>
                    </a> </div>
                  <div class="home-excerpt"> <?php echo substr(get_the_excerpt(), 0,140); ?>... <a href="<?php the_permalink(); ?>">read more</a></div>
                </li>
                <?php endwhile; ?>
              </ul>
            </div>
            <div class="custom-navigation"> <a href="#" class="flex-prev"><span><</span> Previous</a>
              <div class="custom-controls-container"></div>
              <a href="#" class="flex-next">Next <span>></span></a> </div>
          </div>
        </div>
      </div>
    </div>
    <div class="left-shadow"></div>
  </div>
  <div id="home-social-2">
    <div class="latest-leads">
      <div class="leads-header">
        <h4><?php echo get_option('of_threecol2') ?></h4>
      </div>
      <div class="leads-body">
		<center><div id="preloader" style="display:none;"><img src="http://testing.umbrellasupport.co.uk/wp-content/uploads/2016/07/preload.gif" /></div></center>
		<div class="vticker">
			
			<ul></ul>
		</div>
	  </div>
    </div>
    <div class="center-shadow"></div>
  </div>
  <div id="home-social-3">
    <div class="facebook-community">
      <div class="charity-header">
        <h4><?php echo get_option('of_threecol1') ?></h4>
      </div>
      <div class="charity-body">
        <?php $charity = get_option('of_charity') ?>
		  <?php query_posts('category_name='.$charity.'&posts_per_page=1'); ?>
          <?php while (have_posts()) : the_post(); ?>
        <div class="e3ve-charity-article">
          <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
          <?php if(has_post_thumbnail()) {                    
			$image_src = wp_get_attachment_image_src( get_post_thumbnail_id(),'full' );
			 echo '<img src="'.$image_src[0].'" width="70" height="70" class="alignleft"  />';
		  } ?>
          <p><?php echo substr(get_the_excerpt(), 0,100); ?>... <a href="<?php the_permalink(); ?>">read more</a></p>
        </div>
        <?php
			$category_id = get_cat_ID( 'Charity Contributions' );
			$category_link = get_category_link( 7 );
		?>
		<div class="e3ve-charity-btn"> <a href="<?php echo esc_url( $category_link ); ?>"><span class="e3ve-home-btn-arrow-left"></span>See Who Else I’ve Helped ><span class="e3ve-home-btn-arrow-right"></span></a> </div>
        <?php endwhile;?>
      </div>
    </div>
    <div class="right-shadow"></div>
  </div>
</div>
<?php
 get_footer(); 
?>