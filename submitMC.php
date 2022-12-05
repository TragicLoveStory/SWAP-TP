<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous"> -->
</head>
<body>
  <?php 
      //session_start();
        //if(!isset($_SESSION["ID"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !=="USER"){
            //echo "Only permitted for Admins.";
           // die();
        //}
      if(isset($_POST['lol']) && $_POST['lol'] === "Submit File"){
          if(!empty($_POST['fullname']) && !empty($_POST['files'])){
              require_once "mcFunctions.php";
              addmc($_POST['fullname'],$_POST['files']);
          } 
          else{
              echo "Error: No fields should be empty<br>";
          }
      }
  ?>
  
  <form action="submitMC.php" method='post'>
  <table style='text-align: left; margin-left: auto; margin-right: auto;'>
  <h1>Submission Of Medical Certificates</h1>
  
  <form action="registerAccount.php" method='post'>
  <table style='text-align: left; margin-left: auto; margin-right: auto;'>
  <tr>
      <td><label for='fullname'>Full Name:</label></td>
      <td><input type='text' name='fullname'><br></td>
  </tr>
  <tr>
      <td><label for='files'>Submit MC File Here:</label></td>
      <td><input type='file' name='files'><br></td>
  </tr>
  
  <tr>
      <td></td>
      <td style='text-align: right;'><input type='submit' value='Submit File' name='lol'></td>
  </tr>
</table>
</form>






<link href='https://fonts.googleapis.com/css?family=Lato:100,200,300,400,500,600,700' rel='stylesheet' type='text/css'>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="https://www.w3schools.com/lib/w3.js"></script> <!-- Include EVERY OTHER HTML Files to this file-->
<script>
    //to bring in other HTML on the fly into this page
    w3.includeHTML();
</script>


</html>