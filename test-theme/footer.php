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
    <p><?php echo get_option('of_footercopyright') ?></p>
  </div>
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
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script> 
<script>window.jQuery || document.write('<script src="js/libs/jquery-1.7.min.js">\x3C/script>')</script> 

<!-- FlexSlider --> 
<script type="text/javascript">
    $(function(){
      SyntaxHighlighter.all();
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
    g.src = 'http://testing.umbrellasupport.co.uk/wp-content/themes/umbrella-portal/js/prism.js';
    s.parentNode.insertBefore(g, s);
}(document, 'script'));
</script>
</body></html>