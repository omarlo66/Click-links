<?php

$sql = new mysqli('localhost', 'root', '', 'click-link');

function get_options($key){
    global $sql;
    $options = $sql->query("SELECT * FROM options WHERE `key` = '$key'");
    if(! $options or $options->num_rows == 0){
        return false;
        
    }
    return $options->fetch_object()->value;
}
function set_option($key,$value){
    global $sql;
    $value = str_replace("'","\'",$value);
    $value = str_replace('"','\"',$value);
    $options = $sql->query("SELECT * FROM options WHERE `key` = '$key'");
    if($options->num_rows > 0){
        $sql->query("UPDATE options SET value = '$value' WHERE `key` = '$key'");
        return true;
    }
    else{
        $sql->query("INSERT INTO options (`key`,value) VALUES ('$key','$value')");
        return true;
    }
    return false;
}
function users_roles(){
    global $sql;
    return explode(',',get_options('users_roles'));
}
function create_options_table(){
    global $sql;
    $options_table = $sql->query("CREATE TABLE options (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `key` VARCHAR(255) NOT NULL,
        value VARCHAR(255) NOT NULL
    )");
}
function create_links_table(){
    global $sql;
    $links_table = $sql->query("CREATE TABLE links (
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
    )");
}
function create_table_wallet(){
    global $sql;
    $wallet_table = $sql->query("CREATE TABLE wallet (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) NOT NULL,
        amount INT(6) NOT NULL,
        title VARCHAR(255) NOT NULL,
        date VARCHAR(255) NOT NULL
    )");
}
function create_users_table(){
        global $sql;
    $users_table = $sql->query("CREATE TABLE users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        role VARCHAR(255) NOT NULL,
        date VARCHAR(255) NOT NULL
    )");
}
function create_traffic_table(){
    global $sql;
    $traffic_table = $sql->query("CREATE TABLE traffic (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) NOT NULL,
        link_id INT(6) NOT NULL,
        ip_address VARCHAR(255) NOT NULL,
        date VARCHAR(255) NOT NULL
    )");
}
function create_table_usermeta(){
    global $sql;
    $usermeta_table = $sql->query("CREATE TABLE usermeta (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) NOT NULL,
        meta_key VARCHAR(255) NOT NULL,
        meta_value VARCHAR(255) NOT NULL
    )");
}
function user_logged_in(){
    if(isset($_COOKIE['user_id']) and get_userId($_COOKIE['user_id']) != false){
        return true;
    }
    else{
        return false;
    }
}
function current_user(){
    if(isset($_COOKIE['user_id'])){
        global $sql;
        $user_data = array();
        $user_id = get_userId($_COOKIE['user_id']);
        $user = $sql->query("SELECT * FROM users WHERE id = '$user_id'");
        if($user->num_rows > 0){
            $user = $user->fetch_object();
            $user_data['id'] = $user->id;
            $user_data['name'] = $user->name;
            $user_data['email'] = $user->email;
            $user_data['wallet'] = user_points($user->id);
            $user_data['count_user_links'] = count(get_link_by_user($user->id));
            $user_data['role'] = $user->role;
            $user_data['date'] = $user->date;
            return $user_data;
        }
        else{
            return false;
        }
    }
    else{
        return false;
    }
    
}
function login($username,$password){
    global $sql;
    $password = md5($password);
    $user = $sql->query("SELECT * FROM users WHERE name = '$username' OR email = '$username' AND password = '$password'") != null ? $sql->query("SELECT * FROM users WHERE name = '$username' AND password = '$password'") : $sql->query("SELECT * FROM users WHERE email = '$username' AND password = '$password'");
    if($user->num_rows > 0){
        $user = $user->fetch_object();
        update_user_meta($user->id,'last_login',date('Y-m-d H:i:s'));
        $session_token = generate_session_token($user->id);
        update_user_meta($user->id,'session_token',$session_token);
        setcookie('user_id',$session_token ,time() + (86400 * 30),'/');
        echo true;
    }
    else{
        echo false;
    }
}
function register($username,$email,$password){
    global $sql;
    $user = $sql->query("SELECT * FROM users WHERE name = '$username' OR email = '$email'");
    if($user->num_rows > 0){
        return false;
    }
    $date = date('Y-m-d H:i:s');
    $password = md5($password);
    $user = $sql->query("INSERT INTO users (name,email,password,role,date) VALUES ('$username','$email','$password','user', '$date')");
    if($user){
        return true;
    }
    else{
        return false;
    }
}
function logout(){
    update_user_meta(get_userId($_COOKIE['user_id']),'session_token',null);
    session_destroy();
    setcookie('user_id','',time() - 3600,'/');
    header('Location: index.php');
}
function get_user($user_id){
    global $sql;
    $user = $sql->query("SELECT * FROM users WHERE id = '$user_id'");
    if($user->num_rows > 0){
        return $user->fetch_object();
    }
    else{
        return false;
    }
}
function user_points($user_id){
    global $sql;
    $user = $sql->query("SELECT * FROM wallet WHERE user_id = '$user_id' ORDER BY id DESC");
    $points = 0;
    while($row = $user->fetch_object()){
        $points += $row->amount;
    }
    return $points;
}
function user_links_count($user_id){
    global $sql;
    $user = $sql->query("SELECT * FROM links WHERE author = '$user_id'");
    return $user->num_rows;
}
function update_user($user_id,$username,$password,$email){
    global $sql;
    $ready_update = check_duplicate($user_id,$username,$email);
    if(! $ready_update){
        $password = md5($password);
            $query = $sql->query("UPDATE users SET email='$email', name='$username', password='$password' WHERE id=$user_id");
        return $query;
    }
    else{
        return false;
    }
}
function check_duplicate($user_id,$username,$email){
    global $sql;
    $user = $sql->query("SELECT * FROM users WHERE name = '$username' OR email = '$email'");
    if($user->num_rows > 0){
        foreach($user->fetch_all(MYSQLI_ASSOC) as $row){
            if($row['id'] != $user_id){
                return true;
            }
        }
    }
    else{
        return false;
    }
}
function add_new_link($link_id,$link,$source,$budget){
    global $sql;
    $date = date('Y-m-d H:i:s');
    if(! user_logged_in()){
        return false;
    }
    $points_per_click = get_options('points_per_click') || 1;
    $author = current_user()['id'];
    update_user_meta($author,'links_count',user_links_count($author));
    $points = 0;
    $clicks = 0;
    $query = $sql->query("INSERT INTO links (link_id,link,source,budget,author,points,points_per_click,clicks,status,date) VALUES ('$link_id','$link','$source','$budget','$author','$points','$points_per_click','$clicks','pending','$date')");
    return $query;
}

