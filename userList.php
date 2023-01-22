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
    require "config.php";
    require "userFunctions.php";
    session_start();
    if (isset($_SESSION["ID"]) && isset($_SESSION["role"]) && $_SESSION["role"]==="USER-ADMIN"){

        if (isset($_POST['editUser']) && $_POST['editUser'] === 'Edit') {
            if(!empty($_POST['editUserID'])){
                $_SESSION['editUserId'] = $_POST['editUserID'];
                header("Location: http://localhost/SWAP-TP/editAccount.php?editing=true");
                die();
            }
        }
        if (isset($_POST['deleteUser']) && $_POST['deleteUser'] === 'Delete') {
            if(!empty($_POST['deleteUserID'])){
                deleteItem($_POST['deleteUserID']);
            }
        }
        //connection to internalhr database
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
        include "navbar.php";
        // loading of user details
        $query=$con->prepare("SELECT * from users");
        $query->execute();
        $result = $query-> get_result();
        echo "<div class='container listingTable'>
        <p>Users table containing employee accounts from all departments. Edit user details whenever necessary. Please contact <i>tpamcIT@tp.edu.sg</i> or any IT staff for any inquiries.</p>
        <table class='listingTable2'>
            <tr><th>User ID</th><th>Email</th><th>Password</th><th>First name</th><th>Last name</th><th>Date Of Birth</th><th>Contact</th><th>Department</th><th>Occupation</th><th>Role</th><th>Status</th><th>About me</th><th>Profile Pic</th><th>OTP</th><th>Edit</th><th>Delete</th></tr>";
        while($row = $result -> fetch_assoc()){
            $id = $row['ID'];
            $email = $row['email'];
            $password = $row['password'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $date_of_birth = $row['date_of_birth'];
            $contact = $row['contact'];
            $department = $row['department'];
            $occupation = $row['occupation'];
            $role = $row['role'];
            $status = $row['status'];
            $aboutMe = $row['aboutMe'];
            $profilePic = $row['profilePic'];
            $otp = $row['otp'];
            echo "<tr><td>$id</td><td>$email</td><td>$password</td><td>$first_name</td><td>$last_name</td><td>$date_of_birth</td><td>$contact</td><td>$department</td><td>$occupation</td><td>$role</td><td>$status</td><td>$aboutMe</td><td>$profilePic</td><td>$otp</td>
            <td>
                <form action='userList.php' method='POST'>
                    <input type='hidden' name='editUserID' value=".$id.">
                    <input type='submit' name='editUser' value='Edit'>
                </form>
            </td>
            <td>
                <form action='userList.php' method='POST'>
                    <input type='hidden' name='deleteUserID' value=".$id.">
                    <input type='submit' name='deleteUser' value='Delete'>
                </form>
            </td></tr>";
        }
        echo "</table></div>";
        
        include "footer.php";
    }
    else{
        echo "Only permitted for User Admins.";
        die();
    }
    ?>
    
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