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
<body class="backgroundColor">
    <?php 
    session_start();
    require "config.php";
    require "userfunctions.php";
    if(!isset($_SESSION["ID"]) || !isset($_SESSION["role"])){
        header("Location: http://localhost/SWAP-TP/loginForm.php");
        die();
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
    $query=$con->prepare("SELECT * FROM `users` WHERE `ID` =?");
    $query->bind_param('i', $_SESSION['ID']); //bind the parameters
    if($query->execute()){
    $result = $query-> get_result();
    $row = $result -> fetch_assoc();
    }
    include "navbar.php";
    //echo "<p class='welcomeUserText'>Welcome, ".$row['first_name']." ".$row['last_name']."!</p>";
    include "carousel.php";
    ?>
    <div class="container indexDivision">
        <div class="forumIndexDiv">
            <p class="indexDivisionText">TPAMC Forum<br>Converse with other employees of TPAMC here! <br>Create or comment on threads, and get to know people outside of your department! don't be shy to ask for help or second opinions whenever needed, and feel free to share whatever you find intriguing!</p>
            
            <div class="forumIndexDiv2">
                <a href='Forum.php'><img src="Images/forumStock.jpg" class="forumIndexPhoto"></a>
            </div>   
        </div>
        <div class="safetyIndexDiv">
            <p class="indexDivisionText">TPAMC Workplace Safety Materials<br>View our workplace safety guidelines at the tip of your fingers whenever needed.<br>The materials will constantly be updated and revised whenever necessary to ensure safety when handling machines and/or dangerous equipment.<br>Safety is of paramount importance to TPAMC and will always be handled with utmost caution and concern.</p>
            <div class="forumIndexDiv2">
                <a href='Safety.php'><img src="Images/workplaceSafety.jpg" class="forumIndexPhoto"></a>
            </div>   
        </div>
        <div class="attendanceIndexDiv">
            <p class="indexDivisionText">TPAMC Attendance Page<br>View your Attendance, Leave & Medical Certificate requests here!<br>You will be able to see all previous submissions if they were approved or denied, and edit existing pending ones whenever needed.</p>
            <div class="forumIndexDiv2">
                <a href='attendanceAndLeave.php'><img src="Images/submitFilesStock.jpg" class="forumIndexPhoto"></a>    
            </div>   
        </div>
    </div>
    <?php 
    include "footer.php";
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