<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once 'options.php';
    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $link = get_link('id',$id);
        $link_id = $link->link_id; 
        $budget = $link->budget;
        $url = $link->link;
        $source = $link->source;
    }
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>edit <?php echo $id;?></title>
</head>
<body>
<?php require_once 'header.php'; ?>
<link rel="stylesheet" href="../assets/style.css">
    <div class="edit_link form">
       
       <h1>Edit <?php echo $link_id?></h1>
         <div class="input">
            <p style="text-align:left;">link</p>
            <input type="text" name="link" value="<?php echo $url;?>" placeholder="Link">
        </div>    
        <div class="input">
            <p style="text-align:left;">budget</p>
            <input type="text" name="budget" value="<?php echo $budget;?>" placeholder="budget">
        </div>    
            <input type="hidden" name="id" value="<?php echo $id;?>">
        <button onclick="Edit_link()">Edit</button>
    </div>
<script>
    function Edit_link(){
        var link = $('input[name="link"]').val();
        var budget = $('input[name="budget"]').val();
        var id = $('input[name="id"]').val();
        if(id == '' || link == '' || budget == ''){
            alert('Please fill all the fields');
            return;
        }
        $.post('apis/edit_link.php', { link: link, budget: budget, id: id}, function(data){
            $('.msg').append('<h3 class="notification success">'+data+'</h3>');
        });
        setInterval(function(){
            $('.notification').remove();
        }, 9000);
    }
</script>
</body>
</html>