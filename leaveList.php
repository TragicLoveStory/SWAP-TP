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
    require "leaveFunctions.php";
    session_start();
    if (isset($_SESSION["ID"]) && isset($_SESSION["role"]) && $_SESSION["role"] === "LEAVE-ADMIN") {

      if (isset($_POST['deleteLeave']) && $_POST['deleteLeave'] === 'Delete') {
        if (!empty($_POST['deleteLeaveID'])) {
          deleteLeave($_POST['deleteLeaveID']);
        }
      }

      //echo "Permitted for Leave Admins";
      //connection to internalhr database
      try {
        $con = mysqli_connect($db_hostname, $db_username, $db_password, $db_database);
      } catch (Exception $e) {
        //printerror($e->getMessage(),$con);
      }
      if (!$con) {
        //printerror("Connecting to $db_hostname", $con);
        die();
      }
      //else printok("Connecting to $db_hostname");
      include 'navbar.php';
      // loading of user details
      $query = $con->prepare("select `id`,`userId`,`Days`,`startDate`,`endDate`,`Reason`,`timeOfSubmission`,`status`from workleave");
      $query->execute();
      $result = $query->get_result();
      echo "<div class ='container listingTable'>
        <p style='margin-bottom: 5rem;'>Table that contains all leave requests submitted, delete whenever necessary.<br>Please contact <i>tpamcIT@tp.edu.sg</i> or any IT staff for any inquiries.</p>
        <table class='listingTable2'>
            <tr><th> Leave ID</th><th>User ID</th><th>Number of Days</th><th>Start Date</th><th>End Date</th><th>Reason for Leave</th><th>Time of Submission</th><th>Status</th><th>Delete</th></tr>";
      while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $userId = $row['userId'];
        $Days = $row['Days'];
        $startDate = $row['startDate'];
        $endDate = $row['endDate'];
        $Reason = $row['Reason'];
        $timeOfSubmission = $row['timeOfSubmission'];
        $status = $row['status'];
        echo "<tr><td>$id</td><td>$userId</td><td>$Days</td><td>$startDate</td><td>$endDate</td><td>$Reason</td><td>$timeOfSubmission</td><td>$status</td>
              <td>
                  <form action='leaveList.php' method='POST'>
                    <input type='hidden' name='deleteLeaveID' value=".$id.">
                    <input type='submit' name='delete' value='Delete'>
                  </form>
              </td></tr>";
      }
      echo "</table></div>";

      include 'footer.php';
    }
    else {
      echo "Only permitted for Leave Admins.";
      die();
    }
    ?>
</body>
<style>
        th{
            max-width: 200px;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }
        .image{
            height: 70%;
            width: 70%;

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