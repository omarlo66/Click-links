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
    if(!user_logged_in()){
        header('location: login.php');
        exit;
    }
    $generated_link_id = generate_unique_link_id();
    ?>
    <div class="add_link form">
        <h1>Add link</h1>
        <p>generated link:</p>
        <div class="input">
            <input type="text" name="generated_link" id="generated_link" value="<?php echo get_options('url');?>go/<?php echo $generated_link_id;?>" readonly>
            <button id="copy_link">copy</button>
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
            <input type="number" name="budget" placeholder="point per click" id="budget">
        </div>

        <p class="hint">suggested: 5 points minimum</p>
        <div style="height: 20px;"></div>
        <button id="add_link">add link</button>
        <div style="height: 20px;"></div>
    </div>

    <script>
        $('#copy_link').click(()=>{
            var link = $('#generated_link').val();
            navigator.clipboard.writeText(link);
            $('.msg').append('<h3 class="notification success" onclick="remove(this)">Link copied to clipboard</h3>');
            setInterval(function(){
                $('.notification').remove();
            }, 5000);
        });

        $('#add_link').click(function(){
            let link_id = "<?php echo $generated_link_id;?>";
            var link = $('#link').val();
            var src = $('#src').val();
            var budget = $('#budget').val();
            if(link == '' || src == '' || budget == ''){
                alert('Please fill all the fields');
                return;
            }
            $.post('apis/add_link.php', {link_id: link_id, link: link, src: src, budget: budget}, function(data){
                console.log(data)
                $('.msg').append('<h3 class="notification success">'+data+'</h3>');
            });
            setInterval(function(){
                $('.notification').remove();
            }, 3000);
            $('#link').val('');
            $('#src').val('');
            $('#points').val('');
            $('.add_link').append('<p>You can find your link insights on your dashboard <a href="user.php">view insights</a></p>');
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
