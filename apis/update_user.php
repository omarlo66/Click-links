<?php

if(isset($_POST['username'])){
    include_once('../options.php');
    $user_id = $_COOKIE['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if(function_exists('update_user')){
        
        
        if(update_user($user_id,$username,$password,$email)){
            echo 'updated successfully';
        }
        else{
            echo 'user is already registered';
            
            }
        }
    }

?>