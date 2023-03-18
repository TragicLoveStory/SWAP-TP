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
    require "leaveFunctions.php";
    require "config.php";
    session_start();
    if (!isset($_SESSION["ID"]) || !isset($_SESSION["role"])){
       echo "Must be logged in.";
        die();
    }  
    elseif(!isset($_GET['mcEditing']) || $_GET['mcEditing'] != "true"){
        echo "Error.";
        die();
    }
    include "navbar.php";
    $errorMessage = "";
    $uri = $_SERVER['REQUEST_URI'];
    $fullUri = "https://localhost${uri}";
    if(isset($_POST['Submit']) && $_POST['Submit'] === "Edit MC"){
        //client side size validation FOR User Experience
        if ($_FILES["editMC"]["size"] > 1000000) {
            echo "<p class='AlreadyLoggedInText'>File size is too large.</p>";
            die();
        }
        if(!empty($_POST['mcDays']) && !empty($_POST['startDate']) && !empty($_POST['endDate']) && !empty($_FILES['editMC'])){
            $start = new DateTime(date('Y-m-d',strtotime($_POST['startDate'])));
            $end = new DateTime(date('Y-m-d',strtotime($_POST['endDate'])));
            $days  = $end->diff($start)->format('%a');
            $days+=1;
            if($_POST['mcDays'] == $days){
                editMC($_POST['mcDays'],$_POST['startDate'],$_POST['endDate']);
            }
            else{
                echo "<p class='AlreadyLoggedInText'>Number of days does not match start and end date.</p>";
                die();
            }
        }
        
    }
    ?>
    <div class='container mcDivision'>
        <form action="<?= $fullUri ?>" method="post" enctype="multipart/form-data">
            <label for='mcDays'>Number of days for work leave:</label>
            <input type="number" id="mcDays" name="mcDays" min="1" max="60"><br>
            <label for='startDate'>Start Date</label>
            <input type='date' name='startDate'  min="1900-01-01" value="<?= date('Y-m-d'); ?>"><br>
            <label for='endDate'>End Date</label>
            <input type='date' name='endDate'  min="1900-01-01" value="<?= date('Y-m-d'); ?>"><br>
            <label for='editMC'>Edit MC:</label>
            <input type="file" name="editMC"><br>
            <input type="submit" value="Edit MC" name="Submit">
        </form>
        <p style='text-align: center; margin-bottom: 14.5rem;'><?= $errorMessage ?></p>
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