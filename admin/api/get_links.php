<?php
include '../../options.php';
global $sql;
if(current_user()['role'] != 'admin'){
    header('location:../index.php');
    exit();
}
if(isset($_GET['get'])){
    $filter = $_GET['get'];
    if($filter == 'all'){
        global $sql;
        $links = $sql->query('SELECT * FROM links');
        echo links_admin_page($links);
    }elseif($filter == 'active'){
        $links = get_links();
        echo links_admin_page($links);
    }elseif($filter == 'pending'){
        $links = get_unapproved_links();
        echo links_admin_page($links);
    }
}
function links_admin_page($links){
    $html = '';
    foreach($links as $link){
        $author = get_user($link['author']);
        $html .= '
        <div class="link">
            <div class="link_id"> link id:'.$link['id'].'</div>
            <div style="height: 10px;"></div> 
            <div class="link_url"> url: '.$link['link'].'</div>
            <div style="height: 10px;"></div> 
            <div class="link_source"> source: '.$link['source'].'</div>
            <div style="height: 10px;"></div> 
            <div class="link_budget"> budget: '.$link['budget'].' clicks: '.$link['clicks'].'</div>
            <div style="height: 10px;"></div> 
            <div class="link_user"> about user: -- <b>name:'.$author->name.' email:'.$author->email.'</b></div>
            <div style="height: 10px;"></div> 
            <div class="link_status">status: '.$link['status'].'</div>
            <div class="link_action">
                <button onclick="approve_link('.$link['id'].')">approve</button>
                <button onclick="delete_link('.$link['id'].')">delete</button>
            </div>
        </div>
        <div style="height: 30px;"></div> 
        ';
    }
    return $html;
}

?>