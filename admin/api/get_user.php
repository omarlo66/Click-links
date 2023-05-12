<?php
include_once '../../options.php';
if(! current_user() && ! current_user()['role'] == 'admin')
{
    header('location:../index.php');
    exit();
}

if(isset($_GET['id'])){
    $user = get_user($_GET['id']);
    if($user){
        echo json_encode($user);
    }
    else{
        echo 'user not found';
    }
}
?>