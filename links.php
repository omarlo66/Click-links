<!DOCTYPE html>
<html lang="en">
<head>
    <?php
include 'options.php';
        if(! isset($_COOKIE['user_id']) || ! current_user()){
            header('Location: login.php');
        }
        
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>get link | <?php echo get_options('title'); ?> </title>
</head>
<body>
    
    <?php

    include 'header.php';
    $user_id = current_user()['id'];
    ?>
        
        <?php echo "<div class='ads_widget'>".get_options('ad_1')."</div>";?>

    <div class="get_link">
        <h1>get link</h1>
        <input type="hidden" id="user_id" value="<?php echo $user_id;?>">
        <input type="hidden" id="link_id" value="">
        <div class="form get_link">
        <button id="start">Wait....</button>
            <div style="height: 20px;"></div>
            <div class="input">
                <input type="text" id="id" placeholder="ID">
            </div>
            <div style="height: 20px;"></div>
                <button onclick="link_auth()">Check ID</button>
            <div style="height: 40px;"></div>
            <div>
                <button id="report" class="report-btn"> Report a problem </button>
                <div style="height: 20px;"></div>
                <button id="new"> New </button>
            </div>
        </div>
        <?php echo "<div class='ads_widget'>".get_options('ad_2')."</div>";?>
                <div class="content">
            <?php echo get_options('links_page_content') || ''; ?>
        </div>
    </div>
    <script>
        function change(){
                location.reload(true);
        }
        $.get('/apis/get_link.php?user_id='+'<?php echo  $user_id;?>',(data)=>{
            
            data = JSON.parse(data);
            var link_id = data.link_id;
            var src = data.source;
            if(link_id == 0){
                location.href = 'no_links.php';
            }else{
            $('#start').attr('onclick',`open_link('${src}')`);
            $('#start').html('click here');
            $('#link_id').val(link_id);
            $('#user_id').val('<?php echo $user_id;?>')
            }
        });
        let clicked = false;
        function open_link(src){
                var link_src = src;
                window.open(link_src,'_blank');
                clicked = true;
        }
        $('#new').click(()=>{
            change();
        });
            
        $('#report').click(()=>{
                $('.report-btn').remove();
                if(!clicked){
                    $('.input').append('<div class="notification error"><p style="color:red;">Warning: If you tried to report a link again before open your account will be suspended.</p></div>')
                    $code = $('.input input[]').val();
                }else{
                $.post('apis/report.php',{id:id},(data)=>{
                    if(data == 'success'){
                        change();
                    }else{
                        change();
                    }
                    });
                    
        }});
        function link_auth(){
                code = $('#link_id').val();
                id = $('#id').val();
                if(! clicked){
                    $('.msg').append('<div class="notification error">Open the link first and you will get an ID put the ID here.\nWarning: If you tried to report a link again before open your account will be suspended.</p></div>')
                }
                
                if(id === code){
                    $.post('/apis/link_auth.php',{id:id},(data)=>{
                        if(data == 'success'){
                            $('.form').animate({opacity:0},2000);
                            $('.form').html('<p>+1 point added succefully</p>');
                            setInterval(() => {
                                change();
                            }, 7000);
                           
                        }else{
                            $('.msg').append('<div class="notification"><p style="color:red;">Wrong ID</p></div>')
                        }
                    });
                }else{
                    $('.msg').append('<div class="notification error">Try again</p></div>');
                }
            }
            setInterval(() => {
                $('.notification').remove();
            }, 7000);
</script>
</body>
</html>