<?php get_header(); ?>

<?php

function get_account_or_data(){

    global $current_user;

    if(is_user_logged_in()){

         $customAPIKEY  = get_field('custom_api_key','option');// name of the admin
         $customAPIID  = get_field('custom_api_id','option');// Email Title for the admin
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
          'Api-Appid:'.$customAPIID,
          'Api-Key:'.$customAPIKEY
        ));
        $response = curl_exec($session);
        curl_close($session);
        //header("Content-Type: text");
        //echo "CODE: " . $response;
        $getData = json_decode($response);
        //var_dump($getName);
        if(isset($getName->data) && count($getName->data) > 0){
            $acount_id      = $getName->data[0]->id;
            $acount_balance = $getName->data[0]->f1547;
            $packagelower   = $getName->data[0]->f1548;	
        }else{

            $acount_id = 000000;

        }

        return $getData;

    }
	
	
	echo "<pre>";
	print_r( get_account_or_data() );
	echo "</pre>";
	
}

?>


<div id="page-content">
  <?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
  <h2>
    <?php the_title(); ?>
  </h2>
  
  <?php

    // $id=$post->ID; 
    // $post = get_post($id); 
    // $content = apply_filters('the_content', $post->post_content); 

  ?>
  The higher your Reward Level the more goodies, benefits, rewards or whatever you want to call them you receive. To keep the playing field clear your Reward Level has nothing to do with the size of your business or the amount of money that you spend with us. One man band businesses can reach Level 5 just as easily as firms with dozens of employees.
<div id="e3ve-reward-level-container" opacitylevel="">
<div class="e3ve-reward-level-row-1">
<div class="e3ve-reward-level-col-1">
<h3>Level 1</h3>
<img class="image-one" src="http://testing.umbrellasupport.co.uk/wp-content/uploads/2016/08/reward-lvl-1.png" alt="level 1" width="500" height="500" />

</div>
<div class="e3ve-reward-level-col-2">
<h3>Requirement</h3>
<ul>
	<li>Active Umbrella Account &amp; Are Paying Your Bills</li>
</ul>
</div>
<div class="e3ve-reward-level-col-3">
<h3>Reward</h3>
<ul class="e3ve-reward-level-col-3-content">
	<li class="lock-one">A Big Kiss</li>
</ul>
</div>
</div>
<div class="e3ve-reward-level-row-1">
<div class="e3ve-reward-level-col-1">
<h3>Level 2</h3>
<img class="image-two" src="http://testing.umbrellasupport.co.uk/wp-content/uploads/2016/08/reward-lvl-2.png" alt="level 2" width="500" height="500" />

</div>
<div class="e3ve-reward-level-col-2">
<h3>Requirements</h3>
<ul>
	<li>Connected Call Answering or Live Chat Service</li>
	<li>Answers 60 Second Satisfaction Survey Monthly</li>
	<li>Facebook Account Synced with Umbrella</li>
	<li>Backup Payment Method on File</li>
</ul>
</div>
<div class="e3ve-reward-level-col-3">
<h3>Rewards</h3>
<ul class="e3ve-reward-level-col-3-content">
	<li class="lock-two">Reputation Radar</li>
	<li class="lock-two">Fax to Email Tool</li>
	<li class="lock-two">Voice Broadcast Tool</li>
	<li class="lock-two">SMS Broadcast Tool</li>
	<li class="lock-two">Umbrella Branding Removed</li>
</ul>
</div>
</div>
<div class="e3ve-reward-level-row-1">
<div class="e3ve-reward-level-col-1">
<h3>Level 3</h3>
<img class="image-three" style="filter: opacity(30%);" src="http://testing.umbrellasupport.co.uk/wp-content/uploads/2016/08/reward-lvl-3.png" alt="level 3" width="500" height="500" />

</div>
<div class="e3ve-reward-level-col-2">
<h3>Requirements</h3>
<ul>
	<li>Using Postcode or Eligibility Checker on Website</li>
	<li>Using Call Answering + Live Chat Service</li>
	<li>Directory Given to Ineligible Leads by Phone,Email etc</li>
	<li>Using Umbrella Mobile App</li>
</ul>
</div>
<div class="e3ve-reward-level-col-3">
<h3>Rewards</h3>
<ul class="e3ve-reward-level-col-3-content">
	<li class="lock-three">Company &amp; Director Radar</li>
	<li class="lock-three">Consumer Radar (coming soon)</li>
	<li class="lock-three">Monthly Raffle with £200+ Prize</li>
	<li class="lock-three">Medium Weight Directory Ranking</li>
</ul>
</div>
</div>
<div class="e3ve-reward-level-row-1">
<div class="e3ve-reward-level-col-1">
<h3>Level 4</h3>
<img class="image-four" src="http://testing.umbrellasupport.co.uk/wp-content/uploads/2016/08/reward-lvl-4.png" alt="level 4" width="500" height="500" />

</div>
<div class="e3ve-reward-level-col-2">
<h3>Requirements</h3>
<ul>
	<li>Qualifying Questions via Phone, Live Chat &amp; Checker</li>
	<li>Eligibility/Postcode Tool on ALL Major Webpages</li>
	<li>Updates Status of All Leads in Support Centre</li>
	<li>Actively BUYING Leads from the Umbrella Network</li>
</ul>
</div>
<div class="e3ve-reward-level-col-3">
<h3>Rewards</h3>
<ul class="e3ve-reward-level-col-3-content">
	<li class="lock-four">Invitations to Umbrella's Events</li>
	<li class="lock-four">Media Contacts &amp; PR Services</li>
	<li class="lock-four">Advanced Call Management</li>
	<li class="lock-four">TPS Database Access</li>
	<li class="lock-four">Super Powered Radars</li>
</ul>
</div>
</div>
<div class="e3ve-reward-level-row-1">
<div class="e3ve-reward-level-col-1">
<h3>Level 5</h3>
<img class="image-five" src="http://testing.umbrellasupport.co.uk/wp-content/uploads/2016/08/reward-lvl-5.png" alt="level 5" width="500" height="500" />

</div>
<div class="e3ve-reward-level-col-2">
<h3>Requirements</h3>
<ul>
	<li>At least 2 Qualifying Questions</li>
	<li>Businesses Prime 01/02/08x Number Supplied by us</li>
	<li>Maintains Account Balance of £500+</li>
	<li>Uses Umbrella's "Smart Contact Forms" on Website</li>
	<li>Refers at Least 1 New Partner to us a Month</li>
</ul>
</div>
<div class="e3ve-reward-level-col-3">
<h3>Rewards</h3>
<ul class="e3ve-reward-level-col-3-content">
	<li class="lock-five">FREE Part Time Virtual PA</li>
	<li class="lock-five">Heavyweight Directory Ranking</li>
	<li class="lock-five">VIP Treatment at Umbrella's Events</li>
</ul>
</div>
</div>
</div>
Whilst to reach the next Level you will need to tick all the boxes of all Levels below you, you also receive all Rewards of each level below you too as you climb. Speak with your Business Growth Executive today about how you can easily climb to Level 5 in no time.
   
  
  <div style="clear:both"></div>
  <?php endwhile; ?>
  <?php else : ?>
  <h2 class="center">Not Found</h2>
  <p class="center">Sorry, but you are looking for something that isn't here.</p>
  <?php get_search_form(); ?>
  <?php endif; ?>
</div>
<?php get_footer(); ?>
