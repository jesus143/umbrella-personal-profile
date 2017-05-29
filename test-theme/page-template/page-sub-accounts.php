
<?php get_header('letterpage'); ?>


<style type="text/css">

    #sub_accounts_container #sub_acount_modal .modal-title {
        margin-bottom: 0;
        margin-top: 2px;
    }

	#sub_accounts_container .modal-dialog {
		top: 40px;
		margin-left: -220px;
	}
	#customdal{
		border: none;
	}

    #sub_accounts_container .radio {
        margin-right: 15px;
    }

    #sub_accounts_container .radio label input {
        margin-left: 8px;
    }

    #sub_accounts_container .form-control {
        height: auto;
    }

    #sub_accounts_container .input_labels {
        font-size: 13px;
    }

    #user_details_table {
        margin-top: 20px;
    }

	#sub_accounts_container .tab-content > .tab-pane {
		display: inherit !important;
	}

    .page_data_area {
        height: 300px;
        overflow-y: auto;
        margin-bottom: 50px;
    }

    .page_data_area .checkbox-inline {
        font-size: 13px;
    }
	.btn-cus-nel{
		background-color: #4CAF50;
		border: none;
		color: white;
		padding: 9px 28px;
		text-align: center;
		text-decoration: none;
		display: inline-block;
		font-size: 16px;
		margin: 4px 2px;
		cursor: pointer;
	}

</style>


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

<?php

function neldel() {

    wp_die();
}


$current_user_id = get_current_user_id();

if( isset( $_POST['create_sub_user'] ) ) {

    $firstname          = $_POST['firstname'];
    $lastname           = $_POST['lastname'];
    $mobile_number      = $_POST['mobile_number'];
    $email              = $_POST['email_address'];
    $password           = $_POST['password'];
    $repeat_password    = $_POST['repeat_password'];
    $role               = $_POST['userrole'];
    $user_role 			= '';

	$user_role = $role;



    if( empty( $firstname ) ) {
        $error = "Please enter Firstname";
    }
    else if( empty( $lastname ) ) {
        $error = "Please enter Lastname";
    }
    else if( empty( $mobile_number ) ) {
        $error = "Please enter mobile number";
    }
    else if( empty( $email ) ) {
        $error = "Please enter Email Address";
    }
    else if( empty( $password ) ) {
        $error = "Please enter password";
    }
    else if( empty( $repeat_password ) ) {
        $error = "Please repeat your password";
    }
    else if( empty( $role ) ) {
        $error = "Please select Account role";
    }

    else {

        if( $password != $repeat_password ) {

            echo "Password mismatch";

        } else {

			$mobile_number_field = 'mobile_number';
            $sub_account_owner = 'sub_account_owner_id';
			//$single = true;

            $user_id = wp_insert_user(
                array(
                'user_login'        => $email,
                'user_pass'         => $password,
                'user_email'        => $email,
                'first_name'        => $firstname,
                'last_name'         => $lastname,
                'user_registered'   => date('Y-m-d H:i:s'),
                'role'              => $user_role
                )
            );

			update_user_meta( $user_id, $mobile_number_field, $mobile_number );
            update_user_meta( $user_id, $sub_account_owner, $current_user_id );
			update_user_meta( $user_id, $sub_account_owner, $current_user_id );

			$user_mobile_data = get_user_meta($user_id, $mobile_number_field );
            $sub_account_owner_data = get_user_meta( $user_id, $sub_account_owner );

			// echo "<pre>";
			// print_r($sub_account_owner_data);
			// echo "</pre>";

			echo "Inserted";

			/*
            if ( is_wp_error( $user_id ) ) {
                $user_id->get_error_message();
            } else {
                unset( $_POST['create_sub_user'] );

                foreach ($_POST as $key => $value) {
                    update_user_meta( $user_id, $key, $value );
                }

                echo "Inserted";
            }
			*/

        }

    }

    //print_r($role);

    // print_r($user_id);

}


