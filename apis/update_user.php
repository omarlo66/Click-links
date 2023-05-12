<?php

if(isset($_POST['username'])){
    include_once('../options.php');
    $user_id = current_user()['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    if($password == ''){
        $password = current_user()['password'];
    }
    if(function_exists('update_user')){
        if(update_user($user_id,$username,$password,$email)){
            echo 'updated successfully';
        }
        else{
            echo 'username is already used try to change it!';
            }
        }
    }

?>