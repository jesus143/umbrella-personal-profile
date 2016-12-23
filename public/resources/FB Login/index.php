<html>
	<head>
		<style>
			.user-fb-wrapper{
				background: #d2d2d2;
				padding: 9px 3px;
				margin-top: -7px;
				margin: 4px;
				border-radius: 4px;
			}
			.user-fb-wrapper .fb-logout{
				background:url('http://livewebchatcode.com/facebook/logout.png') no-repeat;
				position: absolute;
				height: 16px;
				background-size: cover;
				border: none;
				top: -1px;
				right: 7px;
				width: 14px;
			}
			.user-fb-wrapper .fb-details{margin: -5px 0 0 -29px;}
			.user-fb-wrapper .fb-details b{
				position: relative;
				top: 6px;
			}
			.fbimg { height: 19px !important; }
			.user-fb-wrapper .fb-logout:hover{background-position: 0 -18px;}
			.user-fb-wrapper .facebook-icon{
				position: absolute;
				top: 18px;
				left: 7px;
			}
			.user-fb-wrapper .fb-profile{
				border-radius: 4px;
				width: 65%;
			}
			.user-fb-wrapper .facebook-icon img{
				width: 14px;
			}
			.form-size-textarea{height:65px!important;max-width:286px;}
		</style>
	</head>
	<body>
		<?php 
			$displayResult = false; 
			if(isset($_SESSION['fb_id']) and $displayResult == true) { ?>

			<div class="row user-fb-wrapper" style="">
				<div class="col-md-3 col-sm-3 col-xs-3" >
					<img class="fb-profile" src="http://graph.facebook.com/<?php echo $_SESSION['fb_id']; ?>/picture">
					<div class="facebook-icon"><img src="http://livewebchatcode.com/facebook/facebook-icon.png"></div>
				</div>
				<div class="col-md-9 col-sm-9 col-xs-9">
					<?php 
						echo '<p class="fb-details"><b>'.$_SESSION['fb_fn'] . '</b><br/>';
						echo $_SESSION['fb_ea'] . '</p>';
					?>
					<a href="#" class="fb-logout" onClick="window.location.reload();"></a>
					<input type="hidden" name="Username" value="<?php echo htmlspecialchars($_SESSION['fb_fn']);?>" />
					<input type="hidden" name="Email" value="<?php echo htmlspecialchars($_SESSION['fb_ea']);?>" />
				</div>
			</div>
		<?php session_destroy(); }else{ ?>
			<div class="row">
	
				<script type="text/javascript">
					var myWindow;
					var cInterval;
					function fbauth(){
						myWindow  = window.open('http://testing.umbrellasupport.co.uk/wp-content/plugins/umbrella-personal-profile/public/resources/FB%20Login/facebook-auth.php', '_blank', 'height=620, width=620');
						cInterval = setInterval(function(){ 
							if(myWindow.closed){
								window.location.reload();
								clearInterval(cInterval);
							}
						}, 1000);		
					}
				</script>
				
				<div class="col-md-12">
					<div class="form-group">
						<label class="control-label">Authenticate Account with Facebook</label><br>
						<a href="#" id="fblogin" onclick="fbauth()"> <img height="19px" src="http://livewebchatcode.com/facebook/fb-login-button-v1.png"></a>
					</div>
				</div>
			</div>
		<?php } ?>
	</body>
</html>