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
        $uri = $_SERVER['REQUEST_URI'];
        $fullUri = "http://localhost${uri}";
        require_once "userFunctions.php";
        require_once "config.php";

        if(isset($_GET['editing']) && $_GET['editing']==="true"){
            $con=mysqli_connect($db_hostname,$db_username,$db_password,$db_database);
            if (!$con){
                die('Could not connect: ' . mysqli_connect_errno()); 
            }
            $userID= $_GET['TheUserId'];
            $query=$con->prepare("SELECT * FROM `users` WHERE `ID` =?");
            $query->bind_param('i', $userID); //bind the parameters
            $query->execute();
            $result = $query-> get_result();
            $row = $result -> fetch_assoc();
        }  

        if(isset($_POST['Submit']) && $_POST['Submit'] === "Edit Account"){
            if(!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['dateofbirth']) && !empty($_POST['contact']) && !empty($_POST['department']) && !empty($_POST['occupation']) && !empty($_POST['role'])){
                //echo "No fields are empty<br>";
                edituser($_POST['email'],$_POST['password'],$_POST['firstname'],$_POST['lastname'],$_POST['dateofbirth'],$_POST['contact'],$_POST['department'],$_POST['occupation'],$_POST['role'],$row['ID']);
            } 
            else{
                echo "Error: No fields should be empty<br>";
            }
        }
    ?>

    <form action="<?= $fullUri ?>" method='post'>
    <table style='text-align: left; margin-left: auto; margin-right: auto;'>
    <tr>
        <th></th>
        <th style='text-align: center;'>Edit Account</th>
    </tr>
    <tr>
        <td><label for='email'>Email:</label></td>
        <td><input type='text' name='email' value= '<?= $row['email'] ?>'><br></td>
    </tr>
    <tr>
        <td><label for='password'>Password:</label></td>
        <td><input type='text' name='password' value= '<?= $row['password'] ?>'><br></td>
    </tr>
    <tr>
        <td><label for='firstname'>First Name:</label></td>
        <td><input type='text' name='firstname' value= '<?= $row['first_name'] ?>'><br></td>
    </tr>
    <tr>
        <td><label for='lastname'>Last Name:</label></td>
        <td><input type='text' name='lastname' value= '<?= $row['last_name'] ?>'><br></td>
    </tr>
    <tr>
        <td><label for='dateofbirth'>Date Of Birth:</label></td>
        <td><input type='text' name='dateofbirth' value= '<?= $row['date_of_birth'] ?>'><br></td>
    </tr>
    <tr>
        <td><label for='contact'>Contact:</label></td>
        <td><input type='text' name='contact' value= '<?= $row['contact'] ?>'><br></td>
    </tr>
    <tr>
        <td><label for='department'>Department:</label></td>
        <td><input type='text' name='department' value= '<?= $row['department'] ?>'><br></td>
    </tr>
    <tr>
        <td><label for='occupation'>Occupation:</label></td>
        <td><input type='text' name='occupation' value= '<?= $row['occupation'] ?>'><br></td>
    </tr>
    <tr>
        <td><label for='role'>Role:</label></td>
        <td><input type='text' name='role' value= '<?= $row['role'] ?>'><br></td>
    </tr>
    <tr>
        <td></td>
        <td style='text-align: right;'><input type='submit' value='Edit Account' name='Submit'></td>
    </tr>  
    </table>
    </form>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="https://www.w3schools.com/lib/w3.js"></script> <!-- Include EVERY OTHER HTML Files to this file-->
<script>
    //to bring in other HTML on the fly into this page
    w3.includeHTML();
</script>
</html>