


<?php
include_once '../options.php';
$msgs_not_read = get_messages_not_read();
$all_msgs = get_all_messages();

?>
<div class="input">
    <input type="text" id="search" placeholder="search">

    <select id="status">
        <option value='unread'>unread</option>
        <option value='all'>all</option>
    </select>
</div>
<?php
if(!$msgs_not_read){
    echo "no messages";
    return ;
}
foreach($msgs_not_read as $msg){
    $user = get_user($msg['user_id']);
    if($user){
        $user_id = $user->id;
        $user = $user->name;
    }else{
        $user = '';
        $user_id = 0;
    }
    echo "<div class='msg' id='".$msg['id']."'>";
    echo "<div class='msg_header'>";
    echo "<div class='user_id'>".$user."</div>";
    echo "<div class='msg_title'>".$msg['subject']."</div>";
    echo "<div class='msg_date'>".$msg['date']."</div>";
    echo "<button onclick=open_msg('".$msg['id']."')>open</button>";
    echo "</div></div>";
}
?>

<script>
    function open_msg(id){
        $.get('apis/contact.php?open='+id,function(data){
            data = JSON.parse(data);
            $('#'+id).html('');
            $('#'+id).append('<div class="msg_header"><div class="msg_title">'+data.subject+'</div><div class="msg_date">'+data.date+'</div><button onclick=delete_msg('+data.id+')>delete</button></div>');
            $('#'+id).append('<div class="msg_content">'+data.message+'</div><div><button onclick=reply('+data.id+')>reply</button></div>');
        });
    }
    function send_reply(id){
        var reply = $('#reply').val();
        $.post('admin/api/reply_msg.php',{reply:'<?php echo $user_id;?>',name:'admin',subject:'reply',message:reply},function(data){
            console.log(data)
            data = JSON.parse(data);
            if(data.status == 'ok'){
                $('#'+id).html('');
                $('#'+id).append('<div class="msg_header"><div class="msg_title">'+data.subject+'</div><div class="msg_date">'+data.date+'</div><button onclick=delete_msg('+data.id+')>delete</button></div>');
                $('#'+id).append('<div class="msg_content">'+data.message+'</div><div><button onclick=reply_msg('+data.id+')>reply</button></div>');
            }else{
                alert('error happened');
            }
        });
    }
    function reply(id){
        $('#'+id).append('<div class="send_reply"> <textarea id="reply"></textarea> </div><button onclick=send_reply('+id+')>send</button>');
    }
    $('#search').on('keyup',(e)=>{
        console.log(e);
        filter();
    });
    $('.input #status').on('change',function filter(){
        var search = $('#search').val();
        var filter = $('select').val();
        console.log(search,filter)
        $.get('admin/api/reply_msg.php?search='+search+'&filter='+filter,function(data){
            data = JSON.parse(data);
            $('.msg').html('');
            data.forEach(function(item){
                $('.msg').append('<div class="msg_header"><div class="msg_title">'+item.subject+'</div><div class="msg_date">'+item.date+'</div><button onclick=open_msg('+item.id+')>open</button></div>');
            });
        });
    });

    function delete_msg(id){
        $.get('apis/contact.php?delete='+id,function(data){
            $('#'+id).html('');
        });
    }
</script>