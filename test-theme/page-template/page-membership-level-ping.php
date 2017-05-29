<?php
$getall = $_REQUEST;
$password='umbrella199715';
/**/


$newCredit = $_REQUEST['credits'];
$newservice = $_REQUEST['service'];
$newagent = $_REQUEST['agent'];
$newaction = $_REQUEST['action'];
$newbonus = $_REQUEST['bonus'];
$newpass = $_REQUEST['password'];

$newdate =  current_time( 'mysql' );
$date =  current_time( 'mysql' );
$mlrc_data = unserialize(get_option( 'bonus_credit_ping_'.$_REQUEST['partner_id'] ));
$logdata = unserialize(get_option( 'bonus_credit_ping_log'.$_REQUEST['partner_id'] ));

if(is_array($mlrc_data) && !empty($mlrc_data)){

        if($_REQUEST['credits']!="" && $_REQUEST['service']!="" && $_REQUEST['agent']!="" && $_REQUEST['password']=='umbrella199715'){

            if($_REQUEST['action']=="add"){

                if(in_array($_REQUEST['service'], $mlrc_data['service'])){





                    $key = array_search($_REQUEST['service'], $mlrc_data['service']);

                    if (count($mlrc_data['history_bonus_date'])==0){

                            $mlrc_data['history_bonus_date'] = array(
                                    $newservice => $mlrc_data['date'][$key]
                                );

                                $mlrc_data['history_bonus'] = array(
                                    $newservice => $mlrc_data['credits'][$key]
                                );


                    }else{

                        array_push( $mlrc_data['history_bonus_date'],$mlrc_data['history_bonus_date'] = array(
                                    $newservice => $mlrc_data['date'][$key]
                                ););
                        array_push( $mlrc_data['history_bonus'], $mlrc_data['history_bonus'] = array(
                                    $newservice => $mlrc_data['credits'][$key]
                                ););

                    }





                    $mlrc_data['credits'][$key] =  $mlrc_data['credits'][$key] + $newCredit;

                    $logdata['service'][$newservice] = $newservice;
                    $logdata['credits'][$newservice] = $newCredit;
                    $logdata['agent'][] = $newagent;
                    $logdata['date'][$newservice]    = $date;
                    $logdata['action'][$newservice] = $_REQUEST['action'];
                    $logdata['datainfo'][$newservice][] = array('name'=>$newagent,'addedCredit'=>$newCredit,'action'=>$newaction,'date'=>$date);




                }else{

                    $logdata['service'][$newservice] = $newservice;
                    $logdata['credits'][$newservice] = $newCredit;
                    $logdata['credits'][$newservice] = $newCredit;
                    $logdata['date'][$newservice]    = $date;
                    $logdata['bunos'][$newservice]   = $newbonus;

                    $logdata['action'][$newservice]  = $_REQUEST['action'];
                    $logdata['datainfo'][$newservice][] = array('name'=>$newagent,'addedCredit'=>$newCredit,'action'=>$newaction,'date'=>$date);

                    $mlrc_data['service'][$newservice] = $newservice;
                    $mlrc_data['credits'][$newservice] = $newCredit;
                    $mlrc_data['agent'][] = $newagent;
                    $mlrc_data['date'][$newservice] = $date;
                    $mlrc_data['bonus'][$newservice] = $newbonus;
                    $mlrc_data['action'][$newservice] = $_REQUEST['action'];
                    $mlrc_data['datainfo'][$newservice][] = array('name'=>$newagent,'addedCredit'=>$newCredit,'action'=>$newaction,'date'=>$date);

                }


                insert_logs(serialize($logdata));
                insert_ping(serialize($mlrc_data));


            }elseif($_REQUEST['action']=="remove"){

                    $key_minus = array_search($_REQUEST['service'], $mlrc_data['service']);

                    $mlrc_data['credits'][$key_minus] =  $mlrc_data['credits'][$key_minus] - $newCredit;
                    $mlrc_data['datainfo'][$newservice][] = array('name'=>$newagent,'addedCredit'=>$newCredit,'action'=>$newaction,'date'=>$date);

                    $logdata['service'][$newservice] = $newservice;
                    $logdata['credits'][$newservice] = $newCredit;
                    $logdata['agent'][] = $newagent;
                    $logdata['bunos'][$newservice]    = $newbonus;

                    $logdata['action'][$newservice] = $_REQUEST['action'];
                    $logdata['datainfo'][$newservice][] = array('name'=>$newagent,'addedCredit'=>$newCredit,'action'=>$newaction,'date'=>$date);

                    insert_logs(serialize($logdata));
                    insert_ping(serialize($mlrc_data));
                    echo "Mminu";
            }
            elseif($_REQUEST['action']=="remove-all"){

                    foreach ($mlrc_data['service'] as $key => $value) {
                        $mlrc_data['credits'][$key] = "";
                    }

                    $logdata['service'][$newservice] = $newservice;
                    $logdata['credits'][$newservice] = $newCredit;
                    $logdata['agent'][] = $newagent;
                    $logdata['date'][$newservice]    = $date;
                    $logdata['bunos'][$newservice]    = $newbonus;
                    $logdata['action'][$newservice] = $_REQUEST['action'];

                    insert_logs(serialize($logdata));
                    insert_ping(serialize($mlrc_data));

            }

        }else{

            echo "Invalid  Data";
       }

 }else{

    if($_REQUEST['credits']!="" && $_REQUEST['service']!="" && $_REQUEST['agent']!="" && $_REQUEST['password']=='umbrella199715'){



        $mlrc_data['credits'] = array(
            $newservice => $newCredit
        );
        $mlrc_data['service'] = array(
            $newservice => $newservice
        );
        $mlrc_data['agent'] = array(
            $newagent
        );
        $mlrc_data['action'] = array(
            $newservice => $newaction
        );
        $mlrc_data['date'] = array(
            $newservice => $date
        );
        $mlrc_data['bonus'] = array(
            $newservice => $newbonus
        );
        $mlrc_data['history_bonus_date'] = array(
        );
        $mlrc_data['history_bonus'] = array(
        );




        $mlrc_data['datainfo'][$newservice][] = array('name'=>$newagent,'addedCredit'=>$newCredit,'action'=>$newaction,'date'=>$date);

        $mlrc_data['password'] = $newpass;
        $storeping = serialize($mlrc_data);

        insert_logs($storeping);
        insert_ping($storeping);


    }

}






function insert_logs($data){
     echo "Log Added.<br />";
    update_option( 'bonus_credit_ping_log'.$_REQUEST['partner_id'],$data);

        // echo '<pre>';
        // print_r( unserialize(get_option( 'bonus_credit_ping_log'.$_REQUEST['partner_id'])));
        // echo '</pre>';

}

function insert_ping($data){
        echo "Bonus Credit Added";

        update_option( 'bonus_credit_ping_'.$_REQUEST['partner_id'],$data);

        // echo '<pre>';
        // print_r( unserialize(get_option( 'bonus_credit_ping_'.$_REQUEST['partner_id'])));
        // echo '</pre>';

}


    echo '<pre>';
    print_r($mlrc_data);
    echo '</pre>';


// •    partner_DDDDDDDDDDDDDDDDDffffDd
// •    action
// •    service //array
// •    creditssssss // array
// •    password
// •    agent  // array

?>