

<div class="admin_form">
    <select id="page">
        <option value="0">new page</option>
    </select>
    <div class="input">
        <input type="text" name="title" id="title">
    </div>
    <div class="input">
        <textarea name="content" id="content" cols="30" rows="10"></textarea>
    </div>
    <select id='status'>
        <option value="draft">draft</option>
        <option value="published">published</option>
    </select>
    <button onclick="Save_page()">post</button>
    <button onclick="Delete_page()">delete</button>
</div>

<script>
    $('#content').on('keyup',(e)=>{
        console.log(e.button);
    });
    $.get('admin/api/pages.php?page',(data)=>{
        data = JSON.parse(data);
        data.forEach(page => {
            $('#page').append('<option value="'+page.id+'">'+page.title+'</option>');
        });
    });

    $('#page').change(()=>{
        if($('#page').val() == 0){
            $('#title').val('');
            $('#content').val('');
            return;
        }
        $.get('admin/api/pages.php?page='+$('#page').val(),(data)=>{
            data = JSON.parse(data);
            $('#title').val(data.title);
            $('#content').val(data.content);
        });
    });

    function notification(msg,type){
        $('.admin_form').append('<div class="notification '+type+'">'+msg+'</div>');
        setTimeout(() => {
            $('.notification').remove();
        }, 2000);
    }
    function Save_page(){
        var title = $('#title').val();
        var content = $('#content').val();
        var status = $('#status').val();
        var id = $('#page').val();
        if(id == 0){
            $.post('admin/api/pages.php',{new:true,title:title,content:content,status:status},(data)=>{
                console.log(data);
                $('#page').append('<option value="'+data+'">'+title+'</option>');
                $('#page').val(data);
                notification(title+' page added','success');
            });
        }
        else{
            $.post('admin/api/pages.php',{edit:id,title:title,content:content,status:status},(data)=>{
                notification(title+' page updated','success');
            });
        }
    }
    function Delete_page(){
        var id = $('#page').val();
        email = prompt('write the admin email to delete','@gmail.com');
        if(email != <?php echo "'".current_user()['email']."'"; ?>){
            notification('wrong email','error');
            return;
        }
        $.get('admin/api/pages.php?delete='+id,(data)=>{
            console.log(data);
            $('#page option[value="'+id+'"]').remove();
            $('#page').val(0);
            $('#title').val('');
            $('#content').val('');
        });
    }

</script>
<?php




?>