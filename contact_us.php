<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    include_once 'options.php';
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php include_once 'header.php';
    if(current_user() && current_user()['id'] != 0){
        $user_id = current_user()['id'];
        $username = current_user()['name'];
        $send = true;
    }else{
        $username = "";
        $send = false;
    }
    if(isset($_GET['subject'])){
        $subject = $_GET['subject'];
    }else{
        $subject = "";
    }
    ?>

    <h1>Contact Us</h1>
    <div class="form">
        <div class="input">
            <label for="name">Name</label>
            <input type="text" id="name" value="<?php echo $username;?>" disabled>
        </div>
        <div style='height:40px;'></div>
        <div class="input">
            <label for="subject">subject</label>
            <input type="text" id="subject" value="<?php echo $subject;?>">
        </div>
        <div style='height:40px;'></div>
        <label for="message">message</label>
        <div class="text_box">            
            <textarea name="message" id="message" cols="30" rows="10"></textarea>
        </div>
        <div style='height:40px;'></div>
        <?php 
        if($send){
            echo "<button id='send'>Send</button>";
        }else{
            echo "<p>Please <a href='/login.php'>log in</a> to send messages to admin</p>";
        }
        
        ?>
        
    </div>
    
        <?php
            $page_content = get_page('title','contact') || get_page('title','contact_us');
            if($page_content){
                echo "<h1>".$page_content['title']."</h1>";
                echo "<div class='content'>".$page_content['content']."</div>";
            }
        ?>
    <
    <script>
        
        $('#message').attr('placeholder','')
        $("#send").click(function(){
            var name = $("#name").val();
            var subject = $("#subject").val();
            var message = $("#message").val();
            $.post('apis/contact.php',{name:name,subject:subject,message:message},function(data){
                data = JSON.parse(data);
                if(data.status == 'ok'){
                    
                    alert('Message sent successfully');
                    $('#subject').val('');
                    $('#message').val('');

                }else{
                    alert('Something went wrong');
                }
            });
        });

    </script>
</body>
</html>