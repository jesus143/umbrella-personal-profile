<?php
/*
*Template Name:Manual Top up
*/
get_header(); 
	$current_user = wp_get_current_user();
	$customAPIKEY  	= get_field('custom_api_key','option');// name of the admin
	$customAPIID  	= get_field('custom_api_id','option');// Email Title for the admin
	//$postargs 		= "http://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'".$current_user->user_email."'&searchNotes=true";
	$postargs 		= "http://api.ontraport.com/1/objects?objectID=0&performAll=true&sortDir=asc&condition=email%3D'marvin.romagos@yahoo.com'&searchNotes=true";
	$request		= "";
	$session 		= curl_init();
	curl_setopt ( $session, CURLOPT_RETURNTRANSFER, true );
	curl_setopt ( $session, CURLOPT_URL, $postargs );
	//curl_setopt ($session, CURLOPT_HEADER, true);
	curl_setopt ( $session, CURLOPT_HTTPHEADER, array(
	  'Api-Appid:'.$customAPIID,
	  'Api-Key:'.$customAPIKEY
	));
	$response = curl_exec( $session ); 
	curl_close( $session );
	//header("Content-Type: text");
	//echo "CODE: " . $response;
	$getName 		= json_decode( $response ); 
	//php functionalities here
	$manual=$getName->data[0]->id;
	
	$host  		= "db651120122.db.1and1.com"; 
	$database   = "db651120122";
	$user  		= "dbo651120122";
	$password   = "1qazxsw2!QAZXSW@";
	try{
	$WP_CON	= new PDO('mysql:host='.$host.';dbname='.$database.';', $user, $password);
	$WP_CON->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$WP_CON->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
	}catch(PDOException $ERR){
		echo $ERR->getMessage();
		exit();
	}
