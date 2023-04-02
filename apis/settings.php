<?php

if(isset($_POST['option'])){
    include_once('../options.php');
    $option = $_POST['option'];
    $value = $_POST['value'];

    if(function_exists('set_option')){
        if(set_option($option,$value)){
            echo 'added successfully';
        }
        else{
            echo 'something went wrong';
        }
    }
    else{
        echo 'function not found';
    }
}

if(isset($_GET['get'])){
    include_once('../options.php');
    $option = $_GET['get'];

    if(function_exists('get_options')){
        if(get_options($option)){
            echo get_options($option);
        }
        else{
            echo 'something went wrong';
        }
    }
    else{
        echo 'function not found';
    }
}

?>