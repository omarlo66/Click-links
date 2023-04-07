
<?php

if(isset($_POST['reply'])){
    $id = $_POST['reply'];
    $id = get_message($by='id',$id);
    if(!$id){
        echo json_encode(array('status'=>'error'));
        return null;
    }
    $send_to = $id['user_id'];
    $subject = "reply: ".$id['subject'];
    $msg = $_POST['message'];
    
    include_once '../../options.php';
    if(!send_message('admin',$send_to,$subject,$msg)){
        echo json_encode(array('status'=>'error'));
        return null;
    }
    echo json_encode(array('status'=>'ok'));
}

if(isset($_GET['search']) || isset($_GET['filter'])){
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $filter = isset($_GET['filter']) ? $_GET['filter'] : false;
    $msgs = search_msg($filter,$search);
    echo json_encode($msgs);
    return ;
}
?>