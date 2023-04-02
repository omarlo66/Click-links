<?php
include '../../options.php';
global $sql;
if(current_user()['role'] != 'admin'){
    header('location:../index.php');
    exit();
}
$query = $sql->query("SELECT * FROM links");
echo json_encode($query->fetch_all());
?>