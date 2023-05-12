<?php


include_once '../options.php';

if(isset($_GET['user_id'])){
    $user_id = $_GET['user_id'];
    $suggested_link = generate_unique_link_for_user($user_id);
    if(!$suggested_link){
        echo json_encode(array('link_id'=>0,'link'=>'no_links.php'));
        exit;
    }
    update_link_meta($suggested_link['id'],'u_click_time_'.$user_id,serialize([$user_id,time()]));
    echo json_encode($suggested_link);
}

function generate_unique_link_for_user($user_id){
    $links = get_links();
    $results = [];
    foreach($links as $link){
        $used = get_link_meta($link['id'],'u_click_time_'.$user_id);
        if($used){
            $used = unserialize($used)[1];
            $now = time();
            $diff = $now - $used;
            if($diff > 86000){
                $results[] = $link;
            }
        }else{
            $results[] = $link;
        }
    }
    if(count($results) == 0 && count($links) > 0){
        $results = $links[random_int(0,count($links)-1)];
        if(get_link_meta($results['id'],'u_click_time_'.$user_id)){
            generate_unique_link_for_user($user_id);
        }
    }
    if(count($results) == 1)
        return $results[0];
    return $results[random_int(0,count($results)-1)];
}

?>