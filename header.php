
<?php include_once 'options.php';?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="<?php echo get_options('url');?>/assets/style.css">
<script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>


<header class="header">
    <div class="logo"> 
        <a href="/">
            <?php set_option('logo','/assets/logo.jpeg');?>
            <img src="<?php echo get_options('url').get_options('logo');?>" alt="<?php echo get_options('title'); ?>">
        </a>
    </div>
    <nav>
    <div class="about_website">
    <?php
      echo '<a href="'.get_options('url').'">Home</a>';
        echo '<a href="'.get_options('url').'/contact_us">Contact</a>';
        echo '<a href="'.get_options('url').'/about_us.php">About</a>';
    ?>
    </div>
        <?php
        include_once('options.php');
        if(current_user() && current_user()['id'] != 0){
            $user =  current_user();
            $username = $user['name'];
            if($user['role'] == 'admin'){

                echo '<a href="'.get_options('url').'/user">'.$username.' <i class="fa fa-user-circle" aria-hidden="true"></i>'.' </a>';
                echo '<a href="'.get_options('url').'/admin.php">Admin_panel</a>';
                echo '<a href="'.get_options('url').'/profile"> profile </a>';
                echo '<a href="'.get_options('url').'/logout"> logout </a>';

            }else{
                $user_id = $user['id'];
                $url = get_options('url');
                $user_menu = "
                <div class='active_menu'>
                <a href='$url/profile'><i class='fa fa-user'></i> $username </a>
                <a href='$url/user?id=$user_id'> dashboard ".$user['wallet']." <i class='fa fa-money'></i></a>
                <a href='$url/add_link'> Add Link </a>
                <a href='$url/my_links'> Your Links </a>
                <a href='$url/links'> Link Exchange </a>
                <a href='$url/logout'> logout </a>
                </div>
                ";
                echo $user_menu;
            ?>

                

            <?php
            }
        }
        else{
            echo '<div class="user_links"><a href="'.get_options('url').'/login.php">log in</a>';
            echo '<a href="'.get_options('url').'/register.php">register</a></div>';
        }
        ?>
    
    </nav>
    <div class="nav_menu_btn">
        <i class="fa fa-bars" aria-hidden="true"></i>
    </div>
</header>
<div class="sidebar"></div>
<script>
    $('.nav_menu_btn').click(()=>{
        let nav_bar = $('.nav_menu_btn');
        if(nav_bar.hasClass('active')){
            nav_bar.removeClass('active');
            nav_bar.html('<i class="fa fa-bars" aria-hidden="true"></i>');
            $('.sidebar').html('');
            $('.sidebar').removeClass('active');
        }else{
            nav_bar.addClass('active');
            nav_bar.html('<i class="fa fa-times" aria-hidden="true"></i>');
            $('.sidebar').addClass('active');
            $('.sidebar').append($('nav').html());
        }
    });
    $('.notification').click(()=>{
        $('.notification').hide();
    });
</script>
<div class="user_menu"></div>
<div class="msg"></div>

<div id="body">

