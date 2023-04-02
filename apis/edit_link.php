<?php 
    include '../options.php';
$all_user_links = get_link_by_user(current_user()['id']);
$cred = false;
foreach($all_user_links as $row){
    if($row['id'] == $_POST['id']){
        $cred = true;
    }
}

if(isset($_POST['id'])){
    if($cred){
    $link_id = $_POST['id'];
        $link = $_POST['link'];
        $budget = $_POST['budget'];
        $id = $_POST['id'];
        echo update_link($id, $link, $budget);
    }else{
        echo 'You are not authorized to edit this link';
    }
}


?>