<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'options.php'; ?>


    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo get_options('title');?></title>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="content">
    <?php
        if(isset($_GET['title'])){
            $page_id = $_GET['title'];
            $page = search_page($q=$page_id);
            if( user_logged_in() && current_user()['role'] == 'admin'){
                ?>
                    <a href="edit_page.php?id=<?php echo $page_id; ?>">
                        <div class="page_container">
                            <3>Add new page</3>
                            <p>Click here to add new page</p>
                        </div>
                    </a>
                <?php
            }
            if($page){
                print_r($page);
                $page = $page[0];
                echo '<h1>'.$page['title'].'</h1>';
                echo $page['content'];
            }else{
                echo '<p style="text-align:center;">Sorry this page is not found</p> ';
                ?>
                <div class="input">
                </p>try to seach for it</p>
                <input type="text" id="search" placeholder="search for page"><button id="search_btn">search</button>
                </div>
                <script>
                    $('#search_btn').click(function(){
                        var search = $('#search').val();
                        window.location.href = 'pages.php?title='+search;
                    });
                </script>
                <?php
            }
        }else{
            $pages = all_pages();
        //    show all pages
            if(user_logged_in() && current_user()['role'] == 'admin'){
                ?>
                    <a href="add_page.php">
                       add new page
                    </a>
                <?php
            }
            if($pages){
                foreach($pages as $page){
                    ?>
                        
                        <div class="page_widget">
                        <a href="pages.php?id=<?php echo $page['id'];?>">
                            <h3><?php echo $page['title'];?></h3>
                        </a>
                        </div>
                       
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