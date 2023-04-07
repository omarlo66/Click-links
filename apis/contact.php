<?php

include '../options.php';
if(isset($_GET['get'])){
    if(current_user()['id'] != 0){
    $user_id = current_user()['id'];
    }else{
        return null;
    }
    $msgs = get_user_messages($user_id);
    echo json_encode($msgs);
}

if(isset($_POST['delete'])){
    $id = $_POST['delete'];
    $auth = $_POST['name'];
    if($auth != current_user()['name']){
        echo 'error';
        return ;
    }
    delete_message($id);
    echo 'success';
    return ;
}

if(isset($_POST['name']) && isset($_POST['subject']) && isset($_POST['message'])){
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $user_id = current_user()['id'];
    if(! send_message($user_id,'admin',$subject,$message)){
        echo json_encode(array('status'=>'error'));
        return ;
    }
    echo json_encode(array('status'=>'ok'));
    return ;
}

if(isset($_GET['open'])){
    $id = $_GET['open'];
    $msg = get_message($id);
    echo json_encode($msg);
    return ;
}

?>