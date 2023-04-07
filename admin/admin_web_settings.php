<?php require_once '../options.php';?>

<div class="admin_form">

    <p>website title</p>
    <div class="input">
        <input type="text" id="title" value="<?php echo get_options('title');?>">
    </div>
    
    <p>welcome header</p>
    <div class="input">
    <input type="text" id="welcome_header" value="<?php echo get_options('welcome_header');?>">
    </div>

    <p>welcome content</p>
    <div class="input">
    <textarea id="welcome_content"><?php echo get_options('welcome_content');?></textarea>
    </div>

    <p>logo</p>
    <div class="input">
    <input type="text" id="logo" value="<?php echo get_options('logo'); ?>">
    </div>
    <button onclick="update_web_settings()">Update</button>
</div>
<h2>
    Menus
</h2>
<div class="msg"></div>
<div class="admin_form">
    <select id="menu">
        <option value="0">new menu</option>
        <?php
            $menus = get_menus();
            foreach($menus as $menu){
                echo '<option value="'.$menu['id'].'">'.$menu['title'].'</option>';
            }
        ?>
    </select>
            <div class="input pages">
                
            </div>
    </div>
    <button onclick="save_menu()">post</button>
    <button onclick="Delete_menu()">delete</button>
</div>
<script>
        $('#menu').change(function(){
        $('.pages').html('');
        var menu = $('#menu').val();
        console.log(menu);
        $.post('admin/api/menu.php?id='+menu,function(data){
            data = JSON.parse(data);
            data.forEach(function(item){
                $('.pages').append('<input type="checkbox" name="pages" value="'+item.id+'">'+item.title+'<br>');
            });
        });
    });
    $('.pages input').change(function(){
        var menu_pages = [];
        $('.pages input').each(function(){
            if($(this).is(':checked')){
                menu_pages.push($(this).val());
            }
        });
        console.log(menu_pages);
    });
    function update_web_settings(){
        var title = $('#title').val();
        var welcome_header = $('#welcome_header').val();
        var welcome_content = $('#welcome_content').val();
        var logo = $('#logo').val();
        $.post('apis/update_web_settings.php', {title: title, welcome_header: welcome_header, welcome_content: welcome_content,logo: logo}, function(data){
            $('.msg').append('<h3 class="notification success">'+data+'</h3>');
            console.log(data);
        });
        setInterval(function(){
            $('.notification').remove();
        }, 10000);
    }
    function save_menu(){
        var menu = $('#menu').val();
        var pages = menu_pages;
        console.log(pages);
        var title = $('').val();
        $.post('admin/api/menu.php',{menu:menu,title:title,pages:pages},function(data){
            console.log(data);
        });
    }

</script>