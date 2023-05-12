<?php
include_once('../options.php');
if(isset($_GET['link']) && current_user()){
    $user_id = current_user()['id'];
    $link = $_GET['link'];
    $link_id = str_replace(get_options('url').'go/', '', $link);
    $link = update_user_meta($user_id, 'next_link', $link_id);
    echo $link_id;
}
if(isset($_POST['link']) && isset($_COOKIE['user_id'])){
    $user_id = current_user()['id'];
    $link_id = $_POST['link_id'];
    $link = $_POST['link'];
    $source = $_POST['src'];
    $budget = $_POST['budget'];
    if(check_link_exists($link) || check_link_exists($source)){
        echo "link already exists";
        return;
    }

    }
    if($budget == ''){
        $budget = get_options('min_points_per_link');
        echo "budget is empty";
        return;
    }
    if($budget > user_points($user_id)){
        echo "budget is more than your points";
        return;
    }
    if(user_links_count($user_id) >= get_options('max_links_per_user')){
        echo "you have reached the maximum links per user";
        return;
    }
    
    $next_link = get_user_meta($user_id, 'next_link');
    delete_user_meta($user_id, 'next_link');
    if(function_exists('add_new_link')){
        if(add_new_link($link_id,$link,$source,$budget)){
            echo 'added successfully';
        }
        else{
            echo 'something went wrong';
            echo add_new_link($link_id,$link,$source,$budget);
        }
        $user_id = current_user()['id'];
        $ref = get_user_meta($user_id, 'add_link_bonus');
        if($ref){
            update_points($user_id, get_options('ref_points'));
            delete_user_meta($user_id, 'add_link_bonus');
        }
    }
    else{
        echo 'function not found';
    }

}else{
    header('location: ../login.php');
}
