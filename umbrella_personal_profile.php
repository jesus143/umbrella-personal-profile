<?php
    /**
    * Plugin Name: Umbrella Personal Profile
    * Plugin URI:
    * Description: This will be used in partner personal profile, authenticate with facebook
    * Version: 1.0
    * Author: Jesus Erwin Suarez
    * Author URI:
    * License:
    * Text Domain:
    */
    
    if ( !defined('ABSPATH') ) { 
        define('ABSPATH', dirname(__FILE__) . '/');
    }
 
    define('upp_plugin_path', plugin_dir_path( __FILE__ ));

    define('upp_plugin_url', get_site_url() . '/wp-content/plugins/umbrella-personal-profile');

    if(!function_exists('wp_get_current_user')) {include(ABSPATH . "wp-includes/pluggable.php");}

    add_action('admin_menu', 'umbrella_personal_profile_setup_menu');

    add_shortcode( 'umbrella_person_profile_page', 'umbrella_person_profile_page_func' );
 
    require_once ('includes/UPPUmbrellaPersonalProfile.php');
    require_once ('includes/upp_helper.php');

 
    function umbrella_person_profile_page_func( $atts ) {

        // include ( ABSPATH . 'wp-includes/link-template.php');
        $uPPUmbrellaPersonalProfile = new App\UPPUmbrellaPersonalProfile();
        ob_start();
        ?>
 
        <style>
            .entry-title {

                display:none;

            }
            #page-content h2:nth-child(1) {
                display:none;
            }
        </style>

        <link rel="stylesheet" type="text/css" href="https://lipis.github.io/bootstrap-social/bootstrap-social.css" />
        <?php
  
        // check wordpress meta if exist; 
        $facebook_meta = true; 

        print '<div style="position:absolute; top:-20000px;">';
            $uPPUmbrellaPersonalProfile->htmlPrintFacebookInfoIncludingPicture();
        print '</div>';
        ob_end_flush();
    } 
    function umbrella_personal_profile_setup_menu(){
        // print "umbrella personal profile";
    }