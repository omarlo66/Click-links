<?php
include_once '../../options.php';
if(current_user() && current_user()['role'] != 'admin'){
    header('location:../index.php');
    exit();
}
if(isset($_GET['id'])){
    $id = $_GET['id'];
    delete_link_status($id);
}

?>