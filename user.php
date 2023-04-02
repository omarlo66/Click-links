<!DOCTYPE html>
<html lang="en">
<head>
<?php require 'options.php';
if(! isset($_COOKIE['user_id'])){
    header('Location: login.php');
}
$user = current_user();
$user_name = $user['name'];
$user_id = $user['id'];
?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User | <?php echo $user_name?></title>
</head>
<body>
        <link rel="stylesheet" href="assets/style.css">
        <?php require 'header.php';?>
        <h1><?php echo "Welcome ".$user_name;?></h1>
    <div class="widget_anaytics">
        <?php
        $analytics = analytics();
        ?>
        <div class="dashboard_links">
            <p><?php echo $analytics['links'];?></p>
            <p>Links</p></div>
        <div class="dashboard_users">
            <p><?php echo $analytics['users'];?></p>
            <p>Active user</p></div>
        <div class="dashboard_points">
            <p><?php echo user_points($user_id);?></p>
            <p>Points</p>
        </div>
    </div>
        <?php
        $user_name = current_user()['name'];
        $user_email = current_user()['email'];
        $points = user_points(current_user()['id']);
        $links = get_link_by_user(current_user()['id']);
        ?>
    <div class="user_page">
    <div class="user_details">
    
        <input type="text" id="user_email" value="<?php echo $user_email;?>" disabled>
        <input type="text" id="username" value="<?php echo $user_name;?>" disabled>
        <input type="password" id="password" placeholder="Password" disabled>
        <button onclick="Edit_user()" id="update">Edit</button>
    </div>
    <script>
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
            $.post('apis/update_user.php', {email: user_email, username: username, password: password}, function(data){
                
                 
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

    <div class="Earning">   
    <h2>Your Earning</h2>
    
        <p class="points"><i class="fa fa-money" aria-hidden="true"></i><?php echo $points;?></p>
        <button onclick="all_links()">get links</button>
        <p>Get links to share and earn more</p>
    </div>

    <div class="links">
    <h2>Your Links</h2>
        <div class="links_list">
        <?php
        $count = user_links_count(current_user()['id']);
        echo "<p>You have ( $count ) link</p>"
        ?>
        <button onclick="add_new_link()">add new link</button>
        <a href="my_links.php">Your Links</a>
        <div style='height:10px;'></div>
    </div>
    </div>
    <script>
        function add_new_link(){
            location.href = 'add_link.php';
        }
        function all_links(){
            location.href = 'links.php';
        }
    </script>

<?php include_once 'footer.php'?>
</body>
</html>