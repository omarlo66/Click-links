<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    include_once 'options.php';
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | <?php echo get_options('title');?></title>
</head>
<body>
        <?php include_once 'header.php';?>
        <?php
        if(isset($_GET['token'])){
            $token = $_GET['token'];
            $user_id = $_GET['user_id'];
            $read_token = get_user_meta($user_id,'reset_passwrod');
            if($read_token === $token){
                ?>
                <div class="form">
                    <h1>Reset Password</h1>
                    <div class="input">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password">
                    </div>
                    <div class="input">
                        <label for="password">Password confirm</label>
                        <input type="password" name="password_2" id="password_2">
                    </div>
                    <div id="show_password">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </div>
                    <button onclick="reset_password()">Reset Password</button>
                </div>
                <script>
                    function reset_password(){
                        var password = $('#password').val();
                        var password_2 = $('#password_2').val();
                        var user_id = '<?php echo $user_id;?>';
                        if(password != password_2){
                            alert('passwords do not match');
                            return;
                        }
                        $.post('apis/reset_password.php',{password:password,user_id:user_id},function(data){
                            $('.form').html(data);
                        });
                    }
                    $('#show_password').click(()=>{
                        if($('#password').attr('type') == 'password'){
                            $('#password').attr('type','text');
                            $('#password_2').attr('type','text');
                            $('#show_password').html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
                        }else{
                            $('#password').attr('type','password');
                            $('#password_2').attr('type','password');
                            $('#show_password').html('<i class="fa fa-eye" aria-hidden="true"></i>');
                        }
                    });
                </script>
                <?php
            }else{
                echo 'invalid token';
            }
        }
        if(! isset($_GET['token'])){
        ?>
        <div class="form">
            <h1>Reset Password</h1>
            <div class="input">
                <label for="email">Email</label>
                <input type="email" name="email" id="email">
            </div>
            <button onclick="reset_password()">Reset Password</button>
        </div>
        <script>
            function reset_password(){
                email = $('#email').val();
                $.post('apis/reset_password.php',{email:email},function(data){
                    $('.form').html(data);
                });
            }
        </script>
        <?php 
        }
        include_once 'footer.php';
        ?>
</body>
</html>