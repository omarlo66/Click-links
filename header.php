<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="assets/style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=lalezar">
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script> 

<header class="header">
    <img src="<?php echo get_options('logo');?>" alt="<?php echo get_options('title'); ?>">
    <nav>
    <div class="links">
        <a href="index.php">Home</a>
        <a href="about.php">About us </a>
        <a href="contact.php">Contact us</a>
    </div>
        <?php
        include_once('options.php');
        if(user_logged_in()){
            $user =  current_user();
            $username = $user['name'];
            if($user['role'] == 'admin'){
                echo '<a>'.$username.' <i class="fa fa-user-circle" aria-hidden="true"></i>'.' </a>';
                echo '<a href="admin.php">Admin_panel</a>';
                echo '<a href="logout.php"> logout </a>';
            }else{
                $user_id = $user['id'];
                $user_menu = "
                <div class='active_menu'>
                <a href='user?id=$user_id'> $username </a>
                <a> ".$user['wallet']." <i class='fa fa-money'></i></a>
                <a href='add_link'> Add Link </a>
                <a href='my_links'> Your Links </a>
                <a href='links'> get links </a>
                <a href='logout'> logout </a>
                </div>
                ";
                echo $user_menu;
            ?>

 

            <?php
            }
        }
        else{
            echo '<a href="login.php">log in</a>';
            echo '<a href="register.php">register</a>';
        }
        ?>
    
    </nav>
</header>
<script>
    if(window.screen.width < 600){
        $('nav').hide();
    }
    $('.notification').click(()=>{
        $('.notification').hide();
    });
</script>
<div class="user_menu"></div>
<div class="msg"></div>

<div id="body">

