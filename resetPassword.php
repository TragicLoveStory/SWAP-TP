<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> -->
</head>
<body>
    <?php 
        session_start();
        require "config.php";
        if (!isset($_SESSION["ID"]) || !isset($_SESSION["role"])){
            echo "Must be logged in.";
            die();
        }
        $uri = $_SERVER['REQUEST_URI'];
        $fullUri = "http://localhost${uri}";
        if(isset($_POST['Submit']) && $_POST['Submit'] === "Reset Password"){
            if(!empty($_POST['resetPassword']) && !empty($_POST['confirmPassword'])){
                if($_POST['resetPassword'] === $_POST['confirmPassword']){
                    require_once "mailFunctions.php";
                    resetPassword($_GET['s'],$_POST['resetPassword']);
                }
                else{
                    echo "Passwords do not match.";
                }
            }
            else{
                echo "Error: No fields should be empty<br>";
            }
        }   
    ?>
    <b><p style="text-align: center;">Reset Password</p></b>
    <form action="<?= $fullUri ?>" method='post' style="text-align: center;">
        <label for='resetPassword'>Please enter a new password:</label><br>
        <input type='text' name='resetPassword'><br>
        <label for='confirmPassword'>Confirm new password:</label><br>
        <input type='text' name='confirmPassword'><br>
        <input type='submit' value='Reset Password' name='Submit'>
    </form>
</body>
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="https://www.w3schools.com/lib/w3.js"></script> <!-- Include EVERY OTHER HTML Files to this file-->
<script>
    //to bring in other HTML on the fly into this page
    w3.includeHTML();
</script>
</html>