</div>
<div class="footer">
<div style="height: 40px;"></div>
<?php include_once 'options.php'; ?>
<?php echo get_options('chat_widget'); ?>


<div class="share_btn">
    <!-- Facebook -->
    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_options('url');?>" target="_blank">
        <i class="fa fa-facebook"></i>
    </a>

    <!-- Twitter -->
    <a href="https://twitter.com/intent/tweet?url=<?php echo get_options('url');?>&text=<?php echo get_options('welcome_content');?>" target="_blank">
        <i class="fa fa-twitter"></i>
    </a>

    <!-- LinkedIn -->
    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo get_options('url');?>" target="_blank">
        <i class="fa fa-linkedin"></i>
    </a>
</div>
<?php echo get_options('footer'); ?>
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script> 
<script>
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({pageLanguage: 'en'}, 'body');
    }
    googleTranslateElementInit();
    $('input[type="text"]').keypress(function(e){
        if(e.key == "'" || e.key == '"'){
            $('.msg').html('<h3 class="notification error">You can\'t use this character</h3>');
            $('input[type="text"]').val('');
        }
    });
</script>
<div style="height: 40px;"></div>
</div>