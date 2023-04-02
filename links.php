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
        if($times == $count_links){
            header("Location: no_links.php");
            return ;
        }
        $times++;
        $links = get_links();
        $shuffle = random_int(1, count($links)-1);
        $author = $links[$shuffle]['author'];
            if($author == current_user()['id'] or get_traffic($links[$shuffle]['link_id'],current_user()['id'])){
                shuffle_link();
                $links = array();
            }
        return $links[$shuffle];
    }
    $link = shuffle_link();
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
            <button onclick="link_auth()">Done</button>
            <div style="height: 20px;"></div>
            <div>
                <a href="report?id=<?php echo $id;?>">report</a>
                <a href="change?id=<?php echo $id;?>">change</a>
            </div>
        </div>
        <div class="content">
            <h2>Remember</h2>
                <p>Remember if link is not working or doesn't show any ID close it and report to make link exchange better. <br> <p>thanks for coraporation</p></p>
            <h3>How it's work</h3>
                <ul>1. Click on the button</ul>
                <ul>2. Wait for the link to open</ul>
                <ul>3. Copy the ID</ul>
                <ul>4. Paste the ID in the input</ul>
                <ul>5. Click on done</ul>
        </div>
    </div>
    <script>
        function open_link(){
            var link_src = '<?php echo $link['source'];?>';
            window.open(link_src,'_blank');
        }
        function link_auth(){
            var id = document.getElementById('id').value;
            $.post('apis/link_auth.php',{id:id},(data)=>{
                console.log(data);
                if(data == 'success'){
                    open(window.location.href);
                }else{
                    alert('link not added');
                }
            });
        }
    </script>
</body>
</html>