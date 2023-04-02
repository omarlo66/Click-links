<?php

if(isset($_POST['title'])){
    $title = $_POST['title'];
    $welcome_header = $_POST['welcome_header'];
    $welcome_content = $_POST['welcome_content'];
    $logo = $_POST['logo'];
    require '../options.php';
    set_option('title', $title);
    set_option('welcome_header', $welcome_header);
    set_option('welcome_content', $welcome_content);
    set_option('logo', $logo);
    echo 'Updated Successfully';
}

?>