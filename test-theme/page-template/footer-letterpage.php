<div id="footer">
  <div id="footer-nav">
    <?php echo get_option('of_footermenu') ?>
    <div class="footer-social">
      <a href="<?php echo get_option('of_twitterlink') ?>" class="icon-button twitter" target="_blank"><i class="icon-twitter"></i><span></span></a>
      <a href="<?php echo get_option('of_facebooklink') ?>" class="icon-button facebook" target="_blank"><i class="icon-facebook"></i><span></span></a>
      <a href="<?php echo get_option('of_googlelink') ?>" class="icon-button linkedin" target="_blank"><i class="icon-linkedin"></i><span></span></a>
    </div>
  </div>
  <div id="footer-copyright">
    <div style="width: 180px;float:left;margin: 15px 10px;">
      <a href="/mobile-app/"><img src="<?php print(get_template_directory_uri()); ?>/images/appstore-btn.png"></a>
    </div>
    <div style="width: 180px;float:left;margin-top: 15px;">
      <a href="/mobile-app/"><img src="<?php print(get_template_directory_uri()); ?>/images/android-btn.png" height="53"></a>
    </div>
    <p><?php echo get_option('of_footercopyright')?></p>
    <!-- <p>&copy; 2016 All Rights Reserved, Umbrella Business Support Ltd, Zeal House, 8 Deer Park Road, London, SW19 3UU. Company Number: 08708480 (Registered England &amp; Wales) ICO Registration Number: ZA167803, Tel: 0203 0120 251 Fax: 0203 0120 254 Email: enquiries@umbrellasupport.co.uk</p> -->
</div>
</div>
</div>
</div>

<?php wp_footer(); ?>

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
 

 $acount_id  = $getName->data[0]->id;
 $acount_balance = $getName->data[0]->f1547;
 $packagelower = $getName->data[0]->f1548;
 //var_dump($getName);


 if( isset($acount_id) AND $acount_id != '' ){
   // $accountid = $getName->data[0]->id;
   $accountid = $acount_id;
 }
 else{
   $accountid = 00000;
 }

 echo '<script tyle="text/javascript">
  jQuery(document).ready(function(){
      jQuery(".rae-link").attr("href","https://clickhere.ontraport.net/t?orid='.$accountid.'&opid=3"); 
  });
  </script>';
}
else
{
  echo '<script tyle="text/javascript">
  jQuery(document).ready(function(){
      jQuery(".lo-link").attr("href","'.$base_url.'");
      jQuery(".lo-link").html("Log In");
  });
  </script>';
}
?>


</body></html>