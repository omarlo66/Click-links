<?php
include_once '../../options.php';
if(current_user() && current_user()['role'] != 'admin'){
    header('location:../index.php');
    exit();
}
if(isset($_POST['id'])){
    $id = $_POST['id'];
    if(delete_link_status($id)){
        echo 'success';
    }else{
        echo 'error';
    }
}

?>