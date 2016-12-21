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
    
    define('upp_plugin_path', plugin_dir_path( __FILE__ ));

    define('upp_plugin_url', get_site_url() . '/wp-content/plugins/umbrella-personal-profile');

    if(!function_exists('wp_get_current_user')) {include(ABSPATH . "wp-includes/pluggable.php");}

    add_action('admin_menu', 'umbrella_personal_profile_setup_menu');

    add_shortcode( 'umbrella_person_profile_page', 'umbrella_person_profile_page_func' );

    function umbrella_person_profile_page_func( $atts ) {
        ob_start();
        ?>

        <style>
            .entry-title {

                display:none;

            }
        </style>


        <h3> Welcome to your personal profile, please authenticate to your facebook information!</h3>






        <?php
        ob_end_flush();
    }


    function umbrella_personal_profile_setup_menu(){
        print "umbrella personal profile";
    }
