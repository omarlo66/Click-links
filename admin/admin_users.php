<?php
include_once '../options.php';
global $sql;
$users = $sql->query("SELECT * FROM users");
if(isset($_GET['delete_user'])){
    $id = $_GET['delete_user'];
    $sql->query("DELETE FROM users WHERE id = $id");
    header('location: admin.php');
}
elseif(isset($_GET['view'])){
    $user = get_user($_GET['view']);

    ?>
    <div class="form">
        <input type="text" id="id" value="<?php echo $user->id;?>" hidden>
        <div class="input">
            <p>name:</p> 
            <input type='text' id="username" value = "<?php echo $user->name;?>" disabled>
        </div>
        <div class="input">
            <p>points:</p> 
            <input type='text' id ="points" value = "<?php user_points($user->id);?>" disabled>
        </div>
        <div class="input">
            <p>role:</p> 
            <input type='text' id="role" value = "<?php echo $user->role;?>" disabled>
        </div>
        <div class="users_page">
            <p>links:</p>
            <table>
                <tr>
                    <th>link</th>
                    <th>date</th>
                    <th>budget</th>
                    <th>clicks</th>
                </tr>
            <?php 
            global $sql;
            $links = get_link_by_user($user->id);
            if(! $links){
                echo "no links";
            }
            foreach($links as $link){
                ?>
                <tr>
                    <td><a href="<?php echo $link->source;?>" target='__blank'><?php echo $link->link;?></a></td>
                    <td><p>published date: <?php echo $link->date;?></p></td>
                    <td><p>Budget:<?php echo $link->budget;?> </p></td>
                    <td><p>clicks:<?php echo $link->clicks;?> </p></td>
                </tr>
                </div>
                <?php
            }
            ?>
            </table>
        </div>
        <div>
            <h2>reffered users</h2>
            <table>
                <tr>
                    <th>User_id</th>
                    <th>name</th>
                    <th>email</th>
                </tr>
                <?php
                $referals = get_referrals($user->id);
                if(! $referals){
                    echo "no referals";
                }
                foreach($referals as $referal){
                    ?>
                    <tr>
                        <td><?php echo $referal->id;?></td>
                        <td><?php echo $referal->name;?></td>
                        <td><?php echo $referal->email;?></td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
        <h2>points</h2>
        <div class="points">
            <table>
                <tr>
                    <th>date</th>
                    <th>points</th>
                    <th>reason</th>
                </tr>
                <?php
                $points = user_points($user->id);
                if(! $points){
                    echo "no points";
                }
                foreach($points as $point){
                    ?>
                    <tr>
                        <td><?php echo $point->date;?></td>
                        <td><?php echo $point->points;?></td>
                        <td><?php echo $point->reason;?></td>
                    </tr>
                    <?php
                    global $sql;
                    $points_summary = $sql->query("UPDATE users SET points = $points WHERE id = $user->id");
                    foreach($points_summary as $point_summary){
                        ?>
                        <tr>
                            <td><?php echo $point_summary->date;?></td>
                            <td><?php echo $point_summary->points;?></td>
                            <td><?php echo $point_summary->title;?></td>
                        </tr>
                        <?php
                    }
                    }
                    ?>
                </table>
            </div>
        <div>
            <button class="edit">Edit</button>
        </div>
    </div> 
    <?php
}
else{
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
        <td><button onclick="View(<?php echo $user['id'];?>)">show</button></td>
        </tr>
        <?php
    }
    ?>
</table>
        <?php
}
?>


<script>
    $('#search').on('keyup', function(){
        var value = $(this).val().toLowerCase();
        $('table tr').filter(function(){
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
    function View(id){
        $('.form').load('/admin/admin_users.php?view='+id);
    }
    $('.edit').click(()=>{
        console.log('edit');
        $('.input input').removeAttr('disabled');
        $('.edit').html('save');
        $('.edit').attr('onclick','save()');
    });
    function save(){
        $username = $('#username').val();
        $points = $('#points').val();
        $role = $('#role').val();
        id = $('#id').val();
        $.post('/admin/admin_users.php',{edit:id,username:$username,points:$points,role:$role},(data)=>{
            $('.msg').html(data)
        });
        $('.input input').attr('disabled','disabled');
        $('.edit').html('edit');
        $('.edit').attr('onclick','edit()');
    }
</script>

<?php

if(isset($_POST['edit'])){
    $id = $_POST['edit'];
    $username = $_POST['username'];
    $points = $_POST['points'];
    $role = $_POST['role'];
    $user_points = user_points($id);
    $deduct =  $points - $user_points;
    $sql->query("UPDATE users SET name = '$username',role = '$role' WHERE id = $id");
    update_points($id,$deduct);
    echo 'done';
}


?>