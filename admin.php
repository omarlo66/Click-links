<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'options.php';?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>
<body>
    <?php
    require_once 'header.php';
    if(current_user()['role'] != 'admin'){
        header('Location: login.php');
    }
    ?>
    <h1>Admin panel</h1>
    
    <div class="admin_menu">
        <div id="web_settings">website settings</div>
        <div id="users_admin">users</div>
        <div id="links_admin">links</div>
        <div id="pages_admin">pages</div>
        <div id="ads">ads</div>
    </div>

    <div id="form">

    </div>
    

    <script>
        $('#web_settings').click(function(){
            $('#form').addClass('form');
            $('.form').load('admin/admin_web_settings.php');
        });
        $('#users_admin').click(function(){
            $('#form').addClass('form');
            $('.form').load('admin/admin_users.php');
        });
        $('#links_admin').click(function(){
            $('#form').addClass('form');
            $('.form').load('admin/admin_links.php');
        });
        $('#pages_admin').click(function(){
            $('#form').addClass('form');
            $('.form').load('admin/admin_page.php');
        });
        $('#ads').click(function(){
            $('#form').addClass('form');
            $('.form').load('admin/ads_widget.php');
        });
    </script>
<?php
if(isset($_GET['delete_user'])){
    $id = $_GET['delete_user'];
    $sql->query("DELETE FROM users WHERE id = $id");
    header('location: admin.php');
}

include_once 'footer.php';
?>

</body>
</html>