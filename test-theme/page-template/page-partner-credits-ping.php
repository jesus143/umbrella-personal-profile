<?php

    $partner_id = $_REQUEST['partner_id'];
    $service    = $_REQUEST['service'];
    $credits    = $_REQUEST['credits'];
    $action     = $_REQUEST['action'];

    global $wpdb_bb;
    $wpdb_bb = new wpdb( "dbo656995985", "umbrella1986", "db656995985", "db656995985.db.1and1.com" );

if($partner_id!="" && $credits!="" && $service!="" && $action!=""){

   if($action=='add'){

         $querystr = "SELECT * FROM partner_credits WHERE partner_id = $partner_id";
         $pageposts = $wpdb_bb->get_results($querystr);

         if(count($pageposts)>0){



            $inserted = 0;
            for($x=0;$x<count($pageposts);$x++){
                    if($pageposts[$x]->service == $_REQUEST['service']){
                        $total = 0;
                        $total = $pageposts[$x]->credits +  $credits;

                        $data = array(
                            'credits' => $total
                        );
                        $where = array(
                            'service' => $service
                        );


                        $wpdb_bb->update( 'partner_credits', $data, $where);
                        $inserted = 1;
                        echo "Saved Changes";
                        break;
                    }
            }

            if($inserted!=1){
                             $data = array(
            'partner_id' => $partner_id,
            'service' => $service,
            'credits' => $credits
             );

                $wpdb_bb->insert( 'partner_credits', $data, $format );
                echo "Saved Changes";


            }


         }

         else{


             $data = array(
            'partner_id' => $partner_id,
            'service' => $service,
            'credits' => $credits
             );

            $wpdb_bb->insert( 'partner_credits', $data, $format );
            echo "Inserted";
         }


    }

    elseif($action=='remove'){


        $querystr = "SELECT * FROM partner_credits WHERE partner_id = $partner_id";
         $pageposts = $wpdb_bb->get_results($querystr);

         if(count($pageposts)>0){



            $inserted = 0;
            for($x=0;$x<count($pageposts);$x++){
                    if($pageposts[$x]->service == $_REQUEST['service']){
                        echo "naaaaaaaaaa";
                        $total = 0;
                        $total = $pageposts[$x]->credits -  $credits;

                        $data = array(
                            'credits' => $total
                        );
                        $where = array(
                            'service' => $service
                        );


                        $wpdb_bb->update( 'partner_credits', $data, $where);
                        $inserted = 1;
                        echo "Saved Changes";
                        break;
                    }
            }

            if($inserted!=1){
                             $data = array(
            'partner_id' => $partner_id,
            'service' => $service,
            'credits' => $credits
             );

                $wpdb_bb->insert( 'partner_credits', $data, $format );
                echo "Saved Changes";


            }


         }

         else{


             $data = array(
            'partner_id' => $partner_id,
            'service' => $service,
            'credits' => $credits
             );

            $wpdb_bb->insert( 'partner_credits', $data, $format );
            echo "Inserted";
         }



    }
   elseif($action=='remove-all'){

         $querystr = "SELECT * FROM partner_credits WHERE partner_id = $partner_id";
         $pageposts = $wpdb_bb->get_results($querystr);

         if(count($pageposts)>0){



            $inserted = 0;
            for($x=0;$x<count($pageposts);$x++){
                    if($pageposts[$x]->service == $_REQUEST['service']){

                        $total = 0;

                        $data = array(
                            'credits' => $total
                        );
                        $where = array(
                            'service' => $service
                        );


                        $wpdb_bb->update( 'partner_credits', $data, $where);
                        $inserted = 1;
                        echo "Saved Changes";
                        break;
                    }
            }

            if($inserted!=1){
                             $data = array(
            'partner_id' => $partner_id,
            'service' => $service,
            'credits' => $credits
             );

                $wpdb_bb->insert( 'partner_credits', $data, $format );
                echo "Saved Changes";


            }


         }

         else{


             $data = array(
            'partner_id' => $partner_id,
            'service' => $service,
            'credits' => $credits
             );

            $wpdb_bb->insert( 'partner_credits', $data, $format );
            echo "Inserted";
         }


   }









?>