<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
    <?php 
     require "config.php";
     require "userFunctions.php";
     require "Authentication.php";
     session_start();
     if (!isset($_SESSION["ID"]) || !isset($_SESSION["role"]) || $_SESSION['status'] !== -1){
        echo 'Permission Denied';
        die();
     }
     if(isset($_POST['Submit']) && $_POST['Submit'] === "Change Password"){
        if(!empty($_POST['password']) && !empty($_POST['confirmPassword'])){
            if($_POST['password'] === $_POST['confirmPassword']){
                firstPasswordChange($_POST['password'],$_POST['confirmPassword']);
            }
            else{
                echo "Error: Passwords do not match.<br>";
                die();
            }
        }
        else{
            echo "Error: Fields are empty<br>";
            die();
        }
    }
    ?>
    <form method="post" action="firstPassChange.php">
        <input type="text" name="password" placeholder="Password">
        <input type="text" name="confirmPassword" placeholder="Confirm Password">
        <input type="submit" value="Change Password" name="Submit" class="button">
    </form>
</body>
<style>
    * {
        box-sizing: border-box;
    }

    body {
        background-color: #eeeeee;
    }

    img {
        display: block;
        width: 80px;
        margin: 30px auto;
        box-shadow: 0 5px 10px -7px #333333;
        border-radius: 50%;
    }

    form {
        background-color: #ffffff;
        width: 400px;
        margin: 50px auto 10px auto;
        padding: 30px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px -3px #333;
        text-align: center;
    }

    input {
        border-radius: 100px;
        padding: 10px 15px;
        width: 60%;
        border: 1px solid #D9D9D9;
        outline: none;
        display: block;
        margin: 20px auto 20px auto;
    }

    .button {
        border-radius: 100px;
        border: none;
        background: #719BE6;
        width: 60%;
        padding: 10px;
        color: #FFFFFF;
        margin-top: 25px;
        box-shadow: 0 2px 10px -3px #719BE6;
        display: block;
        margin: 55px auto 10px auto;
    }

    a {
        text-align: center;
        margin-top: 30px;
        color: #719BE6;
        text-decoration: none;
        padding: 5px;
        display: inline-block;
    }

    a:hover {
        text-decoration: underline;
    }
</style>
<!-- JavaScript Bundle with Popper -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<script src="https://www.w3schools.com/lib/w3.js"></script> <!-- Include EVERY OTHER HTML Files to this file-->
<script>
    //to bring in other HTML on the fly into this page
    w3.includeHTML();
</script>
</html>