if( isset( $_POST['save_user_login_pages'] ) ) {

    $user_page_id = $_POST['user_login_page'];

    update_user_meta( $current_user_id, 'user_login_page_id', $user_page_id);

    echo "inserted";

    $test_meta_data = get_user_meta($current_user_id,'user_login_page_id',true);

    // echo "<pre>";
    // print_r( $test_meta_data );
    // echo "</pre>";

}

if( isset( $_POST['save_admin_login_pages'] ) ) {

    $user_page_id = $_POST['admin_login_page'];

    update_user_meta( $current_user_id, 'admin_login_page_id', $user_page_id );

    echo "inserted";

    $test_meta_data = get_user_meta($current_user_id);

    // echo "<pre>";
    // print_r( $test_meta_data );
    // echo "</pre>";

}

if( isset( $_POST['save_custom_login_pages'] ) ) {

    $user_page_id = $_POST['custom_login_page'];

    update_user_meta( $current_user_id, 'custom_login_page_id', $user_page_id);

    $update_message = "Custom login permission updated!";

    $test_meta_data = get_user_meta($current_user_id,'custom_login_page_id',true);

    // echo "<pre>";
    // print_r( $update_user_meta );
    // echo "</pre>";

}
if(isset($_POST['login-user'])){
	$user_login_id = $_POST['user_login_page'];
	update_user_meta( $current_user_id, 'user_login_page_id', $user_login_id);

	$update_message = "User login permission updated!";

	$test_meta_data = get_user_meta($current_user_id,'user_login_page_id',true);

    // echo "<pre>";
    // print_r( $test_meta_data );
    // echo "</pre>";


}

$custom_user_page_id = get_user_meta($current_user_id,'custom_login_page_id',true);
/*
if(isset($_POST['clickdel'])){
	$delId = $_POST['clickdel'];
	echo  $delId;
}*/
?>


