
    <div class="admin_page">
    <?php
    include '../options.php';
    include_once '../header.php';
    if(! isset($_COOKIE['user_id']) || current_user()['role'] != 'admin'){
        header('Location: index.php');
    }

    $pages = all_pages();

    if(isset($_GET['new_page'])){
        ?>

            <div class="admin_form">
                <div class="input">
                    <input type="text" name="title" placeholder="title">
                </div>

                <textarea name="content" id="" cols="30" rows="10" placeholder="content"></textarea>
                <button onclick="add_page()">Add page</button>
            </div>

            <script>
                function add_page(){
                    var title = $('input[name="title"]').val();
                    var content = $('textarea[name="content"]').val();
                    $.post('admin/api/pages.php',{new:0,title:title,content:content},function(data){
                        console.log(data);
                    })
                }
            </script>

        <?php
    }elseif(isset($_GET['edit'])){
        $id = $_GET['edit'];
        $page = get_page($id);
        
        ?>
        <div class="admin_form">
            <?php print_r($page);?>
            <input type="text" value="<?php echo $page->id;?>" hidden>
            <div class="input">
                <input type="text" id="title" value="<?php echo $page->title;?>">
            </div>
            <div>
                <textarea id="content">
                <?php echo $page->content;?>
                </textarea>
            </div>
            <button onclick="save_page()">save</button>
        </div>
        <script>
            function save_page(){
                var id = $('#id').val();
                var title = $('#title').val();
                var content = $('#cotent').val();
                $.post('api/pages.php?edit='+id,{edit:id,title:title,content:content},(data)=>{
                    console.log(data);
                    open('admin/add_page?id='+id)
                })
                
            }

        </script>
        <?php 

    }else{
    ?>
    <div class="options">
        <button onclick="add_new_page()">add page</a>
    </div>
<table>
    <tr>
        <th>title</th>
        <th>date</th>
        <th>edit</th>
        <th>delete</th>
    </tr>

    <?php

    foreach($pages as $page){
        ?>
        <tr>
            <td><?php echo $page['title'];?></td>
            <td><?php echo $page['date'];?></td>
            <td><a href="admin/add_page.php?edit=<?php echo $page['id'];?>">edit</a></td>
            <td><a href="admin/add_page.php?delete=<?php echo $page['id'];?>">delete</a></td>
        <?php
    }
}
    ?>
</table>

<script>
    function add_new_page(){
        $('.options').load('admin/add_page.php?new_page=0');
    }
</script>
</div>