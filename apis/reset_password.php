<?php

if(isset($_POST['email'])){
    $email = $_POST['email'];
    include_once '../options.php';
    $auth = get_user_by_email($email);
    if($auth){
        $token = random_int(100000,999999);
        $val = update_user_meta($auth->id,'reset_passwrod',$token);
        set_option('email','mustafa@disha.fun');
        $user_id = $auth->id;
        $name = $auth->name;
        $subject = "Password Reset";
        $message = "Hi $name, <br> Click on the link below to reset your password <br> <a href='".get_options('url')."forgot_password?token=$token&user_id=$user_id'>Reset Password</a>";
        $send_to = $email;
        $send_from = get_options('email');
        $send_from_reply_to = get_options('email');
        $send_to = $email;
        $headers = array(
            'From' => $send_from,
            'Reply-To' => $send_from_reply_to,
            'X-Mailer' => 'PHP/' . phpversion(),
            'MIME-Version' => '1.0',
            'Content-type' => 'text/html; charset=utf-8'
        );
        $send = mail($send_to, $subject, $message, $headers);
        if($send){
            echo "<div class='result'> <h3>check your inbox</h3> <p style='color: #fff;'>if email not found at inbox you may find it on spam folder.</p></div>";
        }else{
            debug($send);
            echo "<div class='result'> <h3>Failed to send email</h3> <p style='color: #fff;'>please try again later.</p></div>";
        }
    }else{
        echo "<div class='result'> <h3>This email doesn't exists</h3> <p style='color: #fff;'>You may be not registered or there is mistake in email refresh to try again.</p></div>";
    }
}
if(isset($_POST['password'])){
    include_once '../options.php';
    $password = $_POST['password'];
    $user_id = $_POST['user_id'];
    $password = md5($password);
    $user = get_user($user_id);
    $update = update_user($user_id,$user->name,$user->email,$password);
    if($update){
        echo "Password reset successfully";
        delete_user_meta($user_id,'reset_passwrod');
    }else{
        echo "Failed to reset password";
    }
}
?>