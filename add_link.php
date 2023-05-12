<!DOCTYPE html>
<html lang="en">
<head>
    <?php require 'options.php';?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add link | title</title>
</head>
<body>
    <?php include 'header.php';?>
    <?php
    if(! current_user() && current_user()['id'] == 0){
        header('location: login.php');
        exit;
    }
    $user_id = current_user()['id'];
    $link = get_user_meta($user_id, 'next_link');
    if($link && $link != ''){
        $link = $link;
    }else{
        $link = generate_unique_link_id();
    }
    ?>
    <div class="add_link form">
        <input type="hidden" name="generated_link_id" id="generated_link_id" value="<?php echo $link; ?>">
        <h1>Add link</h1>
        <p>generated link:</p>
        <div class="input">
            <input type="hidden" name="generated_link" id="generated_link" value="<?php echo get_options('url').'go/'.$link ?>" readonly>
            <div id="copy_link"><p><?php echo get_options('url').'go/'.$link ?>   <i class="fa fa-copy" style="color:#000; size: 20px;"></i></p></div>
        </div>

        <p>link:</p>
        <div class="input">
        <input type="url" name="link" placeholder="Link" id="link">
        </div>

        <p>Source Link:</p>
        <div class="input">    
        <input type="url" name="source" placeholder="Source link" id="src">
        </div>

        <p>Budget:</p>
        <div class="input"> 
            <input type="number" name="budget" placeholder="Points budget for this link" id="budget" min="<?php echo get_options('min_points_per_link');?>">
        </div>

        <p class="hint">suggested: <?php echo get_options('min_points_per_link');?> points minimum</p>
        <div style="height: 20px;"></div>
        <button id="add_link">add link</button>
        <div style="height: 20px;"></div>
        <input type="hidden" name="user_points" id="user_points" value="<?php echo user_points($user_id);?>">
    </div>

    <script>
        
        
        
        $('#copy_link').click(()=>{
            var link = $('#generated_link').val();
            navigator.clipboard.writeText(link);
            var link_id = $('#generated_link_id').val();
            $.get('/apis/add_link?link='+link_id, function(data){
                console.log(data);
            });
            $('.msg').append('<h3 class="notification success" onclick="remove(this)">Link copied to clipboard</h3>');
            setInterval(function(){
                $('.notification').remove();
            }, 5000);
        });

        $('#add_link').click(function(){
            let link_id = $('#generated_link_id').val();
            var link = $('#link').val();
            var src = $('#src').val();
            var budget = $('#budget').val();
            if(src == '' || budget == '' || link == ''){
                $('.msg').html('<div class="notification error">Please fill all the fields</div>');
                return;
            }
            var min_points = '<?php echo get_options('min_points_per_link');?>';
            let user_points = $('#user_points').val();
            user_points = parseInt(user_points);
            budget = parseInt(budget);
            if( budget < min_points){
                $('.form').html('<div class="error"> Minimum points for a link is '+min_points+'</div>');
                return;
            }
            
            if(user_points-budget < 0){
                $('.form').html('<div class="error"> You don\'t have enough points to add this link make a deposit to your account or earn from <a href="/links.php">link exchange</a></div>');
                return;
            }
            if(link == '' || src == '' || budget == ''){
                alert('Please fill all the fields');
                return;
            }
            let code = $('#generated_link_id').val();
            $.post('apis/add_link.php', {link_id: code, link: link, src: src, budget: budget}, function(data){
                $('.msg').append('<h3 class="notification success">'+data+'</h3>');
                setInterval(function(){
                    document.location.href = '/user.php';
                }, 3000);
            });
            setInterval(function(){
                $('.notification').remove();
            }, 3000);
            $('#link').val('');
            $('#src').val('');
            $('#points').val('');
            $('.add_link').append('<div class="notification success"><p style="color:#fff;">You can find your link insights on your dashboard <a href="/user.php">view insights</a></p></div>');
        });

        $('#link').keyup(function(){
            var link = $('#link').val();
            if(link == '' || ! link.includes('https://')){
                $('#link').css('border', '3px solid red');
                return;
            }
            $('#link').css('border', '3px solid green');
        });
        $('#src').keyup(function(){
            var src = $('#src').val();
            if(src == '' || ! src.includes('https://')){
                $('#src').css('border', '3px solid red');
            }
            $('#src').css('border', '3px solid green');
        });

    </script>
</body>
</html>
