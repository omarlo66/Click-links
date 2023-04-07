<?php
include '../../options.php';

if(current_user()['role'] != 'admin'){
    header('Location: index.php');
    return ;
}

if(isset($_GET['page'])){
    $page_id = $_GET['page'];
    if ($page_id == null){

        $pages = all_pages();
        echo json_encode($pages);
    }else{
        $page = get_page($by='id',$q=$page_id);
        echo json_encode($page);
    }
    return;
}
if(isset($_POST['new'])){
    $title = $_POST['title'];
    $content = $_POST['content'];
    $status = $_POST['status'];
    $user_id = current_user()['id'];
    echo add_page($title,$content,$status);
    return;
}
if(isset($_POST['edit'])){
    $id = $_POST['edit'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $status = $_POST['status'];
    $user_id = current_user()['id'];
    echo $id.' '.$title.' '.$content;
    edit_page($id,$title,$content,$status);
    return ;
}
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $user_id = current_user()['id'];
    delete_page($id,$user_id);
    return;
}





?>