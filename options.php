<?php

$sql = new mysqli('localhost', 'root', '', 'click-link');

if($sql->connect_error){
    log('Connection failed: ' . $sql->connect_error);
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




//System Functions
    function debug($error){
    $date = date('Y-m-d H:i:s');
    $log_file = fopen('log.txt','a') or fopen('log.txt','w');
    fwrite($log_file,"$error\t$date\n");
    }

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
        $query = $sql->query("DELETE FROM links WHERE id='$id'");
        return $query;
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
    function link_reported($id){
        global $sql;
        $query = $sql->query("UPDATE links SET status = 'reported' WHERE id='$id'");
        return $query;
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
    function link_clicked($link_id){
        global $sql;
        $link = $sql->query("SELECT * FROM links WHERE link_id = '$link_id'");
        $link = $link->fetch_array();

        if($link){
            
            $clicks = $link['clicks'] + 1;
            $points_per_click =  $link['points_per_click'];
            $points = $link['points'] + $points_per_click ;
            $budget = $link['budget'] - $points_per_click;
            if($budget === 0){
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
        return array('points_added'=>$query,'credit_added'=>$insert,$points);
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


//Points Function

    function insert_user_wallet($user,$amount,$link_id){
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
        $query = $sql->query("INSERT INTO wallet (user_id,title,amount,date) VALUES ('$user','$link_id','-$amount','$date')");
        if(! $query or $points + 1  < user_points($user)){
            debug($sql->error);
            return false;
        }
        return user_points($user);
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

function init(){
    global $sql;
    if($sql->error){
        debug($sql->error);
        include_once 'setup/bkend.php';
        $menu = create_table_menu();
        $pages = create_table_pages();
        $links = create_links_table();
        $wallet = create_table_wallet();
        $contact = create_table_messages();
        $options = create_options_table();
        $users = create_users_table();
        $user_meta = create_table_usermeta();
        $queue = array();
        array_push($queue,$menu,$pages,$links,$wallet,$contact,$options,$users,$user_meta);
        foreach($queue as $query){
            if($sql->query($query)){
                echo 'Table Created';
            }
            else{
                echo '!!';
            }

        }
        set_option('site_name','My Site');
        set_option('site_description','My Site Description');
        set_option('site_url', __DIR__);
        set_option('site_email','admin@localhost');
        set_option('site_logo','logo.png');
        set_option('site_favicon','favicon.png');
        add_menu('Main Menu',array(
            array(
                'title' => 'Home',
                'url' => '/'
            ),
            array(
                'title' => 'About Us',
                'url' => get_page('title','About Us')
            ),
            array(
                'title' => 'Contact Us',
                'url' => get_page('title','Contact Us')
            )
        ));

    }
}
?>