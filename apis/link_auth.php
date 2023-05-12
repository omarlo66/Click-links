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
        
        //Save traffic
        $p3 = update_link_meta($link_id,'u_click_time_'.$user_id,serialize([$user_id,time()]));

        //insert points to user wallet
        $p4 = assign_click_to_user($user_id,$link_id);
        
        echo 'success';
    }else{
        echo 'wrong pin code';
    }
}
?>