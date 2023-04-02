<?php

if(isset($_POST['username'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    include_once '../options.php';
    if(! function_exists('login')){
        $log = fopen('../log.txt','w');
        $date = date('h:m:s D/M/Y');
        fwrite($log,"login function not found\t$date\n");
        return array('status'=>false,'message'=>'technical issue');
    }
    if(login($username,$password)){
        echo 'success';
    }
    else{
        echo '<strong>wrong</strong> email or password if you are not registered <a href="register.php">register</a>';
    }
}

?>