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
        <div class="msg">

        </div>
        

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
            $('.form').on('keyup',(e)=>{
                    if(e.keyCode == 13){
                        login();
                    }
            });

            $('#show_password').click(()=>{
                console.log('click');
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
            function send_msg(msg, type){
                $('.msg').html('<p class="notification '+type+' style="transition: 2sec;"">'+msg+'</p>');
                setInterval(()=>{
                $('.notification').remove()
                $('.msg').click(()=>{$('.msg').html('')});
                }, 5000);
            }
            function login(){
                if($('#username').val() == '' || $('#password').val() == ''){
                    send_msg('<p class="notification error">Please fill all fields</p>','error');
                    return;
                }
                $.post('apis/api-login.php',{username:$('#username').val(), password:$('#password').val()},(data)=>{
                    
                    if(data == 'true'){
                        send_msg('<p class="notification success">You are logged in succefully.<br> please wait..</p>','success');
                        setInterval(()=>{
                            window.location.href = 'index.php';
                        }, 1000);
                    }else{
                        send_msg('<p class="notification error"> Wrong email or password double check them and try again.</p>','error');
                    }
                }).fail(()=>{
                    send_msg('<p class="notification error">Something went wrong</p>','error');
                });
                $('#login').html('Login');
                }
    </script>

   

<?php include_once 'footer.php'?>
</body>
</html>