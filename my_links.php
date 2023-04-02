<!DOCTYPE html>
<html lang="en">
<head>
        <?php
            if(! isset($_COOKIE['user_id'])){
                header('Location: login.php');
            }
        ?>
    <?php include_once 'options.php';?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your links | <?php get_options('title');?></title>
</head>
<body>
    <?php include_once 'header.php';?>

    <h1>
        Your links
    </h1>
            <?php
            $links = get_link_by_user(current_user()['id']);
            ?>
    <table>
            <tr>
            <th>title</th>
            <th>remaining</th>
            <th>clicks</th>
            <th>status</th>
            </tr>
            <?php

            foreach($links as $row){
                $id = $row['id'];
                $link = $row['link'];
                $points = $row['points'];
                $clicks = $row['clicks'];
                $budget = $row['budget'];
                $status = $row['status'];
                
                echo "<tr><td>$link</td><td>$points / $budget</td><td>$clicks</td><td>$status</td><td><a href='edit_link?id=$id'>edit</a><a href='delete_link?id=$id'>edit</a></tr>";
            }

            ?>
        </table>

    

</body>
</html>