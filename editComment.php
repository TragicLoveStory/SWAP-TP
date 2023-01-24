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
        if (!isset($_SESSION["ID"]) || !isset($_SESSION["role"]) || !isset($_SESSION['commentId'])){
            echo "Must be logged in.";
            die();
        }
        elseif(!isset($_GET['editingComment']) || $_GET['editingComment'] != "true"){
            echo "Error.";
            die();
        }
        include "navbar.php";
        $errorMessage="";
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
        if(isset($_SESSION['commentId'])){
            $query=$con->prepare("SELECT `userId`, `forumId`, `comment` FROM `comments` WHERE `ID` = ?");
            $query->bind_param('i',$_SESSION['commentId']); //bind the parameters
            if($query->execute()){
                $result = $query-> get_result();
                $row = $result -> fetch_assoc();
            }  
        } 
        else{
            echo "FORBIDDEN.";
            die();
        } 

        if(isset($_SESSION['ID']) && isset($row['userId'])){
            if($_SESSION['ID'] !== $row['userId']){
                echo "Access forbidden. Unable to edit other user's threads";
                die();
            }
        }
        
        if(isset($_POST['Submit']) && $_POST['Submit'] === "Edit Comment"){
            if(!empty($_POST['comment'])){
                require_once "forumFunctions.php";
                editComment($_POST['comment'],$_SESSION['commentId'],$row['forumId']);
            }
            else{
                $errorMessage="Error: No fields should be empty";
            }
        }   
    ?>
    <div class='container leaveDivision' style='margin-top: 10rem; margin-bottom: 3rem;'>
        <form action="editComment.php?editingComment=true" method='post' style='text-align: center;'>
            <p style='text-align: center;'><b>Edit Comment</b></p><br>
            <label for='comment'>Comment:</label><br>
            <textarea name="comment" rows="8" cols="50" style="resize:none; margin-bottom: 1rem;"><?= $row['comment'] ?></textarea><br>
            <input type='submit' value='Edit Comment' name='Submit'>
        </form>
        <p style='text-align: center;'><?= $errorMessage ?></p>
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