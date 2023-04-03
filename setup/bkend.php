<?php
include_once 'options.php';

function create_options_table(){
    $table = "CREATE TABLE options (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `key` VARCHAR(255) NOT NULL,
        value VARCHAR(255) NOT NULL
    )";
    return $table;
}
function create_links_table(){
    $links_table ="CREATE TABLE links (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        link_id VARCHAR(255) NOT NULL,
        link VARCHAR(255) NOT NULL,
        source VARCHAR(255) NOT NULL,
        author INT(6) NOT NULL,
        points_per_click INT(6) NOT NULL,
        budget INT(6) NOT NULL,
        points INT(6) NOT NULL,
        clicks INT(6) NOT NULL,
        status VARCHAR(255) NOT NULL,
        date VARCHAR(255) NOT NULL
    )";
}
function create_table_wallet(){
    $wallet_table =  "CREATE TABLE wallet (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) NOT NULL,
        amount INT(6) NOT NULL,
        title VARCHAR(255) NOT NULL,
        date VARCHAR(255) NOT NULL
    )" ;
    return $wallet_table;
}
function create_users_table(){
    $users_table =  "CREATE TABLE users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        role VARCHAR(255) NOT NULL,
        date VARCHAR(255) NOT NULL
    )" ;
    return $users_table;
}
function create_traffic_table(){
    $traffic_table =  "CREATE TABLE traffic (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) NOT NULL,
        link_id INT(6) NOT NULL,
        ip_address VARCHAR(255) NOT NULL,
        date VARCHAR(255) NOT NULL
    )" ;
    return $traffic_table;
}
function create_table_usermeta(){
    $usermeta_table =  "CREATE TABLE usermeta (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) NOT NULL,
        meta_key VARCHAR(255) NOT NULL,
        meta_value VARCHAR(255) NOT NULL
    )" ;
    return $usermeta_table;
}
function create_table_pages(){
    $pages_table =  "CREATE TABLE pages (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        content TEXT NOT NULL,
        date VARCHAR(255) NOT NULL
    )" ;
    return  $pages_table;
}



?>