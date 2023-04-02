<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'options.php';
    if(! isset($_COOKIE['user_id'])){
        header('Location: login.php');
    }
    $user = current_user();
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Great Job <?php echo $user['name'];?>| </title>
</head>
<body>
    <?php include 'header.php';?>
    <h1>
        Great Job <?php echo $user['name'];?>
    </h1>
    <p>
        There are no links availble right now!<br>
        try again later  
        or  <a href="add_link.php">add link</a>
    </p>
</body>
</html>