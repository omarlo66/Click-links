<?php
include_once '../options.php';

if(isset($_GET['link'])){
    if(current_user() && current_user()['id'] != 0){
        $user_id = current_user()['id'];
        $next_link = get_user_meta($user_id, 'next_link');
        if($next_link || $next_link != ''){
            echo $next_link;
            return;
        }else{
            echo generate_unique_link_id();
            return;
        }
    }
    echo generate_unique_link_id();
    return;
}

?>