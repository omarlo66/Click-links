<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        if(! isset($_COOKIE['user_id'])){
            header('Location: login.php');
        }
        include 'options.php';
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>get link | <?php echo get_options('title'); ?> </title>
</head>
<body>
    
    <?php
    include 'header.php';
    
    $count_links = count(get_links());
    function shuffle_link(){
        global $count_links;
        $link = get_links();
        if($count_links > 0){
            $link = $link[rand(0,$count_links-1)];
            return $link;
        }
        
    }

    $link = shuffle_link();
    if(! $link){
            header('Location: no_links.php');
        
    }else{
         $link = $link;
        echo json_encode($link);
        $id = $link['link_id'];
//<script src="assets/script/links.js"></script>
    }
    ?>
        

    <div class="get_link">
        <h1>get link</h1>
        <div class="form get_link">
        <button onclick="open_link('<?php echo $link['source'];?>')">Click here</button>

            <div style="height: 20px;"></div>
            <div class="input">
                <input type="text" id="id" placeholder="ID">
            </div>
            <div style="height: 20px;"></div>
                <button onclick="link_auth('<?php echo $id; ?>')">Check ID</button>
            <div style="height: 40px;"></div>
            <div>
                <button onlcick="report()" class="report-btn">report</button>
                <div style="height: 20px;"></div>
                <button onlcick="change()">change</button>
            </div>
        </div>
        <div class="content">
            <?php echo get_options('links_page_content') || ''; ?>
        </div>
    </div>
    <script>
        let id = '<?php echo $id; ?>';
        let clicked = false;
        function open_link(src){
                var link_src = src;
                window.open(link_src,'_blank');
                clicked = true;
            }
            function report(){
                $('.report-btn').remove();
                console.log(id);
                if(!clicked){
                    $('.input').append('<div class="notification"><p style="color:red;">Warning: If you tried to report a link again before open your account will be suspended.</p></div>')
                    $code = $('.input input[]').val();

                }
                $.post('apis/report.php',{id:id},(data)=>{
                    console.log(data);
                    if(data == 'success'){
                        change();
                    }else{
                        change();
                    }
                });}
            function change(){
                open(window.location.href);
            }
        function link_auth(){
            code = $('#id').val();
            if(!clicked){

                $('.msg').append('<div class="notification error">Open the link first and you will get an ID put the ID here.\nWarning: If you tried to report a link again before open your account will be suspended.</p></div>')
                
                
            }
            
            if(id == code){
                $.post('apis/link_auth.php',{id:id},(data)=>{
                    console.log(data);
                    if(data == 'success'){
                        change();
                    }else{
                        $('.form').append('<div class="notification"><p style="color:red;">Wrong ID</p></div>')
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