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
    
    $times = 0;
    $count_links = count(get_links());
    function shuffle_link(){
        global $times;
        global $count_links;
        $times++;
        if($times > $count_links){
            return false;
        }
        $links = get_links();
        $link = $links[array_rand($links)];
        if($link['author'] == current_user()['id']){
            return shuffle_link();
        }
        return $link;
    }
    $link = shuffle_link();
    if(!$link){
        header('Location: links.php');
    }
    $id = $link['link_id'];
    ?>
    <div class="get_link">
        <h1>get link</h1>
        <div class="form get_link">
        <button onclick="open_link()">Click here</button>

            <div style="height: 20px;"></div>
            <div class="input">
                <input type="text" id="id" placeholder="ID">
            </div>
            <div style="height: 20px;"></div>
            <button onclick="link_auth()">Check ID</button>
            <div style="height: 40px;"></div>
            <div>
                <button onlcick="report('<?php echo $id;?>')" class="report-btn">report</button>
                <div style="height: 20px;"></div>
                <button onlcick="change()">change</button>
            </div>
        </div>
        <div class="content">
            <?php echo get_options('links_page_content') || ''; ?>
        </div>
    </div>
    <script>
        let cliched = false;
        function open_link(){
                var link_src = '<?php echo $link['source'];?>';
                window.open(link_src,'_blank');
                cliched = true;
            }
            function report(id){
                if(!clicked){
                    $('.input').append('<div class="notification"><p style="color:red;">Warning: If you tried to report a link again before open your account will be suspended.</p></div>')
                    $('.input input[]')
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
            var id = document.getElementById('id').value;
            $.post('apis/link_auth.php',{id:id},(data)=>{
                console.log(data);
                if(data == 'success'){
                    change();
                }else{
                    $('.form').append('<div class="notification"><p style="color:red;">Wrong ID</p></div>')
                }
            });
        }
        
        setInterval(() => {
            $('.notification').remove();
        }, 3000);
    </script>
</body>
</html>