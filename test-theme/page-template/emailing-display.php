<?php
/*
*Template Name:Email Display
*/
get_header(); 
?>
<div id="page-content">
<?php
$emailMessage = get_field('email_message','option');// Email Message for the admin
echo $emailMessage;
?>
</div>
<?php get_footer(); ?>