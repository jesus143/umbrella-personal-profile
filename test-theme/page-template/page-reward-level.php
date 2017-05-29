<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
jQuery(document).ready(function(){
	jQuery('#current-user-level').prevAll().addClass( "unlock-img" );
	
	
});
	
</script>
<style type="text/css">

.unlock-img .lockAll{
	
	background: transparent url(/wp-content/uploads/2016/09/podlock-small.png) no-repeat scroll 0 0;
	
}

#current-user-level .img-container {
	width: 100%;
    height: 125px;
}

#current-user-level .e3ve-reward-level-col-1 h3,
#current-user-level .e3ve-reward-level-col-2 h3,
#current-user-level .e3ve-reward-level-col-3 h3 {
    background-color: #ffe5e5 !important;
}

#reward-bg {
	background: #ffe5e5 !important;
}


#current-user-level img {
	filter: opacity(100%) !important;
}

.e3ve-reward-level-col-3 ul li.unlock {
	
    background: transparent url(/wp-content/uploads/2016/09/podlock-small.png) no-repeat scroll 0 0;
    border-bottom: 1px solid #f5f5f5;
    padding-left: 20px;
    text-align: left;

}

.e3ve-reward-level-col-2 ul {
    min-height: 115px !important;
}

.e3ve-reward-level-col-3-content {
    min-height: 115px !important;
}

</style>

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
        if(isset($getData->data) && count($getData->data) > 0){
            $acount_id      = $getData->data[0]->id;
            $acount_balance = $getData->data[0]->f1547;
            $packagelower   = $getData->data[0]->f1548;
			$rewardLevel   = $getData->data[0]->f1549;
        }else{

            $acount_id = 000000;

        }

        return $getData;

    }
	
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
// $content = apply_filters('the_content', $post->post_content); \
	/*
	echo "<pre>";
	print_r(get_account_or_data()->data[0]->f1549);
	echo "</pre>";
	*/
	
	$rewardLevel = get_account_or_data()->data[0]->f1549;
	//$rewardLevel = 5;
	
	//print_r($rewardLevel);
	
?>
  The higher your Reward Level the more goodies, benefits, rewards or whatever you want to call them you receive. To keep the playing field clear your Reward Level has nothing to do with the size of your business or the amount of money that you spend with us. One man band businesses can reach Level 5 just as easily as firms with dozens of employees.
