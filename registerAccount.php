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
        session_start();
        require "userfunctions.php";
        require "config.php";
        if(!isset($_SESSION["ID"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !=="USER-ADMIN"){
            echo "Only permitted for User Admins.";
            die();
        }
        include "navbar.php";
        $errorMessage="";
        if(isset($_POST['Submit']) && $_POST['Submit'] === "Create Account"){
            if(!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['dateofbirth']) && !empty($_POST['contact']) && !empty($_POST['department'])){
                require_once "userFunctions.php";
                adduser($_POST['email'],$_POST['password'],$_POST['firstname'],$_POST['lastname'], $_POST['dateofbirth'],$_POST['contact'],$_POST['department'],$_POST['occupation']);
            } 
            else{
                $errorMessage="Error: No fields should be empty<br>";
            }
        }
    ?>
    <div class='container editUserDivision'>
        <p style='text-align: center;'><?= $errorMessage ?></p>
        <form action="registerAccount.php" method='post'>
        <table style='text-align: left; margin-left: auto; margin-right: auto;'>
        <tr>
            <th></th>
            <th style='text-align: center;'>Create An Account</th>
        </tr>
        <tr>
            <td><label for='email'>Email:</label></td>
            <td><input type='text' name='email'><br></td>
        </tr>
        <tr>
            <td><label for='password'>Password:</label></td>
            <td><input type='password' name='password'><br></td>
        </tr>
        <tr>
            <td><label for='firstname'>First Name:</label></td>
            <td><input type='text' name='firstname'><br></td>
        </tr>
        <tr>
            <td><label for='lastname'>Last Name:</label></td>
            <td><input type='text' name='lastname'><br></td>
        </tr>
        <tr>
            <td><label for='dateofbirth'>Date Of Birth:</label></td>
            <td><input type='date' name='dateofbirth'  min="1900-01-01" max="<?= date('Y-m-d'); ?>" value="<?= date('Y-m-d'); ?>"><br></td>
        </tr>
        <tr>
            <td><label for='contact'>Contact:</label></td>
            <td><input type='text' name='contact'><br></td>
        </tr>
        <tr>
            <td><label for='department'>Department:</label></td>
            <td><input type='text' name='department'><br></td>
        </tr>
        <tr>
            <td><label for='occupation'>Occupation:</label></td>
            <td><input type='text' name='occupation'><br></td>
        </tr>
        <tr>
            <td></td>
            <td style='text-align: right;'><input type='submit' value='Create Account' name='Submit'></td>
        </tr>  
        </table>
        </form>
    </div>
    <?php include "footer.php" ?>
</body>
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