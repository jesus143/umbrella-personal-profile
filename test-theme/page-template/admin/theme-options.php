<?php
add_action('init','of_options');
if (!function_exists('of_options')) {
function of_options(){

// VARIABLES
$themename = get_theme_data(STYLESHEETPATH . '/style.css');
$themename = $themename['Name'];
$shortname = "of";

// Populate OptionsFramework option in array for use in theme
global $of_options;
$of_options = get_option('of_options');
$GLOBALS['template_path'] = OF_DIRECTORY;

//Access the WordPress Categories via an Array
$of_categories = array();  
$of_categories_obj = get_categories('hide_empty=0');
foreach ($of_categories_obj as $of_cat) {
    $of_categories[$of_cat->cat_ID] = $of_cat->cat_name;}
$categories_tmp = array_unshift($of_categories, "Select a category:");    

//Access the WordPress Pages via an Array
$of_pages = array();
$of_pages_obj = get_pages('sort_column=post_parent,menu_order');    
foreach ($of_pages_obj as $of_page) {
    $of_pages[$of_page->ID] = $of_page->post_name; }
$of_pages_tmp = array_unshift($of_pages, "Select a page:");       

// Image Links to Options
$options_image_link_to = array("image" => "The Image","post" => "The Post"); 

//Testing 
$options_select = array("one","two","three","four","five"); 
$options_radio = array("one" => "One","two" => "Two","three" => "Three","four" => "Four","five" => "Five"); 
$align_option = array("left" => "Left", "center" => "Center", "right" => "Right"); 
$contact_option = array("no" => "Show no cotact in sidebar", "one" => "Show one contact in sidebar", "two" => "Show two contacts in sidebar"); 

//Stylesheets Reader
$alt_stylesheet_path = OF_FILEPATH . '/styles/';
$alt_stylesheets = array();
if ( is_dir($alt_stylesheet_path) ) {
    if ($alt_stylesheet_dir = opendir($alt_stylesheet_path) ) { 
        while ( ($alt_stylesheet_file = readdir($alt_stylesheet_dir)) !== false ) {
            if(stristr($alt_stylesheet_file, ".css") !== false) {
                $alt_stylesheets[] = $alt_stylesheet_file;
            }
        }    
    }
}

//More Options
$uploads_arr = wp_upload_dir();
$all_uploads_path = $uploads_arr['path'];
$all_uploads = get_option('of_uploads');
$other_entries = array("Select a number:","1","2","3","4","5","6","7","8","9","10","11","12","13","14","15","16","17","18","19");
$body_repeat = array("no-repeat","repeat-x","repeat-y","repeat");
$body_pos = array("top left","top center","top right","center left","center center","center right","bottom left","bottom center","bottom right");

// Set the Options Array
$options = array();

/********** 
Begin Adding options here ( IMPORTANT: Add your 1st heading before you add any options )***/

//General Heading
$options[] = array( "name" => "General Options",
					"type" => "heading");

//Favicon
$options[] = array( "name" => "Upload Custom Favicon",
					"desc" => "Upload a 16px x 16px Png/Gif image that will represent your website's favicon.",
					"id" => $shortname."_custom_favicon",
					"std" => "",
					"type" => "upload"); 
					$url =  OF_DIRECTORY . '/admin/images/'; 
					
//Google Analytics
$options[] = array( "name" => "Google Analytics Tracking Code",
					"desc" => "Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme.",
					"id" => $shortname."_google_analytics",
					"std" => "",
					"type" => "textarea"); 
					
					
//Header Options
$options[] = array( "name" => "Header Options",
					"type" => "heading"); 
					
//Top Menu
$options[] = array( "name" => "Top Menu",
					"desc" => "Enter html into the field",
					"id" => $shortname."_topmenu",
					"std" => "Top menu html here..",
					"type" => "textarea");   
					
//Logo Uploader
$options[] = array( "name" => "Upload Logo",
					"desc" => "Upload an image file, or specify the address of your online file. (http://yoursite.com/logo.png)",
					"id" => $shortname."_logo",
					"std" => get_template_directory_uri() . "/images/logo.png",
					"type" => "upload");
					$url =  OF_DIRECTORY . '/admin/images/';
					
//Business Connect Uploader
$options[] = array( "name" => "Business Connect Upload",
					"desc" => "Upload an image file, or specify the address of your online file. (http://yoursite.com/logo.png)",
					"id" => $shortname."_businessconnect",
					"std" => get_template_directory_uri() . "/images/business-connect-new.jpg",
					"type" => "upload");
					$url =  OF_DIRECTORY . '/admin/images/';
					
//Welcome Options
$options[] = array( "name" => "Welcome Options",
					"type" => "heading");
					
//Welcome Title
$options[] = array( "name" => "Welcome Title",
					"desc" => "Enter text into the field",
					"id" => $shortname."_welcometitle",
					"std" => "Welcome to Umbrella Portal...",
					"type" => "text");
					
//Welcome Content
$options[] = array( "name" => "Welcome Content",
					"desc" => "Enter text into the field",
					"id" => $shortname."_welcomecontent",
					"std" => "This is an introduction about umbrella suport portal..",
					"type" => "textarea");       
					
//Services Boxes Options
$options[] = array( "name" => "Services Boxes Options",
					"type" => "heading");
					
//Service Link 1
$options[] = array( "name" => "Service Link 1",
					"desc" => "Enter link into the field",
					"id" => $shortname."_service1link",
					"std" => "#",
					"type" => "text");   
					
//Service 1 Title
$options[] = array( "name" => "Service 1 Title",
					"desc" => "Enter title into the field",
					"id" => $shortname."_servicetitle1",
					"std" => "Post Checker",
					"type" => "text");
					
//Service 1 Image
$options[] = array( "name" => "Service 1 Image",
					"desc" => "Upload a 220x220 pixel image, or specify the address of your online file. (http://yoursite.com/image.png)",
					"id" => $shortname."_serviceimage1",
					"std" => get_template_directory_uri() . '/images/image-placeholder.png',
					"type" => "upload");
					$url =  OF_DIRECTORY . '/admin/images/';
					
//Service 1 Description
$options[] = array( "name" => "Service 1 Description",
					"desc" => "Enter description into the field",
					"id" => $shortname."_service1desc",
					"std" => "This is the section for more details about the service...",
					"type" => "textarea");
					
//Service Link 2
$options[] = array( "name" => "Service Link 2",
					"desc" => "Enter link into the field",
					"id" => $shortname."_service2link",
					"std" => "#",
					"type" => "text");
					
//Service 2 Title
$options[] = array( "name" => "Service 2 Title",
					"desc" => "Enter title into the field",
					"id" => $shortname."_servicetitle2",
					"std" => "Post Checker",
					"type" => "text");
					
//Service 2 Image
$options[] = array( "name" => "Service 2 Image",
					"desc" => "Upload a 220x220 pixel image, or specify the address of your online file. (http://yoursite.com/image.png)",
					"id" => $shortname."_serviceimage2",
					"std" => get_template_directory_uri() . '/images/image-placeholder.png',
					"type" => "upload");
					$url =  OF_DIRECTORY . '/admin/images/';
					
//Service 2 Description
$options[] = array( "name" => "Service 2 Description",
					"desc" => "Enter description into the field",
					"id" => $shortname."_service2desc",
					"std" => "This is the section for more details about the service...",
					"type" => "textarea");
					
//Service Link 3
$options[] = array( "name" => "Service Link 3",
					"desc" => "Enter link into the field",
					"id" => $shortname."_service3link",
					"std" => "#",
					"type" => "text");
					
//Service 3 Title
$options[] = array( "name" => "Service 3 Title",
					"desc" => "Enter title into the field",
					"id" => $shortname."_servicetitle3",
					"std" => "Post Checker",
					"type" => "text");
					
//Service 3 Image
$options[] = array( "name" => "Service 3 Image",
					"desc" => "Upload a 220x220 pixel image, or specify the address of your online file. (http://yoursite.com/image.png)",
					"id" => $shortname."_serviceimage3",
					"std" => get_template_directory_uri() . '/images/image-placeholder.png',
					"type" => "upload");
					$url =  OF_DIRECTORY . '/admin/images/';
					
//Service 3 Description
$options[] = array( "name" => "Service 3 Description",
					"desc" => "Enter description into the field",
					"id" => $shortname."_service3desc",
					"std" => "This is the section for more details about the service...",
					"type" => "textarea");
					
//Service Link 4
$options[] = array( "name" => "Service Link 4",
					"desc" => "Enter link into the field",
					"id" => $shortname."_service4link",
					"std" => "#",
					"type" => "text"); 
					
//Service 4 Title
$options[] = array( "name" => "Service 4 Title",
					"desc" => "Enter title into the field",
					"id" => $shortname."_servicetitle4",
					"std" => "Post Checker",
					"type" => "text");
					
//Service 4 Image
$options[] = array( "name" => "Service 4 Image",
					"desc" => "Upload a 220x220 pixel image, or specify the address of your online file. (http://yoursite.com/image.png)",
					"id" => $shortname."_serviceimage4",
					"std" => get_template_directory_uri() . '/images/image-placeholder.png',
					"type" => "upload");
					$url =  OF_DIRECTORY . '/admin/images/';
					
//Service 4 Description
$options[] = array( "name" => "Service 4 Description",
					"desc" => "Enter description into the field",
					"id" => $shortname."_service4desc",
					"std" => "This is the section for more details about the service...",
					"type" => "textarea");
					
//Service Link 5
$options[] = array( "name" => "Service Link 5",
					"desc" => "Enter link into the field",
					"id" => $shortname."_service5link",
					"std" => "#",
					"type" => "text");  
					
//Service 5 Title
$options[] = array( "name" => "Service 5 Title",
					"desc" => "Enter title into the field",
					"id" => $shortname."_servicetitle5",
					"std" => "Post Checker",
					"type" => "text");
					
//Service 5 Image
$options[] = array( "name" => "Service 5 Image",
					"desc" => "Upload a 220x220 pixel image, or specify the address of your online file. (http://yoursite.com/image.png)",
					"id" => $shortname."_serviceimage5",
					"std" => get_template_directory_uri() . '/images/image-placeholder.png',
					"type" => "upload");
					$url =  OF_DIRECTORY . '/admin/images/';
					
//Service 5 Description
$options[] = array( "name" => "Service 5 Description",
					"desc" => "Enter description into the field",
					"id" => $shortname."_service5desc",
					"std" => "This is the section for more details about the service...",
					"type" => "textarea");
					
//Service Link 6
$options[] = array( "name" => "Service Link 6",
					"desc" => "Enter link into the field",
					"id" => $shortname."_service6link",
					"std" => "#",
					"type" => "text");	
					
//Service 6 Title
$options[] = array( "name" => "Service 6 Title",
					"desc" => "Enter title into the field",
					"id" => $shortname."_servicetitle6",
					"std" => "Post Checker",
					"type" => "text");
					
//Service 6 Image
$options[] = array( "name" => "Service 6 Image",
					"desc" => "Upload a 220x220 pixel image, or specify the address of your online file. (http://yoursite.com/image.png)",
					"id" => $shortname."_serviceimage6",
					"std" => get_template_directory_uri() . '/images/image-placeholder.png',
					"type" => "upload");
					$url =  OF_DIRECTORY . '/admin/images/';
					
//Service 6 Description
$options[] = array( "name" => "Service 6 Description",
					"desc" => "Enter description into the field",
					"id" => $shortname."_service6desc",
					"std" => "This is the section for more details about the service...",
					"type" => "textarea");	
					
//Service Link 7
$options[] = array( "name" => "Service Link 7",
					"desc" => "Enter link into the field",
					"id" => $shortname."_service7link",
					"std" => "#",
					"type" => "text");
					
//Service 7 Title
$options[] = array( "name" => "Service 7 Title",
					"desc" => "Enter title into the field",
					"id" => $shortname."_servicetitle7",
					"std" => "Post Checker",
					"type" => "text");
					
//Service 7 Image
$options[] = array( "name" => "Service 7 Image",
					"desc" => "Upload a 220x220 pixel image, or specify the address of your online file. (http://yoursite.com/image.png)",
					"id" => $shortname."_serviceimage7",
					"std" => get_template_directory_uri() . '/images/image-placeholder.png',
					"type" => "upload");
					$url =  OF_DIRECTORY . '/admin/images/';
					
//Service 7 Description
$options[] = array( "name" => "Service 7 Description",
					"desc" => "Enter description into the field",
					"id" => $shortname."_service7desc",
					"std" => "This is the section for more details about the service...",
					"type" => "textarea");
					
//Service Link 8
$options[] = array( "name" => "Service Link 8",
					"desc" => "Enter link into the field",
					"id" => $shortname."_service8link",
					"std" => "#",
					"type" => "text");	
					
//Service 8 Title
$options[] = array( "name" => "Service 8 Title",
					"desc" => "Enter title into the field",
					"id" => $shortname."_servicetitle8",
					"std" => "Post Checker",
					"type" => "text");
					
//Service 8 Image
$options[] = array( "name" => "Service 8 Image",
					"desc" => "Upload a 220x220 pixel image, or specify the address of your online file. (http://yoursite.com/image.png)",
					"id" => $shortname."_serviceimage8",
					"std" => get_template_directory_uri() . '/images/image-placeholder.png',
					"type" => "upload");
					$url =  OF_DIRECTORY . '/admin/images/';
					
//Service 8 Description
$options[] = array( "name" => "Service 8 Description",
					"desc" => "Enter description into the field",
					"id" => $shortname."_service8desc",
					"std" => "This is the section for more details about the service...",
					"type" => "textarea");
					
//Ranking Options
$options[] = array( "name" => "Ranking Options",
					"type" => "heading");
					
//Ranking Heading
$options[] = array( "name" => "Ranking Heading",
					"desc" => "Enter text into the field",
					"id" => $shortname."_rankheading",
					"std" => "Rank Heading",
					"type" => "text"); 
					
//Ranking Footer Text
$options[] = array( "name" => "Ranking Footer Text",
					"desc" => "Enter text into the field",
					"id" => $shortname."_rankfooter",
					"std" => "Rank Footer Text",
					"type" => "text");  
					
//3 Column Boxes Options
$options[] = array( "name" => "Bottom Boxes Options",
					"type" => "heading"); 
					
//First Box Title
$options[] = array( "name" => "First Box Title",
					"desc" => "Enter title into the field",
					"id" => $shortname."_threecol1",
					"std" => "Your Charity Contributions",
					"type" => "text"); 
					
//First Box Content
$options[] = array( "name" => "Select a Category options for First Box Content",
					"desc" => "Hit the dropdown and select a category from the listings",
					"id" => $shortname."_charity",
					"std" => "Select a category:",
					"type" => "select",
					"options" => $of_categories);       
					
//Second Box Title
$options[] = array( "name" => "Second Box Title",
					"desc" => "Enter title into the field",
					"id" => $shortname."_threecol2",
					"std" => "Latest Leads",
					"type" => "text");
					
//Third Box Title
$options[] = array( "name" => "Third Box Title",
					"desc" => "Enter title into the field",
					"id" => $shortname."_threecol3",
					"std" => "Latest News",
					"type" => "text");
					 		                      
//Footer Options
$options[] = array( "name" => "Footer Options",
					"type" => "heading"); 
					
//Footer Menu
$options[] = array( "name" => "Footer Menu",
					"desc" => "Enter html menu into the field",
					"id" => $shortname."_footermenu",
					"std" => "Footer HTML menu here..",
					"type" => "textarea");
					
//Twitter URL
$options[] = array( "name" => "Twitter URL",
					"desc" => "Enter url into the field",
					"id" => $shortname."_twitterlink",
					"std" => "Twitter URL Here!",
					"type" => "text");
					
//Facebook URL
$options[] = array( "name" => "Facebook URL",
					"desc" => "Enter url into the field",
					"id" => $shortname."_facebooklink",
					"std" => "Facebook URL Here!",
					"type" => "text"); 
					
//Google+ URL
$options[] = array( "name" => "Google+ URL",
					"desc" => "Enter url into the field",
					"id" => $shortname."_googlelink",
					"std" => "Google+ URL URL Here!",
					"type" => "text");
					
//Footer Copyright
$options[] = array( "name" => "Footer Copyright",
					"desc" => "Enter text into the field",
					"id" => $shortname."_footercopyright",
					"std" => "Footer copyright here..",
					"type" => "textarea");      

/*** Stop adding options ***/

update_option('of_template',$options); 					  
update_option('of_themename',$themename);   
update_option('of_shortname',$shortname);
}
}
?>