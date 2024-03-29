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
<body class="backgroundImage">
    <?php 
        session_start();
        require "config.php";
        require "userfunctions.php";
        include "navbar.php";
        if (!isset($_SESSION["ID"]) || !isset($_SESSION["role"])){
            echo "Must be logged in.";
            die();
        }
        $errorMessage="";
        if(isset($_POST['Submit']) && $_POST['Submit'] === "Contact Us"){
            if(!empty($_POST['subject']) && !empty($_POST['body'])){
                require_once "mailFunctions.php";
                contactMail($_POST['subject'],$_POST['body']);
            }
            else{
                $errorMessage = "Error: No fields should be empty<br>";
            }
        }   
    ?>

    <div class="container">
        <div class="loginForm">
            <p style="color: #FFFFFF; text-align: center; font-size: 24px; text-decoration: underline;">Contact Us</p>
            <form action="contactUs.php" method='post'>
                <label for='subject' style='color: #FFFFFF;'>Subject:</label>
                <input type='text' name='subject' class="contactSubjectField">
                <label for='subject' style='color: #FFFFFF;'>Body:</label>
                <textarea name="body" rows="8" cols="50" style="resize:none" class="textareaField"></textarea>
                <input type='submit' value='Contact Us' name='Submit' class="signInButton">
            </form>
            <p style="color: #FFFFFF; text-align: center; margin-top: 1rem;"><?= $errorMessage ?></p>
        </div>
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