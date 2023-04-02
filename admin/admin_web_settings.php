<?php require_once '../options.php';?>
<p>website title</p>
<input type="text" id="title" value="<?php echo get_options('title');?>">
<p>welcome header</p>
<input type="text" id="welcome_header" value="<?php echo get_options('welcome_header');?>">
<p>welcome content</p>
<textarea id="welcome_content"><?php echo get_options('welcome_content');?></textarea>
<p>logo</p>
<input type="text" id="logo" value="<?php echo get_options('logo'); ?>">
<button onclick="update_web_settings()">Update</button>

<script>
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
</script>