<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
    <?php 
     require "config.php";
     require "userFunctions.php";
     session_start();
     if (isset($_SESSION["ID"]) && isset($_SESSION["role"])){
        echo '<form method="post" action="Authentication.php"><input type="submit" value="Sign Out" name="Submit2" class="button"></form>';
     }
     else{
        echo '<form method="post" action="Authentication.php"><input type="text" name="username" placeholder="Username"><input type="text" name="password" placeholder="Password"><input type="submit" value="Sign in" name="Submit" class="button"></form>';
     }
    ?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="https://www.w3schools.com/lib/w3.js"></script> <!-- Include EVERY OTHER HTML Files to this file-->
<script>
    //to bring in other HTML on the fly into this page
    w3.includeHTML();
</script>
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
</html>