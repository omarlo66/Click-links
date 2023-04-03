<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'options.php'; ?>

    <?php
    $pages = array();
    $page = '';
    if(isset($_GET['id'])){
        $page = get_page($_GET['id']);
        if(! $page){
            header('Location: pages.php');
        }
        $pages = all_pages();
    }
   
    ?>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo get_options('title');?></title>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="content">
    <?php
        if(isset($_GET['id'])){
            $page_id = $_GET['id'];

            if(current_user()['role'] == 'admin'){
                ?>
                    <a href="edit_page.php?id=<?php echo $page_id; ?>">
                        <div class="page_container">
                            <3>Add new page</3>
                            <p>Click here to add new page</p>
                        </div>
                    </a>
                <?php
            }
            echo $page['content'];
        }else{
        //    show all pages
            if(current_user()['role'] == 'admin'){
    
                ?>
                    <a href="add_page.php">
                       add new page
                    </a>
                <?php
            }
                if($pages){
                foreach($pages as $page){
                    ?>
                        <a href="pages.php?id=<?php echo $page['id'];?>">
                        <div class="page_container">
                            <3><?php echo $page['title'];?></3>
                            <p><?php 
                            echo str_split($page['content'],25)[0];
                            ?></p>
                        </div>
                        </a>
                    <?php
                }
                
                }
                else{
                    echo '<p style="text-align:center;">No pages found</p>';
                }
            
            }
    ?>

    <?php include 'footer.php'; ?>
</body>
</html>