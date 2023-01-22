<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Document</title>
</head>
<body>
  <?php 
  if(!isset($_SESSION["ID"]) || !isset($_SESSION["role"])){
      echo "Not logged in.";
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
  $navbarQuery=$con->prepare("SELECT  `first_name`, `profilePic` FROM `users` WHERE `ID` =?");
  $navbarQuery->bind_param('i', $_SESSION['ID']); //bind the parameters
  if($navbarQuery->execute()){
    $navbarResult = $navbarQuery-> get_result();
    $navbarRow = $navbarResult -> fetch_assoc();
  }
  ?>
  <nav class="navbar navbar-expand-lg navbar-light bg-light theNavbar">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="index.php" style="margin-left: 15rem; color: #000000;">TPAMC</a>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="Forum.php" style="margin-right: 3rem; margin-top: 0.75rem; color: #000000;">Forums</a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="Safety.php" style="margin-right: 3rem; margin-top: 0.75rem; color: #000000;">Safety Materials</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="attendanceAndLeave.php" style="margin-right: 5rem; margin-top: 0.75rem; color: #000000;">Attendance & Leave</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="profile.php" style="margin-right: 15rem;">
              <img src="<?= $navbarRow['profilePic'] ?>" class="profilePicture"></a>
            </li>
        </ul>
      </div>
    </nav>
</body>
</html>