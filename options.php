<?php

//$sql = new mysqli('localhost', 'root', '', 'click-link');
$sql = new mysqli('localhost', 'zonehrak_click', 'Oo_01006178135', 'zonehrak_click');
if($sql->connect_error){
    die('Connection failed: ' . $sql->connect_error);
}

//User Functions
    function user_logged_in(){
        if(isset($_COOKIE['user_id']) && is_array(get_userId($_COOKIE['user_id']))){
            return true;
        }else{
            return false;
        }
    }
    function current_user(){
        if(isset($_COOKIE['user_id'])){
            global $sql;
            $user_data = array();
            $user_id = get_userId($_COOKIE['user_id']);
            $user = $sql->query("SELECT * FROM users WHERE id = '$user_id'" );
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
                return array('id'=>0);
            }
        }
        else{
            return false;
        }
        
    }
    function login($username,$password){
        global $sql;
        $password = md5($password);
        $user = $sql->query("SELECT * FROM users WHERE name = '$username' AND password = '$password'")->num_rows > 0 ? $sql->query("SELECT * FROM users WHERE name = '$username' AND password = '$password'") : $sql->query("SELECT * FROM users WHERE email = '$username' AND password = '$password'");
        if($user->num_rows > 0){
            $user = $user->fetch_object();
            update_user_meta($user->id,'last_login',date('Y-m-d H:i:s'));
            $session_token = generate_session_token($user->id);
            update_user_meta($user->id,'user_id',$session_token);
            setcookie('user_id',$session_token ,time() + (86400 * 30),'/');
            return true;
        }else{
            return false;
        }
    }
    function register($username,$email,$password,$role='user'){
        global $sql;
        $user = $sql->query("SELECT * FROM users WHERE name = '$username' OR email = '$email'");
        if($user->num_rows > 0){
            return false;
        }
        $date = date('Y-m-d H:i:s');
        $password = md5($password);
        $user = $sql->query("INSERT INTO users (name,email,password,role,date) VALUES ('$username','$email','$password','$role', '$date')");
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
        $password = md5($password);
        $old = get_user($user_id);
        $ready_update = false;
        $approve = array('name'=>true,'email'=>true);
        if($old->name != $username){
            $ready_update = true;
            $query = $sql->query('SELECT * FROM users WHERE name = "'.$username.'"');
            if($query->num_rows > 0){
                $approve['name'] = false;
            }
        }
        if($old->email != $email){
            $ready_update = true;
            $query = $sql->query('SELECT * FROM users WHERE email = "'.$email.'"');
            if($query->num_rows > 0){
                $approve['email'] = false;
            }
        }
        if($old->password != $password){
            $ready_update = true;
        }
        if($ready_update && $approve['name'] && $approve['email']){
                $query = $sql->query("UPDATE users SET email='$email', name='$username', password='$password' WHERE id='$user_id'");
            return true;
        }elseif($old->name == $username && $old->email == $email){
            return true;
        }else{
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
    function get_user_meta($user_id,$meta = null){
        global $sql;
        if($meta != null){
            $user = $sql->query("SELECT * FROM usermeta WHERE user_id = '$user_id' AND meta_key = '$meta'");
            $user = $user->fetch_object();
            if($user){
            return $user->meta_value;
            }else{
                return false;
            }
        }
            $user = $sql->query("SELECT * FROM usermeta WHERE user_id = '$user_id'");
            return $user->fetch_all(MYSQLI_ASSOC);
    }
    function update_user_meta($user_id,$meta,$value){
        global $sql;
        $num_rows = $sql->query("SELECT * FROM usermeta WHERE user_id = '$user_id' AND meta_key = '$meta'")->num_rows;
        $exist =  $num_rows > 0;
        if($exist){
            $query = $sql->query("UPDATE usermeta SET meta_value='$value' WHERE user_id='$user_id' AND meta_key='$meta'");
            return $query;
        }
        $query = $sql->query("INSERT INTO usermeta (user_id , meta_key, meta_value) VALUES ('$user_id','$meta','$value')");
        return $query;
    }
    function delete_user_meta($user_id,$meta){
        global $sql;
        $query = $sql->query("DELETE FROM usermeta WHERE user_id = '$user_id' AND meta_key = '$meta'");
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
    function get_user_by_email($email){
        global $sql;
        $user = $sql->query("SELECT * FROM users WHERE email = '$email'");
        if($user->num_rows > 0){
            return $user->fetch_object();
        }
        else{
            return false;
        }
    }


//Refferal program
    function count_referrals($user_id){
        $table = "CREATE TABLE refferals (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id INT(6) NOT NULL,
            refferal_id INT(6) NOT NULL,
            date TIMESTAMP
        )";
        global $sql;
        //$sql->query($table);
        $user = $sql->query("SELECT * FROM refferals WHERE user_id = '$user_id'");
        return $user->num_rows;
    }

    function get_referrals($user_id){
        global $sql;
        $user = $sql->query("SELECT * FROM refferals WHERE user_id = '$user_id'");
        return $user->fetch_all(MYSQLI_ASSOC);
    }

    function add_referral($user_id,$refferal_id){
        global $sql;
        $user = $sql->query("SELECT * FROM refferals WHERE user_id = '$user_id' AND refferal_id = '$refferal_id'");
        if($user->num_rows > 0){
            return false;
        }
        $query = $sql->query("INSERT INTO refferals (user_id , refferal_id) VALUES ('$user_id','$refferal_id')");
        return $query;
    }

//System Functions
    function debug($error){
    $date = date('Y-m-d H:i:s');
    $log_file = fopen('log.txt','a') or fopen('log.txt','w');
    fwrite($log_file,"$error\t$date\n");
    }

    function get_options($key){
        global $sql;
        $options = $sql->query("SELECT * FROM options WHERE `key` = '$key'");
        if(! $options || $options->num_rows == 0){
            return '';
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

//Analytics Functions
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

    function count_links(){
        global $sql;
        $links = $sql->query("SELECT * FROM links");
        return $links->num_rows;
    }
    function count_clicks(){
        global $sql;
        $clicks = $sql->query("SELECT * FROM traffic");
        if($clicks){
            return $clicks->num_rows;
        }
        return 0;
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




//Admin

    function all_pages($by='content',$query=''){
        global $sql;
        $pages = $sql->query("SELECT * FROM pages");
        return $pages->fetch_all(MYSQLI_ASSOC);
    }
    function get_page($by='id',$id=0){
        global $sql;
        $page = $sql->query("SELECT * FROM pages WHERE $by = '$id'");
        return $page->fetch_object();
    }
    function add_page($title,$content,$status){
        global $sql;
        $date = date('y/m/d h:m');
        $query = $sql->query("INSERT INTO pages (title,content,status,date) VALUES ('$title','$content','$status','$date')");
        return $sql->insert_id;
    }
    function edit_page($id,$title,$content,$status){
        global $sql;
        $query = $sql->query("UPDATE pages SET title='$title', content='$content', status = '$status' WHERE id='$id'");
        return $query;
    }
    function delete_page($id,$user_id){
        global $sql;
        if(get_user_meta($user_id,'role') != 'admin'){
            return false;
        }
        $query = $sql->query("DELETE FROM pages WHERE id='$id'");
        return $query;
    }
    function search_page($query){
        global $sql;
        $pages = $sql->query("SELECT * FROM pages WHERE title LIKE '%%$query%%' OR content LIKE '%%$query%%'");
        return $pages->fetch_all(MYSQLI_ASSOC);
    }

//Links Functions
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
        $link = get_link('id',$id);
        if($link){
            $user_id = $link->author;
            $amount = $link->budget;
            update_points($user_id,$amount);
            $query = $sql->query("DELETE FROM links WHERE id='$id'");
            return true;
        }else{
            return false;
        }
        
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
        $try = 0;
        if($try > 10){
            $sql->query('DELETE FROM links WHERE status = "deleted"');
        }
        if($sql->query('SELECT * FROM links WHERE link_id = "'.$id.'"')->num_rows > 0){
            $try += 1;
            return generate_unique_link_id();
        }elseif($sql->query('SELECT * FROM usermeta WHERE meta_value = "'.$id.'"')->num_rows > 0){
            $try += 1;
            return generate_unique_link_id();
        }else{
            return $id;
        }
    }
    function link_reported($id){
        global $sql;
        $query = $sql->query("UPDATE links SET status = 'reported' WHERE id='$id'");
        return $query;
    }
    function add_new_link($link_id,$link,$source,$budget){
        global $sql;
        $date = date('Y-m-d H:i:s');
        $points_per_click = get_options('points_per_click');
        $author = current_user()['id'];
        update_user_meta($author,'links_count',user_links_count($author));
        $points = 0;
        $clicks = 0;
        $query = $sql->query("INSERT INTO links (link_id,link,source,budget,author,points,points_per_click,clicks,status,date) VALUES ('$link_id','$link','$source','$budget','$author','$points','$points_per_click','$clicks','pending','$date')");
        update_points($author,-$budget);
        return $query;
    }
    function check_link_exists($link){
        global $sql;
        $link = $sql->query("SELECT * FROM links WHERE link = '$link' OR src = '$link'");
        if($link->num_rows > 0){
            return true;
        }else{
            return false;
        }
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
    function link_clicked($link_id){
        global $sql;
        $link = $sql->query("SELECT * FROM links WHERE link_id = '$link_id'");
        $link = $link->fetch_array();

        if($link){
            
            $clicks = $link['clicks'] + 1;
            $points_per_click =  $link['points_per_click'];
            $points = $link['points'] + $points_per_click ;
            $budget = $link['budget'] - $points_per_click;
            if($budget <= 0){
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
    }
    function assign_click_to_user($user_id,$link_id){
        global $sql;
        $date = date('Y-m-d H:i:s');
        //link data
        $link = get_link('link_id',$link_id);

        //points should be added to user
            $points = $link->points_per_click;
        //+ user credit
            $credit = user_points($user_id);

        //insert into wallet    
        $query = $sql->query("INSERT INTO wallet (user_id,title,amount,date) VALUES ('$user_id','$link_id','$points','$date')");
        $insert = update_user_meta($user_id,'points',$points + $credit);
        $points = $points + $credit;
        return array('points_added'=>$query,'credit_added'=>$points);
    }
    function remove_old_clicks_from_user_meta(){
        global $sql;
        $users = $sql->query("SELECT * FROM usermeta WHERE meta_key = 'used_link'");
        $users = $users->fetch_all(MYSQLI_ASSOC);
        foreach($users as $user){
            $value = unserialize($user['value']);
            $new_value = array();
            foreach($value as $link){
                $date = $link['date'];
                $date = date('Y-m-d H:i:s',$date);
                $date = strtotime($date);
                $date = $date + 86400;
                if($date > time()){
                    array_push($new_value,$link);
                }
            }
            update_user_meta($user['user_id'],'used_link',$new_value);
        }
    }   
    function get_link_by_user($user_id){
        global $sql;
        $links = $sql->query("SELECT * FROM links WHERE author = '$user_id' ORDER BY id DESC");
        return $links->fetch_all(MYSQLI_ASSOC);
    }

    function update_link_status($link_id,$status){
        global $sql;
        $query = $sql->query("UPDATE links SET status ='$status' WHERE link_id = $link_id ");
        if(! $query){
            return false;
        }
        return true;
    }
    function suggest_link_to_user($user_id){
        $links = get_links();
        $final = array();
        $last_links = unserialize(get_user_meta($user_id,'used_link'));
    /*    if(! $last_links){
            $last_links = array();
        }
        foreach($links as $link){
            if($link->author === $user_id){
                continue;
            }else{
                foreach($last_links as $last_link){
                    if($last_link['link_id'] == $link->link_id && $last_link['date'] + 86400 < time()){
                        continue;
                    }else{
                        array_push($final,$link);
                    }
                }
            }
        }
        return $final;
    }*/
    return $links[rand(0,count($links)-1)];
    }

    function create_table_link_meta(){
        global $sql;
        $query = $sql->query("CREATE TABLE IF NOT EXISTS link_meta (
            id INT(11) NOT NULL AUTO_INCREMENT,
            link_id INT(11) NOT NULL,
            meta_key VARCHAR(255) NOT NULL,
            meta_value TEXT NOT NULL,
            PRIMARY KEY (id)
        )");
        if($query){
            return true;
        }
        else{
            return false;
        }
    }

    function get_link_meta($link_id = 0,$meta_key = null){
        global $sql;
        if($meta_key === null && $link_id !== 0){
            $query = $sql->query("SELECT * FROM link_meta WHERE link_id = '$link_id'");
            $query = $query->fetch_all(MYSQLI_ASSOC);
            return $query;
        }
        $query = $sql->query("SELECT * FROM link_meta WHERE link_id = '$link_id' AND meta_key = '$meta_key'");
        $query = $query->fetch_array();
        if($query){
            return $query['meta_value'];
        }
        else{
            return false;
        }
    }

    function update_link_meta($link_id,$key,$value){
        global $sql;
        $q = $sql->query("SELECT * FROM link_meta WHERE link_id = '$link_id' AND meta_key = '$key'");
        $q = $q->num_rows;
        $q = $q > 0 ? true : false;
        if($q){
            $query = $sql->query("UPDATE link_meta SET meta_value = '$value' WHERE link_id = '$link_id' AND meta_key = '$key'");
        }
        else{
            $query = $sql->query("INSERT INTO link_meta (link_id,meta_key,meta_value) VALUES ('$link_id','$key','$value')");
        }
        if($query){
            return true;
        }
        else{
            return false;
        }
    }

    function search_link_meta($link_id, $query_key){
        global $sql;
        $query = $sql->query("SELECT * FROM link_meta WHERE link_id = '$link_id' AND meta_key LIKE '$query_key%%'");
        $query = $query->fetch_all(MYSQLI_ASSOC);
        return $query;
    }
    function delete_link_meta($link_id,$key){
        global $sql;
        $query = $sql->query("DELETE FROM link_meta WHERE link_id = '$link_id' AND meta_key = '$key'");
        if($query){
            return true;
        }
        else{
            return false;
        }
    }
//Points Function

    function insert_user_wallet($user,$amount,$link_id,$type = '-'){
        global $sql;
        $date = date('Y-m-d H:i:s');
        $points = user_points($user);
        if($points - $amount < 1){
            $link_d = get_link('link_id',$link_id);
            $budget = $link_d->budget;
            $clicks = $link_d->clicks;
            $status = $budget == $clicks * $link_d->points_per_click ? 'done' : 'draft';
            $query = $sql->query("UPDATE links SET status = '$status' WHERE link_id = '$link_id'");
            send_message('admin',$link_d->author,$link_d->id.'_link is in draft','There is a link update need attention go to <a href="my_links">for more</a>');
        }
        $query = $sql->query("INSERT INTO wallet (user_id,title,amount,date) VALUES ('$user','$link_id','$type $amount','$date')");
        if(! $query or $points + 1  < user_points($user)){
            debug($sql->error);
            return false;
        }
        return user_points($user);
    }
    function get_user_transactions($user_id){
        global $sql;
        $date = strtotime(date('Y-m-d H:i:s')) - (86400 * 7);
        $date = date('Y-m-d H:i:s',$date);
        $query = $sql->query("SELECT * FROM wallet WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 0,15");
        if($query->num_rows > 0){
            return $query->fetch_all(MYSQLI_ASSOC);
        }
        else{
            return array();
        }
    }

    function update_points($user_id,$amount){
        global $sql;
        $date = date('Y-m-d H:i:s');
        $query = $sql->query("INSERT INTO wallet (user_id,title,amount,date) VALUES ('$user_id','admin','$amount','$date')");
        if($query){
            return true;
        }
        else{
            return false;
        }
    }
//appearance
    function get_menus(){
        global $sql;
        $menu = $sql->query("SELECT * FROM menu ORDER BY id ASC");
        return $menu->fetch_all(MYSQLI_ASSOC);
    }

    function get_menu($by='title',$value=''){
        global $sql;
        $menu = $sql->query("SELECT * FROM menu WHERE $by LIKE '$value'");
        if($menu -> num_rows > 0){
            return unserialize($menu->fetch_object()->value);
        }
        else{
            return false;
        }
        
    }

    function add_menu($name,$url){
        global $sql;
        $url = serialize($url);
        $query = $sql->query("INSERT INTO menu (title,value) VALUES ('$name','$url')");
        return $query;
    }

    function update_menu($id,$name,$url){
        global $sql;
        $url = serialize($url);
        $query = $sql->query("UPDATE menu SET title='$name', value='$url' WHERE id='$id'");
        return $query;
    }

    function delete_menu($id){
        global $sql;
        $query = $sql->query("DELETE FROM menu WHERE id='$id'");
        return $query;
    }

    function add_menu_location($menu_id,$location){
        set_option($location,$menu_id);
    }

    function show_menu($location='main_menu'){
        $menu_id = get_options($location);
        $menu = get_menu($by='title',$menu_id);
        if(! $menu){
            return false;
        }
        foreach($menu as $item){
            echo '<a href="'.$item['url'].'">'.$item['title'].'</a>';
        }
    }


// Messages
    function send_message($from,$to,$subject,$msg){
        global $sql;
        $date = date('Y-m-d H:i:s');
        $email = current_user()['email'] || '';
        $query = $sql->query("INSERT INTO contact (user_id,send_to,email,subject,message,status,date) VALUES ('$from','$to','$email','$subject','$msg','new','$date')");
        if($query){
            return true;
        }
        else{
            return false;
        }
    }
    function get_user_messages($user_id){
        global $sql;
        $messages = $sql->query("SELECT * FROM contact WHERE send_to = '$user_id' OR user_id = '$user_id' ORDER BY id DESC");
        return $messages->fetch_all(MYSQLI_ASSOC);
    }
    function get_message($id){
        global $sql;
        $message = $sql->query("SELECT * FROM contact WHERE id = '$id'");
        $sql->query("UPDATE contact SET status = 'read' WHERE id = '$id'");
        return $message->fetch_object();
    }
    function delete_message($id){
        global $sql;
        $query = $sql->query("DELETE FROM contact WHERE id='$id'");
        return $query;
    }
    function get_messages_not_read(){
        global $sql;
        $messages = $sql->query("SELECT * FROM contact WHERE status = 'new'");
        return $messages->fetch_all(MYSQLI_ASSOC);
    }
    function get_all_messages(){
        global $sql;
        $messages = $sql->query("SELECT * FROM contact ORDER BY id DESC");
        return $messages->fetch_all(MYSQLI_ASSOC);
    }
    function search_msg($filter = false, $query = ''){
        global $sql;
        if($filter){
            $status = $filter;
            $messages = $sql->query("SELECT * FROM contact WHERE status = '$status' AND message LIKE '%%$query%%' ORDER BY id DESC");
            return $messages->fetch_all(MYSQLI_ASSOC);
        }else{
            $messages = $sql->query("SELECT * FROM contact WHERE message LIKE '%%$query%%' ORDER BY id DESC");
            return $messages->fetch_all(MYSQLI_ASSOC);
        }
    }

//wallet
    function change_points_to_credit($user_id,$points){
        
    }
    function init(){
    global $sql;
        set_option('site_name','My Site');
        set_option('site_description','My Site Description');
        set_option('site_url', __DIR__);
        set_option('site_email','admin@localhost');
        set_option('site_logo','logo.png');
        set_option('site_favicon','favicon.ico');
        
        add_menu('Main Menu',array(
            array(
                'title' => 'Home',
                'url' => '/'
            ),
            array(
                'title' => 'About Us',
                'url' =>    '/About_us'),
            array(
                'title' => 'Contact Us',
                'url' => '/contact_us'
            )
        ));

        if(get_options('url')){
        $curl = curl_init();

        // set our url with curl_setopt()
        $url = get_options('url');
        curl_setopt($curl, CURLOPT_URL, "https://api-services.omarehab17.repl.co/click-links/website?url=$url");

        // return the transfer as a string, also with setopt()
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // curl_exec() executes the started curl session
        // $output contains the output string
        curl_exec($curl);
        
        // close curl resource to free up system resources
        // (deletes the variable made by curl_init)
        curl_close($curl);

        }
        

    }





?>