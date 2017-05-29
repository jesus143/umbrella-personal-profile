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
<script>
jQuery(document).ready(function(){
    jQuery("#show-nav").click(function(){
        jQuery(".main-nav").slideToggle('slow');
    });
});
</script>
<!-- jQuery --> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script> 
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.min.js">\x3C/script>')</script> 

<!-- FlexSlider --> 
<script type="text/javascript">
    $(function(){
      //SyntaxHighlighter.all();
    });
    $(window).load(function(){
      $('.flexslider').flexslider({
        animation: "slide",
        controlsContainer: $(".custom-controls-container"),
        customDirectionNav: $(".custom-navigation a"),
        start: function(slider){
          $('body').removeClass('loading');
        }
      });
    });
  </script>
<script>
$('.e3ve-material-icon').hover(
  function () {
    $('.e3ve-services-description-icon').show();
  }, 
  function () {
    $('.e3ve-services-description-icon').hide();
  }
);
</script>
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




<script type="text/javascript">
function downloadJSAtOnload() {
var element = document.createElement("script");
element.src = "defer.js";
document.body.appendChild(element);
}
if (window.addEventListener)
window.addEventListener("load", downloadJSAtOnload, false);
else if (window.attachEvent)
window.attachEvent("onload", downloadJSAtOnload);
else window.onload = downloadJSAtOnload;
</script>
<script>
function init() {
var imgDefer = document.getElementsByTagName('img');
for (var i=0; i<imgDefer.length; i++) {
if(imgDefer[i].getAttribute('data-src')) {
imgDefer[i].setAttribute('src',imgDefer[i].getAttribute('data-src'));
} } }
window.onload = init;
</script>
<script type="text/javascript">
(function(d, t) {
    var g = d.createElement(t),
        s = d.getElementsByTagName(t)[0];
    g.src = 'https://testing.umbrellasupport.co.uk/wp-content/themes/umbrella-portal/js/prism.js';
    s.parentNode.insertBefore(g, s);
}(document, 'script'));
</script>
<script>
// For Charity Page
$(document).ready(function(){
    $(".charity-top-bar h3").click(function(){
        $(".charity-how-it-works").slideToggle("slow");
    });
	$(".chiw-hide").click(function(){$(".charity-how-it-works").slideUp("slow");});
});

//Accordion Foldable 
$(document).ready(function(){
$( "#e3ve-accordion-container" ).accordion({
      heightStyle: "content",
      active:false,
      collapsible: true,
      header:"div.e3ve-accordianheader"
});

$("#e3ve-accordion-container").accordion({
    collapsible:true,

    beforeActivate: function(event, ui) {
         // The accordion believes a panel is being opened
        if (ui.newHeader[0]) {
            var currHeader  = ui.newHeader;
            var currContent = currHeader.next(".ui-accordion-content");
         // The accordion believes a panel is being closed
        } else {
            var currHeader  = ui.oldHeader;
            var currContent = currHeader.next(".ui-accordion-content");
        }
         // Since we've changed the default behavior, this detects the actual status
        var isPanelSelected = currHeader.attr("aria-selected") == "true";

         // Toggle the panel's header
        currHeader.toggleClass("ui-corner-all",isPanelSelected).toggleClass("accordion-header-active ui-state-active ui-corner-top",!isPanelSelected).attr("aria-selected",((!isPanelSelected).toString()));

        // Toggle the panel's icon
        currHeader.children(".ui-icon").toggleClass("ui-icon-triangle-1-e",isPanelSelected).toggleClass("ui-icon-triangle-1-s",!isPanelSelected);

         // Toggle the panel's content
        currContent.toggleClass("accordion-content-active",!isPanelSelected)    
        if (isPanelSelected) { currContent.slideUp(); }  else { currContent.slideDown(); }

        return false; // Cancel the default action
    }
});
});

$(document).ready(function(){
$( "#e3ve-accordion-container" ).accordion({
  active: 1
});
$( "#e3ve-accordion-container" ).accordion({
  active: 3
});
});

$(document).ready(function(){
    $(".cmcr-loanlength").click(function(){
        $(".cmcll-popup-loanlength").fadeIn(300);
    });
	$(".cmcll-popup-close p").click(function(){
		$(".cmcll-popup-loanlength").fadeOut(300);
	});
	
	$(".cmcr-repayment").click(function(){
        $(".cmcll-popup-repayment").fadeIn(300);
    });
	$(".cmcll-popup-close p").click(function(){
		$(".cmcll-popup-repayment").fadeOut(300);
	});
	
	$(".cmcr-disbursed").click(function(){
        $(".cmcll-popup-disbursed").fadeIn(300);
    });
	$(".cmcll-popup-close p").click(function(){
		$(".cmcll-popup-disbursed").fadeOut(300);
	});
	
	$(".cmcr-loss").click(function(){
        $(".cmcll-popup-loss").fadeIn(300);
    });
	$(".cmcll-popup-close p").click(function(){
		$(".cmcll-popup-loss").fadeOut(300);
	});
	
	$(".cmcr-partner").click(function(){
        $(".cmcll-popup-partner").fadeIn(300);
    });
	$(".cmcll-popup-close p").click(function(){
		$(".cmcll-popup-partner").fadeOut(300);
	});
	
	$(".cmcr-interest").click(function(){
        $(".cmcll-popup-interest").fadeIn(300);
    });
	$(".cmcll-popup-close p").click(function(){
		$(".cmcll-popup-interest").fadeOut(300);
	});
	
	$(".cmcr-rating").click(function(){
        $(".cmcll-popup-rating").fadeIn(300);
    });
	$(".cmcll-popup-close p").click(function(){
		$(".cmcll-popup-rating").fadeOut(300);
	});
});
</script>
</body></html>