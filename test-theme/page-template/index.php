<?php
get_header();
?>


<style type="text/css">

  .main-button-box-content .boxbodycontent p {
    padding: 10px;
  }

  .checkbox input[type="checkbox"], .checkbox-inline input[type="checkbox"], .radio input[type="radio"], .radio-inline input[type="radio"] {
    bottom: 25px !important;
  }

</style>


<div class="home-intro">
	<hr></hr>
  <h1 style="line-height: 70px;"><?php echo get_option('of_welcometitle') ?></h1>
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
			$postargs = "https://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'".$current_user->user_email."'&searchNotes=true";

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
	<div class="rank-thermometer" style="background: transparent url('<?php if(!empty($output_level)){echo $output_level;}else{ echo esc_url(get_stylesheet_directory_uri().'/images/level-0.png'); }?>') no-repeat scroll 0 0 / contain;">
	</div>
    <div class="rank-footer">
      <a href="#"><p style="font-size: 14px; vertical-align: center; text-align: center;"><?php echo get_option('of_rankfooter') ?></p></a>
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
        <div class="news-wrapper">
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
		<center><div id="preloader" style="display:none;"><img src="https://testing.umbrellasupport.co.uk/wp-content/uploads/2016/07/preload.gif" /></div></center>
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