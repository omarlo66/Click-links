<?php
include_once '../../options.php';
if(current_user()['role'] != 'admin'){
    header('location:../index.php');
    exit();
}
if(isset($_POST['id'])){
    $id = $_POST['id'];
    
    if(! function_exists('approve_link_status')){
        $log = fopen('log.txt','w');
        $date = date('h:m:s D/M/Y');
        fwrite($log,"approve_link function not found\t$date");
        return array('status'=>false,'message'=>'technical issue');
    }
    if(approve_link_status($id)){
        echo 'success';
    }
    else{
        echo 'error';
    }
}
?>