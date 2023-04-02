<?php

include_once '../options.php';
global $sql;
$users = $sql->query("SELECT * FROM users");
?>

<input type="text" id="search" placeholder="search">
<table>
    <tr>
    <th>name</th>
    <th>email</th>
    <th>role</th>
    <th>action</th>
    </tr>
    <?php
    foreach($users as $user){
        ?>
        <tr>
        <td><?php echo $user['name'];?></td>
        <td><?php echo $user['email'];?></td>
        <td><?php echo $user['role'];?></td>
        <td><a href="?delete=<?php echo $user['id'];?>">delete</a></td>
        </tr>
        <?php
    }?>
</table>
<script>
    $('#search').on('keyup', function(){
        var value = $(this).val().toLowerCase();
        $('table tr').filter(function(){
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
</script>

<?php



?>