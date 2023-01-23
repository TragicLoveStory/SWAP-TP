<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <?php 
        session_start();
        require "config.php";
        require "userfunctions.php";
        $uri = $_SERVER['REQUEST_URI'];
        $fullUri = "https://localhost${uri}";
        if (!isset($_SESSION["ID"]) || !isset($_SESSION["role"])){
            echo "Must be logged in.";
            die();
        }
        elseif(!isset($_GET['editing']) || $_GET['editing'] != "true"){
            echo "Error.";
            die();
        }
        include "navbar.php";
        try {
            $con=mysqli_connect($db_hostname,$db_username,$db_password,$db_database);
            }
            catch (Exception $e) {
                printerror($e->getMessage(),$con);
            }
            if (!$con) {
                printerror("Connecting to $db_hostname", $con);
                die();
            }
            //else printok("Connecting to $db_hostname");
        $errorMessage="";
        if(isset($_GET['editing']) && $_GET['editing']==="true"){
            $query=$con->prepare("SELECT `title`,`content`,`userId` FROM `forum` WHERE `id` =?");
            $query->bind_param('i', $_SESSION['forumID']); //bind the parameters
            $query->execute();
            $result = $query-> get_result();
            $row = $result -> fetch_assoc();
        }  
        if(isset($_POST['Submit']) && $_POST['Submit'] === "Edit Thread"){
            if(!empty($_POST['title']) && !empty($_POST['content'])){
                require_once "forumFunctions.php";
                editThread($_POST['title'],$_POST['content'],$_SESSION['forumID']);
            }
            else{
                $errorMessage = "Error: No fields must be empty";
            }
        }
        if($_SESSION['ID'] !== $row['userId']){
            echo "Access forbidden. Unable to edit other user's threads";
            die();
        }
    ?>
    <div class='container editThreadDivision'>
        <p style='text-align: center;'><?= $errorMessage ?></p>
        <form action="<?= $fullUri ?>" method='post'>
        <label for='title'>Title:</label><br>
        <input type='text' name='title' value="<?= $row['title'] ?>"><br>
        <label for='content'>Content:</label><br>
        <textarea name="content" rows="8" cols="50" style="resize:none"><?= $row['content'] ?></textarea><br>
        <input type='submit' value='Edit Thread' name='Submit'>
        </form>
    </div>
    <?php include "footer.php"; ?>
</body>
<!-- JavaScript Bundle with Popper -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<!-- Include every Bootstrap JavaScript plugin and dependency  -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://www.w3schools.com/lib/w3.js"></script> <!-- Include EVERY OTHER HTML Files to this file-->
<script>
    //to bring in other HTML on the fly into this page
    w3.includeHTML();
</script>
</html>