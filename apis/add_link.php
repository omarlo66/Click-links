<?php


if(isset($_POST['link']) && isset($_COOKIE['user_id'])){
    include_once('../options.php');
    $link_id = $_POST['link_id'];
    $link = $_POST['link'];
    $source = $_POST['src'];
    $budget = $_POST['budget'];

    if(function_exists('add_new_link')){
        if(add_new_link($link_id,$link,$source,$budget)){
            echo 'added successfully';
        }
        else{
            echo 'something went wrong';
        }
    }
    else{
        echo 'function not found';
    }

}else{
    header('location: ../login.php');
}
