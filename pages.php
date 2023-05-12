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
    <?php echo "<div class='ads_widget'>".get_options('ad_1')."</div>";?>
        <?php
        if(isset($_GET['id'])){
            $page_id = $_GET['id'];
            $page = get_page('id', $page_id);
            if($page){
                echo '<h1>'.$page->title.'</h1>';
                echo "<div>".$page->content."</div>";
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
                        window.location.href = '/pages.php?title='+search;
                    });
                </script>
                <?php
            }
        }else{
            $pages = all_pages();
        //    show all pages
            if($pages){
                foreach($pages as $page){
                    ?>
                        
                        <div class="page_widget">
                        <a href="/pages.php?id=<?php echo $page['id'];?>">
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
    <?php echo "<div class='ads_widget'>".get_options('ad_2')."</div>";?>
    <?php echo "<div class='ads_widget'>".get_options('ad_3')."</div>";?>
    <?php include 'footer.php'; ?>
</body>
</html>