<div id="sub_accounts_container">

    <?php if(isset($update_message)) { ?>
        <div class="alert alert-success">
            <strong><?php echo $update_message; ?></strong>
        </div>
    <?php } ?>

    <?php

    if( isset( $error ) ) {
        echo $error;
    }

    ?>

    <?php

    $all_user_data = new WP_User_Query( array(
                                    'meta_key' => $sub_account_owner,
                                    'meta_value' => $current_user_id
                                    ) );

    ?>

    <form method="post" action="" class="form-horizontal">
        <div class="row">
            <div class="form-group col-sm-5" style="margin-right: 40px;">
                <label for="firstname" class="input_labels">Firstname:</label>
                <input type="text" class="form-control" id="firstname" placeholder="Enter firstname" name="firstname" value="<?php echo $firstname; ?>">
            </div>
            <div class="form-group col-sm-5" style="margin-right: 40px;">
                <label for="lastname" class="input_labels">Lastname:</label>
                <input type="text" class="form-control" id="lastname" placeholder="Enter Lastname" name="lastname" value="<?php echo $lastname; ?>">
            </div>
            <div class="form-group col-sm-5" style="margin-right: 40px;">
                <label for="mobile_number" class="input_labels">Mobile Number:</label>
                <input type="text" class="form-control" id="mobile_number" placeholder="Enter mobile number" name="mobile_number" value="<?php echo $mobile_number; ?>">
            </div>
            <div class="form-group col-sm-5" style="margin-right: 40px;">
                <label for="email_address" class="input_labels">Email Address:</label>
                <input type="email" class="form-control" id="email_address" placeholder="Enter email address" name="email_address" value="<?php echo $email; ?>">
            </div>
            <div class="form-group col-sm-5" style="margin-right: 40px;">
                <label for="password" class="input_labels">Password:</label>
                <input type="password" class="form-control" id="pwd" placeholder="Enter pasword" name="password">
            </div>
            <div class="form-group col-sm-5" style="margin-right: 40px;">
                <label for="repeat_password" class="input_labels">Repeat Password:</label>
                <input type="password" class="form-control" id="repeat_password" placeholder="Repeat pasword" name="repeat_password">
            </div>
        </div>

        <div class="form-group" style="margin-left: 5px;">
            <p class="lead">What Kind of Sub Account Would you like to Setup?</p>
            <label style="margin-right: 10px;"><input type="radio" name="userrole" value="user_login">&nbsp;&nbsp;User Login</label>
            <label style="margin-right: 10px;"><input type="radio" name="userrole" value="admin_login">&nbsp;&nbsp;Admin Login</label>
            <label style="margin-right: 10px;"><input type="radio" name="userrole" value="custom_login">&nbsp;&nbsp;Custom Login</label>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" name="create_sub_user">Create Sub Account Now</button>
            </div>
        </div>
    </form>


    <div id="user_details_table">

		<h3>Sub Accounts list</h3>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Access Level</th>
                    <th>Email Address</th>
                    <th>password</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($all_user_data->results as $fields => $val) {
                $sub_account_id = $val->ID;

                $meta_data = get_user_meta($sub_account_id);

                $get_user_role_data = get_userdata( $sub_account_id ); //get all user role data
                $user_role_data = $get_user_role_data->roles[0];

				$user_meta_firstname = $meta_data['first_name'][0];
                $user_meta_lastname = $meta_data['last_name'][0];
				$user_mobile = $meta_data['mobile_number'][0];
				$user_email_data = $get_user_role_data->data->user_email;
				$user_password_data = $get_user_role_data->data->user_pass;
				//strtotime(date("Y-m-d", 1310571061));

				// echo "<pre>";
				// print_r($meta_data);
				// echo "</pre>";

				$password = str_repeat("*", strlen($user_password_data));
            ?>
                <tr>
                    <td><label class="fname<?php echo $val->ID; ?>" ><?php echo $user_meta_firstname; ?></label></td>
                    <td><label class="lname<?php echo $val->ID; ?>"><?php echo $user_meta_lastname; ?></label></td>
                    <td><label class="role<?php echo $val->ID; ?>"><?php echo $user_role_data; ?></label></td>
					<td><label class="emailUser<?php echo $val->ID; ?>"><?php echo $user_email_data; ?></label><br><small class="mobilePhone<?php echo $val->ID; ?>"><?php echo $user_mobile ?></small></td>
					<td><label class="pass<?php echo $val->ID; ?>"><?php echo $password; ?></label></td>
					<td>
						<button class="clickUp" id="<?php echo $val->ID; ?>" value="<?php echo $val->ID; ?>" data-toggle="modal" data-target="#edit_form_sub_account"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>&nbsp;
						<button class="clickdel" id="<?php echo $val->ID; ?>" value="<?php echo $val->ID; ?>") name="clickdel" ><i class="fa fa-trash" aria-hidden="true"></i></button>
					</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

    </div>


	 <div class="modal fade" id="edit_form_sub_account" role="dialog" style="z-index: 3500;">
		<div class="modal-dialog">

		  <!-- Modal content-->
		  <div class="modal-content" id="customdal">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title">Edit Account</h4>
			</div>
			<div class="modal-body">


			  <div style="padding-right: 1em;">

					<div class="row">
						<div class="form-group col-sm-12" style="margin-right: 40px;">
							<label for="firstname" class="input_labels">Firstname:</label>
							<input type="text" class="form-control" id="firstnameUp" placeholder="Enter firstname" name="firstnameUp" value="">
						</div>
						<div class="form-group col-sm-12" style="margin-right: 40px;">
							<label for="lastname" class="input_labels">Lastname:</label>
							<input type="text" class="form-control" id="lastnameUp" placeholder="Enter Lastname" name="lastnameUp" value="">
						</div>
						<div class="form-group col-sm-12" style="margin-right: 40px;">
							<label for="mobile_number" class="input_labels">Mobile Number:</label>
							<input type="text" class="form-control" id="mobile_numberUp" placeholder="Enter mobile number" name="mobile_numberUp" value="">
						</div>
						<div class="form-group col-sm-12" style="margin-right: 40px;">
							<label for="email_address" class="input_labels">Email Address:</label>
							<input type="email" class="form-control" id="email_addressUp" placeholder="Enter email address" name="email_addressUp" value="">
						</div>
						<div class="form-group col-sm-12" style="margin-right: 40px;">
							<label for="password" class="input_labels">Password:</label>
							<input type="password" class="form-control" id="pwdUp" placeholder="Enter pasword" name="passwordUp">
						</div>
						<div class="form-group col-sm-12" style="margin-right: 40px;">
							<label for="repeat_password" class="input_labels">Repeat Password:</label>
							<input type="password" class="form-control" id="repeat_passwordUp" placeholder="Repeat pasword" name="repeat_passwordUp">
						</div>
					</div>

					<div class="form-group" style="margin-left: 5px;">
						<p class="lead">What Kind of Sub Account Would you like to Setup?</p>
						<label style="margin-right: 10px;"><input type="radio" name="userroleUp" class="userRole userroleUp" value="user_login">&nbsp;&nbsp;User Login</label>
						<label style="margin-right: 10px;"><input type="radio" name="userroleUp" class="userAdmin userroleUp" value="admin_login">&nbsp;&nbsp;Admin Login</label>
						<label style="margin-right: 10px;"><input type="radio" name="userroleUp" class="usercustom userroleUp" value="custom_login">&nbsp;&nbsp;Custom Login</label>
					</div>

					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="button" class="clickUpdate" name="createUp">Create Sub Account Now</button>
						</div>
					</div>

			 </div>

			</div>
			<div class="modal-footer">
			  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		  </div>

		</div>
	  </div>







        <div style="margin-top: 50px; margin-bottom: 50px;">
        <h3>Custom Login</h3>
        <p>List of Pages</p>
        <form method="post" action="">
            <div class="page_data_area">
                <div class="row" style="margin-top: 25px; margin-bottom: 25px;">

                        <?php

                        $pages_for_custom_user = get_pages();






                        foreach ($pages_for_custom_user as $page_key => $page_value) {
                            // echo "<pre>";
                            // print_r( $page_value );
                            // echo "</pre>";
                            $custom_user_page_id_data = $page_value->ID;
                            $custom_user_page_title_data = $page_value->post_title;


                        ?>

							<?php if($custom_user_page_title_data!=""){ ?>
								<div class="col-sm-5">
									<label class="checkbox-inline check-cus"><input  type="checkbox" style="margin-top: 2.9px;" value="<?php echo $custom_user_page_id_data; ?>" name="custom_login_page[]" <?php if(is_array($custom_user_page_id)){ if( in_array($custom_user_page_id_data, $custom_user_page_id)){ echo "checked"; }} ?>> <span > <?php echo $custom_user_page_title_data; ?> </span></label>
								</div>
							<?php } ?>
                        <?php } ?>

                </div>
            </div>
            <button type="submit" class="btn-cus-nel" name="save_custom_login_pages">Save</button>
        </form>
        </div>


	 <div style="margin-top: 50px; margin-bottom: 50px;">
        <h3>UsersLogin</h3>
        <p>List of Pages</p>
        <form method="post" action="">
            <div class="page_data_area">
                <div class="row" style="margin-top: 25px; margin-bottom: 25px;">

                        <?php

								$pages_for_user_login = get_pages(array(
								   'number' =>'10'
								));

								foreach ($pages_for_user_login as $page_key => $page_value) {
									// echo "<pre>";
									// print_r( $page_value );
									// echo "</pre>";
									$custom_user_page_id_data = $page_value->ID;
									$custom_user_page_title_data = $page_value->post_title;

                        ?>

							<?php if($custom_user_page_title_data!=""){ ?>
									<div class="col-sm-5">
										<label class="checkbox-inline check-cus"><input type="checkbox" style="margin-top: 2.9px;" value="<?php echo $custom_user_page_id_data; ?>" name="user_login_page[]" <?php if(is_array($custom_user_page_id)){ if( in_array($custom_user_page_id_data, $custom_user_page_id)){ echo "checked"; }} ?>><?php echo $custom_user_page_title_data; ?></label>
									</div>
							<?php } ?>
                        <?php } ?>

                </div>
				 <button type="submit" class="btn-cus-nel" name="login-user" >Save</button>
            </div>

        </form>
        </div>




    </div>




