<?php
include_once '../options.php';
if(current_user()->role != 'admin'){
    header('location:../index.php');
    exit();
}
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $sql->query("DELETE FROM links WHERE id = $id");
    echo "deleted succefully";
}

?>