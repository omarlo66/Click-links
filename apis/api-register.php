<?php

if(isset($_POST['username'])){
    include_once '../options.php';
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    if(function_exists('register')){
        if(register($username,$email,$password)){
            echo 'success';
        }
        else{
            echo 'failed';
        }
    }
    else{
        $log = fopen('../log.txt','w');
        $date = date('h:m:s D/M/Y');
        fwrite($log,"register function not found\t$date\n");
        echo 'There is error right now please try again later or contact us';
    }
}


?>