<div style="clear:both"></div>
<?php endwhile; ?>
<?php else : ?>
<h2 class="center">Not Found</h2>
<p class="center">Sorry, but you are looking for something that isn't here.</p>
<?php get_search_form(); ?>
<?php endif; ?>
</div>

<script type="text/javascript">
$(document).ready(function(){
	$('.clickdel').click(function(){
		var del_id = $(this).attr('id');
		var parent = $(this).parent("td").parent("tr");
		var urls =  "<?php echo admin_url('admin-ajax.php'); ?>";
		if(confirm('Sure to Delete ID no = ' +del_id))
		{
			parent.fadeOut(500);

			$.ajax({
				type: 'POST',
				url: urls,
				data:{
					id: del_id,
					action:'adddelfunction'
				},
				success: function(datus){
					alert(datus);

				}
			});

			/*$.post(
				urls,
				{
					'action': 'adddelfunction',
					'id': del_id
				},
				function(response){

					alert(response)
				}
			);*/

		}
		else{}

	});


	$('.clickUp').click(function(){
		var UpId = $(this).attr('id');
		var fname 		=  $('.fname'+UpId+'').html();
		var lname		=  $('.lname'+UpId+'').html();
		var role 		=  $('.role'+UpId+'').html();
		var emailUser   =  $('.emailUser'+UpId+'').html();
		var phone 		=  $('.mobilePhone'+UpId+'').html();
		var pass		=  $('.pass'+UpId+'').html();

		$('#firstnameUp').val(fname);
		$('#lastnameUp').val(lname);
		$('#mobile_numberUp').val(phone);
		$('#email_addressUp').val(emailUser);
		$('#pwdUp').val(pass);
		$('#repeat_passwordUp').val(pass);
		var finalRole = role;

		if(finalRole=='user_login'){
			$('.userRole').prop('checked',true);
			$finalRole="user_login";
		}
		else if(finalRole=='custom_login'){
			$('.usercustom').prop('checked',true);
			$finalRole="custom_login";
		}
		else if(finalRole=='admin_login'){
			$('.userAdmin').prop('checked',true);
			$finalRole="admin_login";
		}




		var idUp = UpId;


			$('.clickUpdate').on('click', function(){

					 var fname_final 	 = $('#firstnameUp').val();
					 var lname_final 	 = $('#lastnameUp').val();
					 var role_final 	 = $('input[name=userroleUp]:checked').val();
					 var emailUser_final = $('#email_addressUp').val();
					 var mobileNo		 = $('#mobile_numberUp').val();
					 var pass_final 	 = $('#pwdUp').val();
					 var repeat_final  	 = $('#repeat_passwordUp').val();

					alert(role_final);


					var repeatP = $('#repeat_passwordUp').val();
					var passwords = $('#pwdUp').val();
					var urlUp =  "<?php echo admin_url('admin-ajax.php'); ?>";
					if(confirm('Sure to Edit ID no = ' +idUp))
					{
						if(passwords==repeatP){

							$.ajax({
								type: 'POST',
								url: urlUp,
								data:{
									id: idUp,
									fname: fname_final,
									lname: lname_final,
									role: role_final,
									emailUser: emailUser_final,
									pass: pass_final,
									mobileNo: mobileNo,
									action:'addUpdatefunction'
								},
								success: function(data){
									alert(data);
								}

							});

						}
						else{
							alert('Password Invalid');
						}

					}
				})




	})


	// var checkUser = [];
	// $('#check-user-login:checked').each(function(i){
		// checkUser[i] = $(this).val();

	// })

	// $('#login-user-check').click(function(){
		// var urlUser =  "<?php echo admin_url('admin-ajax.php'); ?>";
		// var nelson = "nelson";

		// $.ajax({
			// type: 'POST',
			// url: urlUser,
			// data:{
				// id: checkUser,
				// action:'addUserlogin'
			// },
			// success: function(data){
				// alert(data);
			// }

		// });

		// for(var i=0;i<checkUser.length;i++){
			// alert(checkUser[i]);
		// }


	// })




});



</script>

<?php get_footer(); ?>
