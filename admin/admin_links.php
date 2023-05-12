<?php
include_once '../options.php';
if(current_user()['role'] != 'admin'){
    header('location:../index.php');
    exit();
}
?>
<div>
    <select class="filter_links">
        <option value="active">active <?php echo count(get_links());?></option>
        <option value="pending">pending <?php echo count(get_unapproved_links());?></option>
        <option value="all" default>all <?php echo count(get_links()) + count(get_unapproved_links());?></option>
    </select>
</div>
<div class="links_dashboard">

</div>
<script>
    function approve_link(id){
        $.post('admin/api/approve_link.php',{id:id},function(data){
            console.log(data)
            if(data == 'success'){
                alert('link approved');
                location.reload();
            }
            else{
                alert('error');
            }
        })
    }
    function delete_link(id){
        $.post('admin/api/delete_link.php',{id:id},function(data){
            if(data == 'success'){
                alert('link deleted');
                location.reload();
            }
            else{
                alert('error maybe link not found');
            }
        });
    }
    function get_links(){
        let links = [];
        $.get('admin/api/get_links.php?get='+$('.filter_links').val(),function(data){
            $('.links_dashboard').html(data);
        });
    }
    $('.filter_links').change(function(){
        get_links();
        console.log($('.filter_links').val());
    });
    get_links();

    function get_user_name(id){
        var name = '';
        $.get('admin/api/get_user.php?id='+id,function(data){
            name = data;
            data = JSON.parse(data);
            $('.link_user').html('<p> name: '+data.name+'</p> <p> email: '+data.email+'</p>');
        });
        
    }
</script>