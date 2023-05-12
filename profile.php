<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once 'options.php';
    $user = current_user();
    if(! $user){
        echo "<script>location.href = '/login.php'</script>";
        return;
    }
    $user_name = $user['name'];
    $user_id = $user['id'];
    $user_email = $user['email'];
    $user_password = $user['password'];
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | <?php echo $user_name;?></title>
</head>
<body>
    <?php include 'header.php';?>
<div class="form">
    <div class="input">
        <input type="text" id="user_email" value="<?php echo $user_email;?>" disabled>
    </div>
       <div class="input">
            <input type="text" id="username" value="<?php echo $user_name;?>" disabled>
        </div>
        <div class="input">
            <div id="show_password"><i class="fa fa-eye"></i></div>
            <input type="password" id="password" placeholder="Password" value="<?php echo $user_password;?>" disabled>
        </div>
        <button onclick="Edit_user()" id="update">Edit</button>
    </div>
    <script>
    
    $('#show_password').click(()=>{
            if($('#password').attr('type') == 'text'){
                $('#password').attr('type', 'password');
                $('#show_password').html('<i class="fa fa-eye" aria-hidden="true"></i>');
            }else{
                $('#password').attr('type', 'text');
                $('#show_password').html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
            }
        });

        function Edit_user(){
            $('#user_email').attr('disabled', false);
            $('#username').attr('disabled', false);
            $('#password').attr('disabled', false);
            $('#update').html('Update');
            $('#update').attr('onclick', 'Update_user()');
        }

        function Update_user(){
            var user_email = $('#user_email').val();
            var username = $('#username').val();
            var password = $('#password').val();
            if(user_email == '' || username == ''){
                alert('Please fill all the fields');
                return;
            }
            $.post('/apis/update_user.php', {email: user_email, username: username, password: password}, function(data){ 
                    $('.msg').append('<h3 class="notification success">'+data+'</h3>');
                    $('#user_email').attr('disabled', true);
                    $('#username').attr('disabled', true);
                    $('#password').attr('disabled', true);
                    $('#update').html('Edit');
                    $('#update').attr('onclick', 'Edit_user()');
            });
            setInterval(function(){
                $('.notification').remove();
            }, 3000);
        }
    </script>
    <?php include_once 'footer.php';?>
</body>
</html>