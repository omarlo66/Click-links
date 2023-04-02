<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
</head>
<body>
<link rel="stylesheet" href="assets/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <h1>Login</h1>
    <div class="login_form form">
        <div class="input">
            <i class="fa fa-user-circle" aria-hidden="true"></i>
            <input type="text" name="username" id="username" placeholder="username">
        </div>
        <div style='height: 20px;'></div>
        <div class="input">
            <i class="fa fa-key" aria-hidden="true"></i>
            <input type="password" name="password" id="password" placeholder="password">
        </div>
        <div id='show_password'>
            <i class="fa fa-eye" aria-hidden="true"></i>
        </div>
        <div style='height: 20px;'></div>
        <a href="forgot_password" >forgot password</a>
        <div style='height: 40px;'></div>
        <button id="login" onclick="login()">Login</button>
        <div style='height: 40px;'></div>
        <p style="color:#000;">create account <a href="register.php">Sign up</a></p>
    </div>
   
<script>
        let count_login_try = 3;
    function login(){
        if(count_login_try == 0){
            let count_login_try = sessionStorage.getItem('count_login_try');
            $('#login').html('please wait..');
            $('.login_form').append('<p class="notification error">You have tried to login more than 3 times.<br> please wait 5 minutes</p>');
            setInterval(()=>{$('.notification').remove()}, 5000);
            $('#login').attr('disabled', true);
            setInterval(()=>{
                $('#login').attr('disabled', false)
                $('#login').html('Login')
                count_login_try = 3;
            }, 1000*60*5);
            return;
        }
        $('#login').html('please wait..');
        count_login_try--;
        sessionStorage.setItem('count_login_try', count_login_try);
        if($('#username').val() == '' || $('#password').val() == ''){
            $('.login_form').append('<p class="notification error">Please fill all fields</p>');
            setInterval(()=>{$('.notification').remove()}, 5000);
            $('#login').html('Login');
            return;
        }
        $.post('apis/api-login.php',{username:$('#username').val(), password:$('#password').val()},(data)=>{
            console.log(data);
            if(data){
                $('.login_form').append('<p class="notification success">You are logged in succefully.<br> please wait..</p>');
                setInterval(()=>{window.location.href='index.php'}, 2000);
            }else{
                $('.login_form').append('<p class="notification error"> Wrong email or password double check them and try again<br>Remember try to login is limited you have '+count_login_try+' times to try login after that you will wait 5 minutes to try again.</p>');
                setInterval(()=>{$('.notification').remove()}, 5000);
            }
        }).fail(()=>{
            $('.login_form').append('<p class="notification error">Something went wrong</p>');
            setInterval(()=>{$('.notification').remove()}, 5000);
        });
        $('#login').html('Login');
    }
    $('#show_password').click(()=>{
            if($('#password').attr('type') == 'text'){
                $('#password').attr('type', 'password');
                $('#password2').attr('type', 'password');
                $('#show_password').html('<i class="fa fa-eye" aria-hidden="true"></i>');
            }else{
                $('#password').attr('type', 'text');
                $('#password2').attr('type', 'text');
                $('#show_password').html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
            }
        });
</script>
<?php include_once 'footer.php'?>
</body>
</html>