?>
<div id="page-content">
	<div class="manual-top-up">
		<?php //the_content();?>
		<h2><?php echo get_field('tm_title_header','option'); ?></h2>
		<p>
			<?php echo get_field('tm_contents','option'); ?>
		</p>
		<div class="e3ve-billing-container">
			<div class="e3ve-billing-row-1">
				<div class="e3ve-billing-account-balance">
					<h3>Account Balance: <span><strong><?php  echo do_shortcode( '[amountValue]');?></strong></span></h3>
					View this months Billing Statement <span><a href="http://portal.umbrellasupport.co.uk/billing/" target="_blank">here &gt;</a></span>

				</div>
				<div style="z-index: -1;" class="e3ve-billing-package">
				<div <?php  echo do_shortcode( '[packageBackground]');?>></div>
				<div style="z-index: 1; position: relative;">
					<h3>Package Level: <?php  echo do_shortcode( '[packageDetails]');?></h3>
					Monthly Investment: &pound;49/month <span><a href="#">Upgrade &gt;</a></span>

					</div>
				</div>
			</div>
			<div class="e3ve-topup-account">
				<label>Top Up Balance By: </label>
				<form action="" method="POST">
					<select form="acctform" name="acctlist2" id="topupAmount">
						<option value="">Please Select...</option>
						<option value="25">&pound;25.00</option>
						<option value="50">&pound;50.00</option>
						<option value="75">&pound;75.00</option>
						<option value="100">&pound;100.00</option>
						<option value="150">&pound;150.00</option>
						<option value="250">&pound;250.00</option>
						<option value="500">&pound;500.00</option>
					</select>
				</form>
				<script type='text/javascript'>
					$(function() {
						$('#topupAmount').change(function() {
							// if changed to, for example, the last option, then
							// $(this).find('option:selected').text() == D
							// $(this).val() == 4
							// get whatever value you want into a variable
							var x = $(this).val();	
							var display = '£'+x+'.00';	
							// and update the hidden input's value
							$('#topupAmountDisplay').val(display);
							$('#topupAmountDisplays').val(display);
							$('#topupAmounts').val(x);
							$("#direcDebit").attr("href", "https://gocardless.com?amount="+display+"&partner_id=<?php if(empty($manual)){ echo 'xxxx';}else{ echo $manual;} ?>");
						});
					});
				</script>
			</div>
			<div class="e3ve-billing-temp-text1" name="e3ve-tab-placeholder">
				<p>
				<span>Please first select Top Up Amount.</span>
				</p>
			</div>
			<div class="e3ve-billing-tab-container" name="hidden" style="display: none;">
			<ul class="tab" id="selector">
				<li id="card"><a class="tablinks active" onclick="openTabnav(event, 'DirectDebit')"  href="#/">Direct Debit</a></li>
				<li id="card"><a class="tablinks" onclick="openTabnav(event, 'BankTransfer')"  href="#/">Bank Transfer</a></li>
				<li id="credit-card"><a class="tablinks" onclick="openTabnav(event, 'CreditDebitCard')"  href="#/">Credit/Debit Card</a></li>
				<li id="card"><a class="tablinks" onclick="openTabnav(event, 'CashDeposit')"  href="#/">Cash Deposit</a></li>
				<li id="credit-card-list"><a class="tablinks properties-none" onclick="openTabnav(event, 'CreditList')"  href="#/">Credit Card List</a></li>
			</ul>
			<script>
				jQuery(document).ready(function($){
					$('#selector').on('click', 'li#credit-card .active', function(){
						$('li#credit-card-list a').removeClass('properties-none');
					});
					$('#selector').on('click', 'li#card .active', function(){
						$('li#credit-card-list a').addClass('properties-none');
					});
				 });
				</script>
			<div id="DirectDebit" class="tabcontent" style="display: block;">
				<?php if(is_user_logged_in()){ ?>
				<table border="0">
				<tr>
				<td>
					<div class="card-wrapper">
						<div class="card-image">
							<img src="/wp-content/uploads/2016/09/card-type-icons.jpg">
						</div>
						<div class="ssl-image">
							<img src="/wp-content/uploads/2016/09/ssl-300x133.png">
						</div>
					</div>
				</td>
				<td>
				<h3>Direct Debit</h3>
				<form method="post" id="directcardForm">
					<div class="form-row">
					<label>
						<span>Amount</span>
						<input type="text" name="amountValue" id="topupAmountDisplays" value="" disabled>
					</label>
					</div>
					<div class="form-row">
						<label> 
							<span>First Name</span>
							<input type="text" name="directFname">
						</label>
					</div>
					<div class="form-row">
						<label> 
							<span>Last Name</span>
							<input type="text" name="directLname">
						</label>
					</div>
					<div class="form-row">
						<label> 
							<span>Email Address</span>
							<input type="text" name="directEmail">
						</label>
					</div>
					<div class="form-row">
						<label> 
							<span>Sort Code</span>
							<input type="text" name="directEmail">
						</label>
					</div>
					<div class="form-row">
						<label> 
							<span>Account Number</span>
							<input type="text" name="directEmail">
						</label>
					</div>
					<div class="form-row">
						<label> 
							<span>Address Line</span>
							<input type="text" name="directEmail">
						</label>
					</div>
					<div class="form-row">
						<label> 
							<span>Town/City</span>
							<input type="text" name="directEmail">
						</label>
					</div>
					<div class="form-row">
						<label> 
							<span>Postcode</span>
							<input type="text" name="directEmail">
						</label>
					</div>
					<div class="form-row">
						<label> 
							<span>Partner ID</span>
							<input type="text" name="manualID" id="directmanualID" value="<?php if(empty($manual)){ echo 'xxxx';}else{ echo $manual;}?>">
						</label>
					</div>
							<input type="hidden" name="action" value="directCARDPAYMENT">
							<input type="submit" id="direct-process" name="directSubmit" value="Set Up Direct Debit">
					</div>
				</form>
				<div class="direct-process-message"></div>
				<script>
				jQuery(document).ready(function($){
							$("#direct-process").click(function(){
								var directmanualID = $("#directmanualID").val();
								var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
								// Returns successful data submission message when the entered information is stored in database.
								if(directmanualID==''){
									$( ".direct-process-message" ).append("<p class="+'direct-error-message'+"><b>✘</b>Your name must not be empty!</p>");
								}else{
									// AJAX Code To Submit Form.
									$.ajax({
										type: "POST",
										url: ajaxurl,
										data:{
										   action: "directCARDPAYMENT",
										   directmanualID :directmanualID,
										},
										cache: false,
										success: function(result){
											$(".direct-process-message").html(result);
											//window.top.location.reload();
										}
									});
								}
								return false;
							});
						 });	
				</script>
				<a id="direcDebit" href=""><img src="<?php echo get_stylesheet_directory_uri().'/images/direct-debit.png'; ?>"></a>
				</td>
				<td>
					<div class="cards-wrapper">
					<?php echo get_field('direct_debit_content', 'option'); ?>
					</div>
				</td>
				</tr>
				</table>
				<?php }else{ ?>
				Direct Debit content here...<br>
				Please login your account before you top up manually! 
				<?php } ?>
			</div>
			<div id="BankTransfer" class="tabcontent">
				<?php if(is_user_logged_in()){ ?>
				<table border="0">
				<tr>
				<td>
					<div class="card-wrapper">
						<div class="card-image">
							<img src="/wp-content/uploads/2016/10/bank_trasfer-512.png">
						</div>
					</div>
				</td>
				<td>
				<h3>Bank Transfer</h3>
				</td>
				<td>
					<div class="cards-wrapper">
					<?php echo get_field('bank_transfer_content', 'option'); ?>
					</div>
				</td>
				</tr>
				</table>
				<?php }else{ ?>
				Bank Transfer content here...<br>
				Please login your account before you top up manually! 
				<?php } ?>
			</div>
			<div id="CreditDebitCard" class="tabcontent">
				<?php if(is_user_logged_in()){ ?>
				<table border="0">
				<tr>
				<td>
					<div class="card-wrapper">
						<div class="card-image">
							<img src="/wp-content/uploads/2016/09/card-type-icons.jpg">
						</div>
						<div class="ssl-image">
							<img src="/wp-content/uploads/2016/09/ssl-300x133.png">
						</div>
					</div>
				</td>
				<td>
				<h3>Credit/Debit Card</h3>
				<div id="cvc-close">
				</div>
				<?php
				if(!empty($manual)){
					try {
						//var_dump($manual);
						$results = $WP_CON->query("SELECT * FROM wp_settingscredit WHERE setting_partnerid =".$manual);
						//echo "Successful.";
					} catch (Exception $e) {
						echo "Error.";
						exit;
					}
					$resultCredit=$results->fetchAll(PDO::FETCH_ASSOC);
					global $cardnameVal,$cardnumVal,$cardexpyVal,$cardexpmVal,$cardcvvVal;
					foreach( $resultCredit as $result ){
						$cardnameVal = $result['setting_cardname'];
						$cardnumVal  = $result['setting_cardnumber'];
						$cardexpmVal = $result['setting_expmonth'];
						$cardexpyVal = $result['setting_expyear'];
						$cardcvvVal  = $result['setting_cvv'];
					}
				}
				?>
				<form action="" method="POST" id="payment-form">
				  <span class="payment-errors"></span>
				  <span class="creditcard-log"></span>
				  <div class="form-row">
					<label>
					  <span>Amount</span>
					  <!--<input type="text" size="20" data-stripe="name" class="card-name" placeholder="card holder">--->
					  <input type='text' id='topupAmountDisplay' value='' class="card-amount" disabled>
					</label>
				  </div> 
				  <div class="form-row">
					<label>
					  <span>Card Holder Name</span>
					  <?php echo $resultCredit['setting_cardname'];?>
					  <input type="text" size="20" value="" data-stripe="name" class="card-name" placeholder="card holder">
					   <!--<input type="text" size="20" class="card-name" placeholder="card holder">-->
					</label>
				  </div>
				  <div class="form-row">
					<label>
					  <span>Card Number</span>
					  <input type="text" size="20" data-stripe="number" class="card-number" placeholder="card number">
					</label>
				  </div>

				  <div class="form-row">
					<label>
					  <span>Expiration (MM/YY)</span>
					  <input type="text" size="2" data-stripe="exp_month" class="expire-month" placeholder="month">
					</label>
					 / 
					<input type="text" size="2" data-stripe="exp_year" class="expire-month" placeholder="year">
				  </div>

				  <div class="form-row">
					<label>
					  <span>CVV2 <u> <a class="cvc-button" href="#cvc-popup">(What's this?)</a> </u></span>
					  <input type="text" size="4" data-stripe="cvc" class="card-cvc" placeholder="cvv2" maxlength="4"> 
					</label>
					<div id="cvc-popup" class="cvc-overlay">
						<div class="cvc-popup">
							<a class="cvc-close" href="#cvc-close">&times;</a>
							<div class="cvc-content">
								<img src="/wp-content/uploads/2016/09/cv_card.jpg">
							</div>
						</div>
					</div>
				  </div>
				  <input type='hidden' id='topupAmounts' value='' class="card-amount">
				  <input type="submit" class="submit-card" value="Submit Payment">
				</form>
				<script>
				jQuery(document).ready(function($){
					$(function() {
						Stripe.setPublishableKey('pk_test_u289X2Do4OavHR2STjbI2TsL');
						var $form = $('#payment-form');
						$form.submit(function(event) {
						// Disable the submit button to prevent repeated clicks:
						//$form.find('.submit').prop('disabled', true);

						// Request a token from Stripe:
						//Stripe.card.createToken($form, stripeResponseHandler);
							Stripe.card.createToken($form,function(status,response){
							console.log(status);
							console.log(response);  
							if(response.error){
								$form.find('.payment-errors').text(response.error.message);
								$form.find('.submit').prop('disabled', true);
							}else{
								var token=response.id;
								//var directCard='directCard';
								var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
								$form.append($('<input type="hidden" name="stripe-token" id="stripeToken"/>').val(token));
								var stripeToken=$("#stripeToken").val();
								var topupAmount=$('#topupAmounts').val();
								var creditsucc= confirm("Are you sure yo want to purchase?");
								if (creditsucc== true){
									$.ajax({
										type: "POST",
										url: ajaxurl,
										//timeout: 3000,
										data:{
										   action: "creditCard", 
										   stripeToken :stripeToken,
										   topupAmount :topupAmount, 
										},
										cache: false,
										success: function(result){
											//alert(result);
											//console.log(result);
											$(".creditcard-log").html(result);
											//window.top.location.reload();
										}
									});
								}else{
									
								}
							}
						});

						// Prevent the form from being submitted:
						return false;
					  });
					});
				});
				</script>
				</td>
				<td>
					<div class="cards-wrapper">
					<?php echo get_field('credit_debit_content', 'option'); ?>
					</div>
				</td>
				</tr>
				</table>
				<?php }else{ ?>
				Credit/Debit Card content here...<br>
				Please login your account before you top up manually!
				<?php } ?>
			</div>
			<div id="CashDeposit" class="tabcontent">
			<?php if(is_user_logged_in()){ ?>
				<table border="0">
				<tr>
				<td>
					<div class="card-wrapper">
						<div class="card-image">
							<img src="/wp-content/uploads/2016/10/75010.jpg">
						</div>
					</div>
				</td>
				<td>
				<h3>Cash Deposit</h3>

				</td>
				<td>
					<div class="cards-wrapper">
					<?php echo get_field('cash_transfer_content', 'option'); ?>
					</div>
				</td>
				</tr>
				</table>
				<?php }else{ ?>
				Cash Deposit content here...<br>
				Please login your account before you top up manually! 
				<?php } ?>
			</div>
			<div id="CreditList" class="tabcontent">
			<?php if(is_user_logged_in()){ ?>
				<div class="setcard-success"></div>
				<table border="0" class="">
				<tr>
				<td>
					<div class="card-wrapper">
						<div class="card-image">
							<img src="/wp-content/uploads/2016/09/card-type-icons.jpg">
						</div>
						<div class="ssl-image">
							<img src="/wp-content/uploads/2016/09/ssl-300x133.png">
						</div>
					</div>
				</td>
				<td>
					<h3>Add Card List</h3>
					<div id="cvc-close">
					</div>
					<form action="" method="POST" id="payment-formcheck">
					  <span class="creditcard-log-set"></span>
					  <span class="change-card-errors"></span>
					  <div class="form-row">
						<label>
						  <span>Card Holder Name</span>
						  <input type="text" size="20" data-stripe="name" class="card-name" placeholder="card holder" id="cardName">
						  <!--<input type="text" size="20" class="card-name" placeholder="card holder">--->
						</label>
					  </div>
					  <div class="form-row">
						<label>
						  <span>Card Number</span>
						  <input type="text" size="20" data-stripe="number" class="card-number" placeholder="card number" id="cardNumber">
						</label>
					  </div>

					  <div class="form-row">
						<label>
						  <span>Expiration (MM/YY)</span>
						  <input type="text" size="2" data-stripe="exp_month" class="expire-month" placeholder="month">
						</label>
						 / 
						<input type="text" size="2" data-stripe="exp_year" class="expire-month" placeholder="year">
					  </div>

					  <div class="form-row">
						<label>
						  <span>CVV2 <u><a class="cvc-button" href="#cvc-popup">(What's this?)</a></u> </span>
						  <input type="text" size="4" data-stripe="cvc" class="card-cvc" placeholder="cvv2" id="cardCvc">
						</label>
						<div id="cvc-popup" class="cvc-overlay">
							<div class="cvc-popup">
								<a class="cvc-close" href="#cvc-close">&times;</a>
								<div class="cvc-content">
									<img src="/wp-content/uploads/2016/09/cv_card.jpg">
								</div>
							</div>
						</div>
					  </div>
					  <input type="hidden" class="setpartner" value="<?php echo $manual;?>" id="setPartner">
					  <input type="submit" class="submit-card" value="Add Credit Card">
					</form>
					<script>
					jQuery(document).ready(function($){
						$(function() {
							Stripe.setPublishableKey('pk_test_u289X2Do4OavHR2STjbI2TsL');
							var $form = $('#payment-formcheck');
							$form.submit(function(event) {
								Stripe.card.createToken($form,function(status,response){
								console.log(status);
								console.log(response);  
								if(response.error){
									$form.find('.change-card-errors').text(response.error.message);
									$form.find('.submit').prop('disabled', true);
								}else{
									var token=response.id;
									var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
									$form.append($('<input type="hidden" name="stripe-token" id="stripeToken"/>').val(token));
									var stripeToken=$("#stripeToken").val();
									var setPartner=$("#setPartner").val();
									var cardName=$("#cardName").val();
									var cardNumber=$("#cardNumber").val();
									var cardCvc = $("#cardCvc").val();
									var creditsucc= confirm("Are you sure yo want to add this account?");
									if (creditsucc== true){
										$.ajax({
											type: "POST",
											url: ajaxurl,
											//timeout: 3000,
											data:{
											   action: "changeCard", 
											   setstripeToken :stripeToken,
											   setPartner :setPartner,
											   setcardNumber :cardNumber,
											   setcardCvc :cardCvc,
											   setcardName :cardName,
											},
											cache: false,
											success: function(result){
												//alert(result);
												//console.log(result);
												$(".creditcard-log-set").html(result); 
												window.top.location.reload();
											}
										});
									}else{
										
									}
								}
							});

							// Prevent the form from being submitted:
							return false;
						  });
						});
					});
					</script>
				</td>
				<td>
					<div class="cards-wrapper">
						<?php echo get_field('credit_debit_content', 'option'); ?>
					</div>
				</td>
				</tr>
				</table>
				<table border="0" style="width:100%;" class="view-cards">
					<tr>
						<td colspan="6">
							<h2>YOUR CURRENT CARDS</h2>
						</td>
					</tr>
					<tr>
						<th>
							Card List
						</th>
						<th>
							Card Name
						</th>
						<th>
							Card Number
						</th>
						<th>
							Card Type
						</th>
						<th>
							Expire
						</th>
						<th>
						   Manage
						</th>
					</tr>
					<?php
					if(!empty($manual)){
						try {
							//var_dump($manual);
							$results = $WP_CON->query("SELECT * FROM wp_settingscredit WHERE setting_partnerid =".$manual);
							//echo "Successful.";
						} catch (Exception $e) {
							echo "Error.";
							exit;
						}
						$resultCredit=$results->fetchAll(PDO::FETCH_ASSOC);
						//var_dump($resultCredit);
						foreach( $resultCredit as $result ){
							?>
							<tr>
								<td>
									› <?php echo $result['setting_primarycard'];?>
								</td>
								<td>
									<?php echo $result['setting_cardname'];?>
								</td>
								<td>
									<?php 
										$CARD_NUM=$result['setting_cardnumber'];
										echo str_repeat('*', strlen($CARD_NUM) - 4) . substr($CARD_NUM, -4); 
									?>
								</td>
								<td>
									<?php
									switch($result['setting_cardtype']){
										case 'Visa':
											$cardtype = get_stylesheet_directory_uri().'/images/card/card_visa.png';
										break;
										case 'MasterCard':
											$cardtype = get_stylesheet_directory_uri().'/images/card/master_card.png';
										break;
										case 'American Express':
											$cardtype = get_stylesheet_directory_uri().'/images/card/american_express_card.png';
										break;
										case 'Discover':
											$cardtype = get_stylesheet_directory_uri().'/images/card/discover_card.png';
										break;
										case 'Diners Club':
											$cardtype = get_stylesheet_directory_uri().'/images/card/diners_club.png';
										break;
										case 'JCB':
											$cardtype = get_stylesheet_directory_uri().'/images/card/jcb_card_payment.png';
										break;
										default: break;
									}
									?>
									<img src="<?php echo $cardtype; ?>">
								</td>
								<td>
									<?php echo $result['setting_expmonth'];?>/<?php echo $result['setting_expyear'];?>
								</td>
								<td>
								   <a class="set" href="" title="Set Primary">
										<img src="<?php echo get_stylesheet_directory_uri().'/images/key-card.png'; ?>">
								   </a>
								   <a title="Delete">
										<img src="<?php echo get_stylesheet_directory_uri().'/images/delete-card.png'; ?>">
								   </a>
								</td>
							</tr>
							<?php
						}
					}
					?>
				</table>
				<?php }else{ ?>
				Card content here...<br>
				Please login your account before you top up manually!
				<?php } ?>
			</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>