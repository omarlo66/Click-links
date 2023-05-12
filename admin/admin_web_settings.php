<?php require_once '../options.php';?>

<div class="admin_form">

    <p>website title</p>
    <div class="input">
        <input type="text" id="title" value="<?php echo get_options('title');?>">
    </div>
    <p>Google Analytics ID</p>
    <div class="input">
        <input type="text" id="g_insights" value="<?php echo get_options('google_analytics');?>">
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

    <p>chat widget</p>
    <div class="input">
        <textarea id="chat_widget"><?php echo get_options('chat_widget');?></textarea>
    </div>

    <p>footer</p>
    <div class="input">
        <textarea id="footer"><?php echo get_options('footer');?></textarea>
    </div>
    <button onclick="update_web_settings()">Update</button>
</div>

<h2>Links Options</h2>
<div class="admin_form">
    <div class="input">
        <p>min points per link</p>
        <input type="number" id="min_points_per_link" value="<?php echo get_options('min_points_per_link');?>">
    </div>
    <div class="input">
        <p>point per click</p>
        <input type="number" id="point_per_click" value="<?php echo get_options('points_per_click');?>">
    </div>
    <div>
        <p>Maximum user links</p>
        <input type="number" id="max_user_links" value="<?php echo get_options('max_user_links');?>">
    </div>
</div>
<button onclick="update_links_options()">Update</button>

<div class="admin_form">
    <div class="input">
        <p>add to links</p>
        <input type="number" id="add_to_links" value="<?php echo get_options('add_to_links');?>">
    </div>
    <div class="input">
        <p>add to users</p>
        <input type="number" id="add_to_users" value="<?php echo get_options('add_to_users');?>">
    </div>
    <button onclick="update_count()">Save</button>
</div>
<div class="admin_form">
    <div class="input">
        <p>how much should refferal earn per user?</p>
        <input type="number" id="ref_points" value="<?php echo get_options('ref_points');?>">
    </div>
    <div class="input">
        <p>Welcome Bonus</p>
        <input type="number" id="new_ref_points" value="<?php echo get_options('new_ref_points');?>">
    </div>

    <button onclick="update_refferal()">Save</button>
</div>
<script>
    function update_refferal(){
        var ref_points = $('#ref_points').val();
        var new_ref_points = $('#new_ref_points').val();

        $.get('admin/api/update_web_settings.php?refferal=1&ref_points='+ref_points+'&new_ref_points='+new_ref_points,(data)=>{
            $('.msg').append('<h3 class="notification success"><p>'+data+'</p></h3>');
            setInterval(function(){
                $('.notification').remove();
            }, 7000);
        });
    }
    function update_count(){
        var add_to_links = $('#add_to_links').val();
        var add_to_users = $('#add_to_users').val();
        $.get('admin/api/update_web_settings.php',{add_to_links:add_to_links,add_to_users:add_to_users},function(data){
            $('.msg').append('<h3 class="notification success">'+data+'</h3>');
            setInterval(function(){
                $('.notification').remove();
            }, 10000);
        });
    }
    function update_web_settings(){
        var title = $('#title').val();
        var g4 = $('#g_insights').val();
        var welcome_header = $('#welcome_header').val();
        var welcome_content = $('#welcome_content').val();
        var logo = $('#logo').val();
        var chat_widget = $('#chat_widget').val();
        var footer = $('#footer').val();
        $.post('/admin/api/update_web_settings.php', {title: title, g_analytics: g4, welcome_header: welcome_header, welcome_content: welcome_content,logo: logo,chat_widget:chat_widget,footer:footer}, function(data){
            $('.msg').append('<h3 class="notification success">'+data+'</h3>');
            console.log(data);
        });
        setInterval(function(){
            $('.notification').remove();
        }, 10000);
    }
    function update_links_options(){
        var min = $('#min_points_per_link').val();
        var point_per_click = $('#point_per_click').val();
        var max_user_links = $('#max_user_links').val();
        $.post('admin/api/update_web_settings.php',{link_min_points:min,point_per_click:point_per_click,max_links:max_user_links},function(data){
            $('.msg').append('<h3 class="notification success">'+data+'</h3>');
            setInterval(function(){
                $('.notification').remove();
            }, 10000);
        });
    }
</script>