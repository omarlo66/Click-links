<?php
include_once '../../options.php';
if(current_user()['role'] != 'admin'){
    header('location:../index.php');
    exit();
}
if(isset($_POST['new_ad'])){
    $ad1 = $_POST['ad_1'];
    $ad2 = $_POST['ad_2'];
    $ad3 = $_POST['ad_3'];
    $ad4 = $_POST['ad_4'];
    set_option('ad_1',$ad1);
    set_option('ad_2',$ad2);
    set_option('ad_3',$ad3);
    set_option('ad_4',$ad4);
    echo 'true';
}
?>