<!DOCTYPE html>
<html lang="en">
<head>
    <?php require 'options.php';?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | <?php echo get_options('title');?></title>
</head>
<body>
<link rel="stylesheet" href="<?php echo get_options('url');?>assets/style.css">
<?php include 'header.php';?>
<?php 
if(isset($_COOKIE['user_id']) && current_user()['id'] != 0){
    header('Location: user.php');
}
$welcome = get_options('welcome_content');
$welcome_header = get_options('welcome_header');

$page = get_page('title','home');
if($page){
    $content = $page['content'];
    echo $content;
}
?>

<?php
if($welcome != null && $welcome_header != null){
    echo "<div class='welcome'>
    <div class='welcome_title'><h3>$welcome_header</h3><p>$welcome</p></div>
    <div class='welcome_img'><img src='assets/welcome.png'></div>
    </div>";
}?>
<div class="about_us">
    <h2>Why disha.fun to earn from?</h2>
    <p>
        <div class="widget_1">
            <img src="assets/1.png">
            <h3>Easy to use</h3>
            <p>It is very easy to use and you can earn from it.</p>
        </div>
        <div class="widget_1">
            <img src="assets/2.png">
            <h3>Fast payment</h3>
            <p>Payment is very fast and you can withdraw your money in just 1 hour.</p>
        </div>
        <div class="widget_1">
            <img src="assets/3.png">
            <h3>Safe and secure</h3>
            <p>It is very safe and secure to use and you can earn from it.</p>
        </div>
    </p>
</div>
<?php include_once 'footer.php'?>
<script>
    <?php set_option('url',$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);?>
</script>

</body>
</html>
