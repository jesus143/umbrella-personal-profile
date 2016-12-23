<?php
/**
 * Template Name: Sample Page Chat2
 */
 
 echo get_domain($_SERVER['HTTP_HOST']);
?>
<html>
	<head>
		<title>My Website</title>
	</head>
	<body>
		<h1> This is my WEBSITE! </1>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
		<script type="text/javascript">
			var LHCChatOptions = {};
			LHCChatOptions.opt = {widget_height:340,widget_width:300,popup_height:520,popup_width:500};
			
			$(document).ready(function() {
				var Vclientid 	= '77336';
				var Vhashid	= '7840431e46f0ebd7b683f39c36d78436';
				var parts = window.location.hostname.split('.');
				var subdomain = parts.shift();
				var upperleveldomain = parts.join('.');
				
				
					
				$.ajax({
				   type: "POST", // HTTP method POST or GET
				   url: "http://testing.umbrellasupport.co.uk/chat-process/", //Where to make Ajax calls
				   //dataType:"text", // Data type, HTML, json etc.
				   data:{
					   clientid:Vclientid,
					   hashid:Vhashid,
					   domainn:upperleveldomain
				   },
				   success:function(response){
					 //responseaction
					//$('#chatcontainer').html(response);
					if(response == '<img src="http://testing.umbrellasupport.co.uk/wp-content/uploads/2016/07/NotAllowed.png">'){
						
				    }else{
					   load_chat();
				    }
					
				   },
				   error:function (xhr, ajaxOptions, thrownError){
					alert("Error: " + thrownError);
				   }
				});
			});
		</script>
		<script src="http://testing.umbrellasupport.co.uk/wp-content/themes/umbrella-portal/js/clients/77336.js"></script>
		<div id="chatcontainer"></div> 
	</body>
</html>