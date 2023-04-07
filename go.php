<?php

include 'options.php';

if(isset($_GET['link'])){
    $id = $_GET['link'];
    $id=str_replace('.php','',$id);
    $link = get_link('link_id',$id);
}else{
    echo "<h1>Error 404</h1>";
    return;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Copy id</title>
</head>
<body>
    <style>
    body{
        background: #000;
        color: #fff;
        font-family: sans-serif;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    </style>
    <h1>Copy id</h1>
    <p>Copy the id of the link you want to share</p>
        <input type="text" value="<?php echo $link->link_id;?>" id="id">
    <button onclick="copy()">copy</button>
    <script>
        function copy(){
            var id = document.getElementById('id').value;
            navigator.clipboard.writeText(id);
            alert('ID copied !');
        }
    </script>
</body>
</html>