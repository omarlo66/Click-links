<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'options.php';?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About | <?php echo get_options('title');?></title>
</head>
<body>
    <?php
    require_once 'header.php';
    $page_content = get_page('title', 'about');
    if(!$page_content){
        echo "<script>location.href = '/index.php'</script>";
        return;
    }
    ?>
    <h1><?php echo $page_content->title;?></h1>
    <div class="content">
    <?php echo $page_content->content;?>

    </div>
    <?php
    include 'footer.php';
    ?>
</body>
</html>