<?php
include_once '../../options.php';
if(current_user()['role'] != 'admin'){
    header('location:../index.php');
    exit();
}
if(isset($_POST['menu'])){
    $id = $_POST['menu'];
    $id = get_menu($by='title',$id) ? get_menu($by='title',$id)['id'] : 0;
    $value = $_POST['pages'];
    $title = $_POST['title'];
    $data = array();
    foreach($value as $page){
        $page = get_page($by='id',$page);
        array_push($data,array('id'=>$page['id'],'title'=>$page['title']));
    }
    
    if ($id == 0){
        add_menu($title,$value);
    }
    else{
        update_menu($id,$title,$value);
    }
    return ;
}

if(isset($_GET['id'])){
    include_once '../../options.php';
    $title = $_GET['id'];
    $menu = get_menu($by='id',$title);
    echo json_encode($menu);
    return ;
}
?>