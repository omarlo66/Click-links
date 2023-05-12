<?php
include '../../options.php';
if(current_user() && current_user()['role'] != 'admin'){
    header('location:../../index.php');
    exit();
}
if(isset($_POST['title'])){
    $title = $_POST['title'];
    $g4 = $_POST['g_analytics'];
    $welcome_header = $_POST['welcome_header'];
    $welcome_content = $_POST['welcome_content'];
    $chat_widget = $_POST['chat_widget'];
    $footer = $_POST['footer'];
    $logo = $_POST['logo'];
    set_option('title', $title);
    set_option('google_analytics', $g4);
    set_option('welcome_header', $welcome_header);
    set_option('welcome_content', $welcome_content);
    set_option('logo', $logo);
    set_option('chat_widget', $chat_widget);
    set_option('footer', $footer);
    echo 'Updated Successfully';
}

if(isset($_POST['link_min_points'])){
    $link_min_points = $_POST['link_min_points'];
    set_option('min_points_per_link', $link_min_points);
    set_option('points_per_click', $_POST['point_per_click']);
    set_option('max_user_links', $_POST['max_user_links']);
    echo 'Updated Successfully';
}

if(isset($_GET['add_to_links'])){
    $add_to_links = $_GET['add_to_links'];
    $add_to_users = $_GET['add_to_users'];
    set_option('add_to_links', $add_to_links);
    set_option('add_to_users', $add_to_users);
    echo 'Updated Successfully';
}

if(isset($_GET['refferal'])){
    $ref_points = $_GET['ref_points'];
    $welcome_bonus = $_GET['new_ref_points'];
    set_option('ref_points', $ref_points);
    set_option('new_ref_points', $welcome_bonus);
    echo 'Updated Successfully';
}
?>