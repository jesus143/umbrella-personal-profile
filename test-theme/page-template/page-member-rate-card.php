<?php get_header(); ?>
  <style>
    table, td, th {
    border: 1px solid #ddd;
    text-align: left;
    }

    table {
    border-color: black;
    width: 100%;
    border: 1px solid #ddd;
    background-color: #f2f2f2;
    }

    th, td {
    padding: 15px;
    }
  </style>
<div id="page-content">
  <?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>
  <h2>
    <?php the_title(); ?>
  </h2>

<?php

  require_once(TEMPLATEPATH."/class/class-ontraport-api.php");


   $id = '123';
   $partnerID=0;
if($_REQUEST['partner_id']>0){


  $partnerID = $_REQUEST['partner_id'];
  $data = unserialize(get_option( 'mlrc_partner_id_'.$partnerID ));
  $users_bonus_credits = array();

}




  if( isset( $_POST['btn-bonus'] ) ) {

    $users_bonus_credits = array();
    $allbonus = $_POST['bonuscredit'];

    $bonuscreditvalidity = $_POST['bonuscreditvalidity'];
    $data['bonus-credits']  =  $allbonus;
    $data['bonuscreditvalidity']  =  $bonuscreditvalidity;

    $users_bonus_credits['users_creditbonus']   =  $allbonus;
    $users_bonus_credits['bonuscreditvalidity'] =  $bonuscreditvalidity;
    $users_bonus_credits['users_creditbonus']   =  $allbonus;
    $users_bonus_credits['bonuscreditvalidity'] =  $bonuscreditvalidity;

    $storebonus = serialize($data);

    update_option( 'mlrc_partner_id_'.$owner[0]->id,$storebonus);
    update_option( 'mlrc_partner_id_log'.$owner[0]->id,$storebonus);
    $update_message = "Bonus Credits Updated";

    $storeussers = serialize($users_bonus_credits);
    update_option( 'users_bunos_credits'.$owner[0]->id,$storeussers);



  }


  ?>


<form method="post" action="" class="form-horizontal">
      <?php if(isset($update_message)) { ?>
        <div class="alert alert-success">
            <strong><?php echo $update_message; ?></strong>
        </div>
    <?php } ?><br>
  <table class="table table-bordered">
    <thead>
    <tr>
        <th colspan="4" ><center><h1 style="color: red; font-size: 30px;"><?php echo get_user_member_level(); ?> Package</h1></center></th>

      </tr>
      <tr>
        <th>Service</th>
        <th>Inclusive Credits</th>
        <th>Bonus Credits</th>
      </tr>
    </thead>
    <tbody>

  <?php
    $i = 0;
        if(is_array($data['service'])){
    foreach($data['service'] as $key => $value){ ?>
      <tr>
      <td><?php echo $data['service'][$i]; ?></td>
      <td><?php echo $data['credits'][$i]; ?></td>
      <td>
        <?php if($data['bonus-credits'][$i]!=""){
                echo $data['bonus-credits'][$i];
          }else{
          echo '0';
          }
         ?>

      </td>
      </tr>
     <?php $i++; }  ?>
       <?php } ?>
    </tbody>
  </table>
</form>


  <div style="clear:both"></div>
  <?php endwhile; ?>
  <?php else : ?>
  <h2 class="center">Not Found</h2>
  <p class="center">Sorry, but you are looking for something that isn't here.</p>
  <?php get_search_form(); ?>
  <?php endif; ?>

</div>
<?php get_footer(); ?>
