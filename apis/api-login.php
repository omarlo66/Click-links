<?php

if(isset($_POST['username'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    include_once '../options.php';
    if(! function_exists('login')){
        $log = fopen('../log.txt','a');
        $date = date('h:m:s D/M/Y');
        fwrite($log,"login function not found\t$date\n");
        echo 'false';
    }else{
        $log = fopen('../log.txt','a');
        $date = date('h:m:s D/M/Y');
        fwrite($log,"login function found\t$date\n");
    }
    if(login($username,$password)){
        echo 'true';
    }
    else{
        echo 'false';
    }
}

?>