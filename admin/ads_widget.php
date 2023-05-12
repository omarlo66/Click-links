<?php

include_once '../options.php';

$ad1 = get_options('ad_1');
$ad2 = get_options('ad_2');
$ad3 = get_options('ad_3');
$ad4 = get_options('ad_4');
?>
<div class="admin_form">
    <p>ad placement 1</p>
    <div class="input">
        <textarea id="ad_1">
            <?php echo $ad1;?>
        </textarea>
    </div>
    <p>ad placement 2</p>
    <div class="input">
        <textarea id="ad_2">
            <?php echo $ad2;?>
        </textarea>
    </div>
    <p>ad placement 3</p>
    <div class="input">
        <textarea id="ad_3">
            <?php echo $ad3;?>
        </textarea>
    </div>
    <p>ad placement 4</p>
    <div class="input">
        <textarea id="ad_4">
            <?php echo $ad4;?>
        </textarea>
    </div>
    <button onclick="save()">save</button>
</div>
<script>
    function save(){
        ad1 = $('#ad_1').val();
        ad2 = $('#ad_2').val();
        ad3 = $('#ad_3').val();
        ad4 = $('#ad_4').val();
        $.post('/admin/api/ads_widget.php',{new_ad:true,ad_1:ad1,ad_2:ad2,ad_3:ad3,ad_4:ad4},(data)=>{
            if(data == 'true'){
                alert('saved');
            }else{
                alert('error try again');
            }
        });
    }
</script>
