<?php

if(isset($_POST['id'])){
    include_once('../options.php');
    $link_id = $_POST['id'];
    $user_id = current_user()['id'];
    add_traffic($link_id,$user_id);
    $link = get_link('link_id',$link_id);
    if($link){
        link_clicked($link_id);
        assign_click_to_user($user_id,$link_id);
        echo "success";
    }else{
        echo 'wrong pin code';
    }
    
    
    }
?>