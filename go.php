<?php

include 'options.php';
if(isset($_GET['go'])){
    $id = $_GET['go'];
    $link = get_link('link_id',$id);
}else{
    echo "<h1>Error 404</h1>";
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