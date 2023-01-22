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
    if(!isset($_SESSION["ID"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !=="USER-ADMIN"){
        echo "Only permitted for User Admins.";
        die();
    } 
    elseif(!isset($_GET['editing']) || $_GET['editing'] != "true"){
        echo "Error.";
        die();
    }

    if(isset($_POST['Submit']) && $_POST['Submit'] === "Edit Account"){
        if(!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['dateofbirth']) && !empty($_POST['contact']) && !empty($_POST['department'])){
            //echo "No fields are empty<br>";
            edituser($_POST['email'],$_POST['password'],$_POST['firstname'],$_POST['lastname'],$_POST['dateofbirth'],$_POST['contact'],$_POST['department'],$_POST['occupation']);
        } 
        else{
            echo "Error: No fields should be empty<br>";
        }
    }
    
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
        $uri = $_SERVER['REQUEST_URI'];
        $fullUri = "http://localhost${uri}";
        if(isset($_GET['editing']) && $_GET['editing']==="true"){
            $query=$con->prepare("SELECT * FROM `users` WHERE `ID` =?");
            $query->bind_param('i', $_SESSION['editUserId']); //bind the parameters
            $query->execute();
            $result = $query-> get_result();
            $row = $result -> fetch_assoc();
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
        <td><input type='text' name='password'><br></td>
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
        <td><input type='date' name='dateofbirth'  min="1900-01-01" max="<?= date('Y-m-d'); ?>" value="<?= $row['date_of_birth'] ?>"><br></td>
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
    <!-- <tr>
        <td><label for='role'>Role:</label></td>
        <td><input type='text' name='role' value= '<?= $row['role'] ?>'><br></td>
    </tr> -->
    <tr>
        <td></td>
        <td style='text-align: right;'><input type='submit' value='Edit Account' name='Submit'></td>
    </tr>  
    </table>
    </form>
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