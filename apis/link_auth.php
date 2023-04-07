<?php

if(isset($_POST['id'])){
    include_once('../options.php');
    $link_id = $_POST['id'];
    $user_id = current_user()['id'];
    //add_traffic($link_id,$user_id);
    $link = get_link('link_id',$link_id);

    if($link){
        //Link data
        $p1 = link_clicked($link_id);

        //Link Publisher
        //Deduct point from user wallet
        $p2 = insert_user_wallet($link->author,$link->points_per_click,$link->link_id);
        
        //Link Clicker
        //insert points to user wallet
        $p3 = assign_click_to_user($user_id,$link_id);
        echo 'success';
    }else{
        echo 'wrong pin code';
    }
    
    
    }
?>