<div id="e3ve-reward-level-container" opacitylevel="">

	<div class="e3ve-reward-level-row-1" id="<?php if( $rewardLevel == '1' ): echo "current-user-level"; endif; ?>">
		<div class="e3ve-reward-level-col-1">
			<h3>Level 1</h3>
			
			<div class="img-container" id="<?php if( $rewardLevel == '1' ): echo "reward-bg"; endif; ?>">
				<img class="image-one" src="http://testing.umbrellasupport.co.uk/wp-content/uploads/2016/08/reward-lvl-1.png" alt="level 1" width="500" height="500"/>
			</div>
		</div>
		<div class="e3ve-reward-level-col-2">
			<h3>Requirement</h3>
			<ul id="<?php if( $rewardLevel == '1' ): echo "reward-bg"; endif; ?>">
				<li>Active Umbrella Account &amp; Are Paying Your Bills</li>
			</ul>
		</div>
		<div class="e3ve-reward-level-col-3">
			<h3>Reward</h3>
			<ul class="e3ve-reward-level-col-3-content" id="<?php if( $rewardLevel == '1' ): echo "reward-bg"; endif; ?>">
					<li class="<?php if( $rewardLevel == '1'): echo "unlock"; endif; ?> lockAll">A Big Kiss</li>
					<!--<li class="">A Big Kiss</li>-->
			</ul>
		</div>
	</div>
	
	<div class="e3ve-reward-level-row-1" id="<?php if( $rewardLevel == '2' ): echo "current-user-level"; endif; ?>">
		<div class="e3ve-reward-level-col-1">
			<h3>Level 2</h3>
			
			<div class="img-container" id="<?php if( $rewardLevel == '2' ): echo "reward-bg"; endif; ?>">
				<img class="image-two" src="http://testing.umbrellasupport.co.uk/wp-content/uploads/2016/08/reward-lvl-2.png" alt="level 2" width="500" height="500"/>
			</div>
			
		</div>
		<div class="e3ve-reward-level-col-2">
			<h3>Requirements</h3>
			<ul id="<?php if( $rewardLevel == '2' ): echo "reward-bg"; endif; ?>">
				<li>Connected Call Answering or Live Chat Service</li>
				<li>Answers 60 Second Satisfaction Survey Monthly</li>
				<li>Facebook Account Synced with Umbrella</li>
				<li>Backup Payment Method on File</li>
			</ul>
		</div>
		<div class="e3ve-reward-level-col-3">
			<h3>Rewards</h3>
			<ul class="e3ve-reward-level-col-3-content" id="<?php if( $rewardLevel == '2' ): echo "reward-bg"; endif; ?>">
					<li class="<?php if( $rewardLevel == '2'): echo "unlock"; endif; ?> lockAll">Reputation Radar</li>
					<li class="<?php if( $rewardLevel == '2'): echo "unlock"; endif; ?> lockAll">Fax to Email Tool</li>
					<li class="<?php if( $rewardLevel == '2'): echo "unlock"; endif; ?> lockAll">Voice Broadcast Tool</li>
					<li class="<?php if( $rewardLevel == '2'): echo "unlock"; endif; ?> lockAll">SMS Broadcast Tool</li>
					<li class="<?php if( $rewardLevel == '2'): echo "unlock"; endif; ?> lockAll">Umbrella Branding Removed</li>
			</ul>
		</div>
	</div>
	
	<div class="e3ve-reward-level-row-1" id="<?php if( $rewardLevel == '3' ): echo "current-user-level"; endif; ?>">
		<div class="e3ve-reward-level-col-1">
			<h3>Level 3</h3>
			
			<div class="img-container" id="<?php if( $rewardLevel == '3' ): echo "reward-bg"; endif; ?>">
				<img class="image-three" style="filter: opacity(30%);" src="http://testing.umbrellasupport.co.uk/wp-content/uploads/2016/08/reward-lvl-3.png" alt="level 3" width="500" height="500"/>
			</div>
				
		</div>
		<div class="e3ve-reward-level-col-2">
			<h3>Requirements</h3>
			<ul id="<?php if( $rewardLevel == '3' ): echo "reward-bg"; endif; ?>">
				<li>Using Postcode or Eligibility Checker on Website</li>
				<li>Using Call Answering + Live Chat Service</li>
				<li>Directory Given to Ineligible Leads by Phone,Email etc</li>
				<li>Using Umbrella Mobile App</li>
			</ul>
		</div>
		<div class="e3ve-reward-level-col-3">
			<h3>Rewards</h3>
			<ul class="e3ve-reward-level-col-3-content" id="<?php if( $rewardLevel == '3' ): echo "reward-bg"; endif; ?>">
					<li class="<?php if( $rewardLevel == '3'): echo "unlock"; endif; ?> lockAll">Company &amp; Director Radar</li>
					<li class="<?php if( $rewardLevel == '3'): echo "unlock"; endif; ?> lockAll">Consumer Radar (coming soon)</li>
					<li class="<?php if( $rewardLevel == '3'): echo "unlock"; endif; ?> lockAll">Monthly Raffle with £200+ Prize</li>
					<li class="<?php if( $rewardLevel == '3'): echo "unlock"; endif; ?> lockAll">Medium Weight Directory Ranking</li>
			</ul>
		</div>
	</div>
	<div class="e3ve-reward-level-row-1" id="<?php if( $rewardLevel == '4' ): echo "current-user-level"; endif; ?>">
		<div class="e3ve-reward-level-col-1">
			<h3>Level 4</h3>
			<div class="img-container" id="<?php if( $rewardLevel == '4' ): echo "reward-bg"; endif; ?>">
				<img class="image-four" src="http://testing.umbrellasupport.co.uk/wp-content/uploads/2016/08/reward-lvl-4.png" alt="level 4" width="500" height="500"/>
			</div>
		</div>
		<div class="e3ve-reward-level-col-2">
			<h3>Requirements</h3>
			<ul id="<?php if( $rewardLevel == '4' ): echo "reward-bg"; endif; ?>">
				<li>Qualifying Questions via Phone, Live Chat &amp; Checker</li>
				<li>Eligibility/Postcode Tool on ALL Major Webpages</li>
				<li>Updates Status of All Leads in Support Centre</li>
				<li>Actively BUYING Leads from the Umbrella Network</li>
			</ul>
		</div>
		<div class="e3ve-reward-level-col-3">
			<h3>Rewards</h3>
			<ul class="e3ve-reward-level-col-3-content" id="<?php if( $rewardLevel == '4' ): echo "reward-bg"; endif; ?>">
					<li class="<?php if( $rewardLevel == '4'): echo "unlock"; endif; ?> lockAll">Invitations to Umbrella's Events</li>
					<li class="<?php if( $rewardLevel == '4'): echo "unlock"; endif; ?> lockAll">Media Contacts &amp; PR Services</li>
					<li class="<?php if( $rewardLevel == '4'): echo "unlock"; endif; ?> lockAll">Advanced Call Management</li>
					<li class="<?php if( $rewardLevel == '4'): echo "unlock"; endif; ?> lockAll">TPS Database Access</li>
					<li class="<?php if( $rewardLevel == '4'): echo "unlock"; endif; ?> lockAll">Super Powered Radars</li>
			</ul>
		</div>
	</div>
	<div class="e3ve-reward-level-row-1" id="<?php if( $rewardLevel == '5' ): echo "current-user-level"; endif; ?>">
		<div class="e3ve-reward-level-col-1">
			<h3>Level 5</h3>
			
			<div class="img-container" id="<?php if( $rewardLevel == '5' ): echo "reward-bg"; endif; ?>">
				<img class="image-five" src="http://testing.umbrellasupport.co.uk/wp-content/uploads/2016/08/reward-lvl-5.png" alt="level 5" width="500" height="500"/>
			</div>

		</div>
		<div class="e3ve-reward-level-col-2">
		<h3>Requirements</h3>
			<ul id="<?php if( $rewardLevel == '5' ): echo "reward-bg"; endif; ?>">
				<li>At least 2 Qualifying Questions</li>
				<li>Businesses Prime 01/02/08x Number Supplied by us</li>
				<li>Maintains Account Balance of £500+</li>
				<li>Uses Umbrella's "Smart Contact Forms" on Website</li>
				<li>Refers at Least 1 New Partner to us a Month</li>
			</ul>
		</div>
		<div class="e3ve-reward-level-col-3">
			<h3>Rewards</h3>
			<ul class="e3ve-reward-level-col-3-content" id="<?php if( $rewardLevel == '5' ): echo "reward-bg"; endif; ?>">
				<li class="<?php if( $rewardLevel == '5'): echo "unlock"; endif; ?> lockAll">FREE Part Time Virtual PA</li>
				<li class="<?php if( $rewardLevel == '5'): echo "unlock"; endif; ?> lockAll">Heavyweight Directory Ranking</li>
				<li class="<?php if( $rewardLevel == '5'): echo "unlock"; endif; ?> lockAll">VIP Treatment at Umbrella's Events</li>
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
