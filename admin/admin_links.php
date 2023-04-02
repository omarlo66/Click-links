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
        $.get('admin/api/delete_link.php',{id:id},function(data){
            if(data == 'success'){
                alert('link deleted');
                location.reload();
            }
            else{
                alert('error');
            }
        });
    }
    function get_links(){
        $.get('admin/api/get_links.php',function(data){
            if($('.filter_links').val() == 'all'){
            var links = JSON.parse(data);
            var html = '';
            for(var i = 0; i < links.length; i++){
                html += '<div class="admin_link">';
                html += '<div class="link_title">'+links[i][1]+'</div>';
                html += '<a href="'+links[i][2]+'">'+links[i][2]+'</a>';
                html += '<div class="link_description">'+links[i][3]+'</div>';
                html += '<div class="link_status" style="padding:7px; background-color: green; color: #fff; width: fit-content; border-radius: 10px;">'+links[i][9]+'</div>';
                html += '<div class="link_actions">';
                if(links[i][9] == 'pending'){
                    html += '<button onclick="approve_link('+links[i][0]+')">Approve</button>';
                }
                html += '<button onclick="delete_link('+links[i][0]+')">Delete</button>';
                html += '</div>';
                html += '</div>';
            }
        }
        else{
            var links = JSON.parse(data);
            var html = '';
            for(var i = 0; i < links.length; i++){
                if(links[i][9] == $('.filter_links').val()){
                    html += '<div class="admin_link">';
                html += '<div class="link_title">'+links[i][1]+'</div>';
                html += '<a href="'+links[i][2]+'">'+links[i][2]+'</a>';
                html += '<div class="link_description">'+links[i][3]+'</div>';
                html += '<div class="link_actions">';
                html += '<button onclick="approve_link('+links[i][0]+')">Approve</button>';
                html += '<button onclick="delete_link('+links[i][0]+')">Delete</button>';
                html += '</div>';
                html += '</div>';
                }
            }
        }

            $('.links_dashboard').html(html);
        });

    }
    $('.filter_links').change(function(){
        get_links();
        console.log($('.filter_links').val());
    });
    get_links();
</script>