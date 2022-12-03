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
        if(isset($_POST['Submit']) && $_POST['Submit'] === "Create Account"){
            if(!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['firstname']) && !empty($_POST['lastname']) && !empty($_POST['dateofbirth']) && !empty($_POST['contact']) && !empty($_POST['department'])){
                require_once "userFunctions.php";
                adduser($_POST['email'],$_POST['password'],$_POST['firstname'],$_POST['lastname'],
                $_POST['dateofbirth'],$_POST['contact'],$_POST['department']);
            } 
            else{
                echo "Error: No fields should be empty<br>";
            }
        }
    ?>

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
        <td><input type='text' name='password'><br></td>
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
        <td><label for='contact'>Contact (+65):</label></td>
        <td><input type='text' name='contact'><br></td>
    </tr>
    <tr>
        <td><label for='department'>Department:</label></td>
        <td><input type='text' name='department'><br></td>
    </tr>
    <!-- <tr>
        <td><label for='role'>Role:</label></td>
        <td><input type='text' name='role'><br></td>
    </tr> -->
    <tr>
        <td></td>
        <td style='text-align: right;'><input type='submit' value='Create Account' name='Submit'></td>
    </tr>  
    </table>
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