function get_links(){
    global $sql;
    $links = $sql->query("SELECT * FROM links WHERE status = 'active' ORDER BY budget DESC");
    return $links->fetch_all(MYSQLI_ASSOC);
}
function get_link($by='id',$id=0){
    global $sql;
    $link = $sql->query("SELECT * FROM links WHERE $by = '$id'");
    if($link->num_rows > 0){
        return $link->fetch_object();
    }
    else{
        return false;
    }
}
function add_traffic($url_id,$user_id){
    global $sql;
    $date = date('u');
    $ip = $_SERVER['REMOTE_ADDR'];
    $query = $sql->query("INSERT INTO traffic (user_id,link_id,ip_address,date) VALUES ('$user_id','$url_id','$ip','$date')");
    return $query;
}
function get_traffic_ip(){
    return $_SERVER['REMOTE_ADDR'];
}
function get_traffic($url_id,$user_id,$ip = null){
    if($ip == null){
        $ip = get_traffic_ip();
    }
    global $sql;
    $traffic = $sql->query("SELECT * FROM traffic WHERE link_id = '$url_id' AND user_id = '$user_id' AND ip_address = '$ip'");
    if($traffic->num_rows > 0){
        return $traffic->fetch_all(MYSQLI_ASSOC);
    }else{
        return false;
    }
}
function link_clicked($link_id){
    global $sql;
    $link = $sql->query("SELECT * FROM links WHERE link_id = '$link_id'");
    $link = $link->fetch_object();
    $clicks = $link->clicks + 1;
    $points = $link->points + $link->points_per_click;
    $budget = $link->budget - $link->points_per_click;
    if($budget == 0){
        $sql->query("UPDATE links SET status = 'done' WHERE link_id = '$link_id'");
    }
    $query = $sql->query("UPDATE links SET clicks = '$clicks', points='$points' WHERE link_id = '$link_id'");

    if($query){
        return true;
    }
    else{
        return false;
    }

}
function assign_click_to_user($user_id,$link_id){
    global $sql;
    $date = date('Y-m-d H:i:s');
    $link = get_link('link_id',$link_id);
    $points = $link->points_per_click;
    $query = $sql->query("INSERT INTO wallet (user_id,title,amount,date) VALUES ('$user_id','$link_id','$points','$date')");
    return "+$points";
}
function analytics(){
    $count_users = count_users();
    $count_links = count_links();
    $count_clicks = count_clicks();
    $count_points = count_points();
    return array(
        'users' => $count_users,
        'links' => $count_links,
        'clicks' => $count_clicks,
        'points' => $count_points
    );
}
function count_users(){
    global $sql;
    $users = $sql->query("SELECT * FROM users");
    return $users->num_rows;
}
function get_link_by_user($user_id){
    global $sql;
    $links = $sql->query("SELECT * FROM links WHERE author = '$user_id' ORDER BY id DESC");
    return $links->fetch_all(MYSQLI_ASSOC);
}
function count_links(){
    global $sql;
    $links = $sql->query("SELECT * FROM links");
    return $links->num_rows;
}
function count_clicks(){
    global $sql;
    $clicks = $sql->query("SELECT * FROM traffic");
    return $clicks->num_rows;
}
function count_points(){
    global $sql;
    $points = $sql->query("SELECT * FROM wallet");
    $total = 0;
    while($row = $points->fetch_object()){
        $total += $row->amount;
    }
    return $total;
}
function clear_old_traffic(){
    global $sql;
    $date = date('u') * 60 * 60 * 24;
    $query = $sql->query("DELETE FROM traffic WHERE date < '$date'");
    return $query;
}
function get_unapproved_links(){
    global $sql;
    $links = $sql->query("SELECT * FROM links WHERE status = 'pending'");
    return $links->fetch_all(MYSQLI_ASSOC);
}
function approve_link_status($id){
    global $sql;
    $query = $sql->query("UPDATE links SET status='active' WHERE id='$id'");
    return $query;
}
function delete_link_status($id){
    global $sql;
    $query = $sql->query("DELETE FROM links WHERE id='$id'");
    return $query;
}
function get_user_meta($user_id,$meta = null){
    global $sql;
    if($meta != null){
        $user = $sql->query("SELECT * FROM users WHERE id = '$user_id'");
        $user = $user->fetch_object();
        return $user->$meta;
    }
        $user = $sql->query("SELECT * FROM users WHERE id = '$user_id'");
        return $user->fetch_object();
}
function update_user_meta($user_id,$meta,$value){
    global $sql;
    $num_rows = $sql->query("SELECT * FROM usermeta WHERE user_id = '$user_id' AND meta_key = '$meta'")->num_rows;
    $exist =  $num_rows > 0 ? true : false;
    if($exist){
        $query = $sql->query("UPDATE usermeta SET meta_value='$value' WHERE user_id='$user_id' AND meta_key='$meta'");
        return $query;
    }
    $query = $sql->query("INSERT INTO usermeta (user_id , meta_key, meta_value) VALUES ('$user_id','$meta','$value')");
    return $query;
}
function generate_session_token($user_id){
    $token = md5(uniqid(rand(), true));
    global $sql;
    if($sql->query('SELECT * FROM usermeta WHERE meta_value = "'.$token.'"')->num_rows > 0){
        generate_session_token($user_id);
    }
    update_user_meta($user_id,'session_token',$token);
    return $token;
}
function get_userId($session){
        global $sql;
        $user_id = $sql->query("SELECT * FROM usermeta WHERE meta_value = '$session'");
        $query = $user_id->fetch_object();
        if($query){
            return $query->user_id;
        }
        return false;
}

function update_link($id,$url,$budget){
    global $sql;
    $query = $sql->query("UPDATE links SET link='$url', budget='$budget', status = 'pending' WHERE id='$id'");
    if($query){
        return 'updated successfully';
    }else{
        return 'failed to update';
    }
}

function generate_unique_link_id(){
    $id = rand(100000,999999);
    global $sql;
    if($sql->query('SELECT * FROM links WHERE link_id = "'.$id.'"')->num_rows > 0){
        generate_unique_link_id();
    }
    return $id;
}

?>