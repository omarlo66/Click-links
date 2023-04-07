<!DOCTYPE html>
<html lang="en">
<head>
    <?php require 'options.php';
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | <?php echo get_options('title');?></title>
</head>
<body>
<link rel="stylesheet" href="assets/style.css">
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
    <div><h3>$welcome_header</h3><p>$welcome</p></div>
    <div><img src='assets/welcome.png'></div>
    </div>";
}?>

<?php include_once 'footer.php'?>
<script>
        current_url = window.location.href;
        current_url = current_url.replace('index.php','');
        $.post('apis/settings.php',{option:'url',value:current_url},(data)=>{

                });
            
</script>

</body>
</html>
