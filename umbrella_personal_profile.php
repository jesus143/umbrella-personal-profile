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
 
    require_once ('includes/UPPUmbrellaPersonalProfile.php');
    require_once ('includes/upp_helper.php');


    function umbrella_person_profile_page_func( $atts ) {
        $uPPUmbrellaPersonalProfile = new App\UPPUmbrellaPersonalProfile();
        ob_start();
        ?>


        <!-- Latest compiled and minified CSS -->
<!--        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
        <style>
            .entry-title {

                display:none;

            }
            #page-content h2:nth-child(1) {
                display:none;
            }
        </style>
        <?php
 
//        require_once('public/resources/FB Login/index.php');
    
 
        // $_SESSION['personal_profile']['fb_id']  = $user['id'];
        // $_SESSION['personal_profile']['fb_ea']  = $user['email'];
        // $_SESSION['personal_profile']['fb_fn']  = $user['name']; 
        // $_SESSION['personal_profile']['fb_profile_pic'] = "http://graph.facebook.com/" . $_SESSION['fb_id'] . "/picture";    
  
        // check wordpress meta if exist; 
        $facebook_meta = true;
//
//        if($uPPUmbrellaPersonalProfile->isAuthenticatedWithFaceBook() == true) { // or condition if facebook information is already in the meta
//
//            print "<br> authenticated with facebook ";
//        } else {
//            print "<br>not authenticated with facebook";
//        }

//        $uPPUmbrellaPersonalProfile->updateTagInfoToOntraPort();
//        $opResponse = $uPPUmbrellaPersonalProfile->getTagInfoOntraPort();

        //        print "current user id " . $uPPUmbrellaPersonalProfile->getCurrentUserId();
        //
        //        $opResponse = $uPPUmbrellaPersonalProfile->queryTagInfoOntraPort([
        //            'facebook_email' => 'nicetestFb@gmail.com',
        //            'method'=>'PUT'
        //        ]);
        //
        //        $opResponse = $uPPUmbrellaPersonalProfile->queryTagInfoOntraPort([
        //            'facebook_email' => '',
        //            'method'=>'GET'
        //        ]);
        //
//        $opResponse = json_decode($opResponse, true );


//        $uPPUmbrellaPersonalProfile->getBusinessProfilePicPath();
//        print "<pre>";
//            print_r($opResponse);
//        print "</pre>";



//       print " business profile pic url " . getBusinessProfilePic();


        print '<div style="position:absolute; top:-20000px;">';
            $uPPUmbrellaPersonalProfile->htmlPrintFacebookInfoIncludingPicture();
        print '</div>';
        ob_end_flush();
    } 
    function umbrella_personal_profile_setup_menu(){
//        print "umbrella personal profile";
    }
