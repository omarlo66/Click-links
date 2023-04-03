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
        echo $pages;
    }else{
        $page = get_page($q=$page_id);
        echo $page;
    }
    return;
}
if(isset($_POST['new'])){
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = current_user()['id'];
    echo add_page($title,$content);
    return;
}
if(isset($_POST['edit'])){
    $id = $_POST['edit'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = current_user()['id'];
    echo $id.' '.$title.' '.$content;
    edit_page($id,$title,$content);
    return ;
}
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $user_id = current_user()['id'];
    delete_page($id,$user_id);
    return;
}

    echo json_encode(all_pages());
    return;



?>