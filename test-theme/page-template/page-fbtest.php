<?php get_header(); ?>
<div id="page-content">
  <?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
  <h2>
    <?php the_title(); ?>
  </h2>
<?php
$wpdb_b = new wpdb( "dbo656995985", "umbrella1986", "db656995985", "db656995985.db.1and1.com" );
    
$svces = $wpdb_b->get_results( "SELECT * FROM services", 'ARRAY_A' );
$partner_credits = $wpdb_b->get_results( "SELECT * FROM partner_credits", 'ARRAY_A' );
    
        
    
$showtables = $wpdb_b->get_results( "show tables", 'ARRAY_A' );  
    
echo "<pre>"; 
echo '<h1>All Tables</h1>';
print_r($showtables);
echo '<h1>All partner_credits</h1>'; 
print_r($partner_credits);
echo '<h1>All Services</h1>';
print_r($svces);
echo "</pre>";
?>    
  <?php
    $current_user = wp_get_current_user();
    $email = $current_user->user_email;
	require_once(TEMPLATEPATH."/class/class-ontraport-api.php");
    
    $settings['appId']  = '2_7818_AFzuWztKz';
	$settings['apiKey'] = 'fY4Zva90HP8XFx3';
    
	$ontraport = new ontraport_API( $settings['appId'], $settings['apiKey'] );
	$query = 'email=\''.$email.'\'';
	//$query = 'email=\'rhoward@raptorsms.com\'';
	// $query = 'email=\'UmbrellaTestingSmithtesting@umbrellasupport.co.uk\'';
	$owner = $ontraport->search_contacts($query); 
    echo "<pre>";
    print_r($owner);
    echo "</pre>";
  ?>
  <?php the_content(); ?>
  
  <div style="clear:both"></div>
  <?php endwhile; ?>
  <?php else : ?>
  <h2 class="center">Not Found</h2>
  <p class="center">Sorry, but you are looking for something that isn't here.</p>
  <?php get_search_form(); ?>
  <?php endif; ?>
    
</div>
<?php get_footer(); ?>
