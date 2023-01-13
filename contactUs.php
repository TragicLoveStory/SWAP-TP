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
        require "userfunctions.php";
        if (!isset($_SESSION["ID"]) || !isset($_SESSION["role"])){
            echo "Must be logged in.";
            die();
        }

        if(isset($_POST['Submit']) && $_POST['Submit'] === "Contact Us"){
            if(!empty($_POST['subject']) && !empty($_POST['body'])){
                require_once "mailFunctions.php";
                contactMail($_POST['subject'],$_POST['body']);
            }
            else{
                echo "Error: No fields should be empty<br>";
            }
        }   
    ?>
    <form action="contactUs.php" method='post'>
        <label for='subject'>Subject:</label><br>
        <input type='text' name='subject'><br>
        <textarea name="body" rows="8" cols="50" style="resize:none"></textarea><br>
        <input type='submit' value='Contact Us' name='Submit'>
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