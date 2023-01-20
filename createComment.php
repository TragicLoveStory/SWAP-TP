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
        session_start();
        if (!isset($_SESSION["ID"]) || !isset($_SESSION["role"])){
            echo "Must be logged in.";
            die();
        }
        if(isset($_POST['Submit']) && $_POST['Submit'] === "Create Comment"){
            if(!empty($_POST['comment'])){
                require_once "forumFunctions.php";
                createComment($_POST['comment']);
            }
            else{
                echo "Error: No fields should be empty<br>";
            }
        }
    ?>
    <form action="createComment.php" method='POST'>
    <table style='text-align: left; margin-left: auto; margin-right: auto;'>
    <tr>
        <th></th>
        <th style='text-align: center;'>Create a new comment:</th>
    </tr>
    <tr>
        <td><label for='comment'>Comment:</label></td>
        <td><textarea name="comment" rows="8" cols="50" style="resize:none; white-space: pre-wrap;"></textarea><br></td>
    </tr>
    <tr>
        <td></td>
        <td style='text-align: right;'><input type='submit' value='Create Comment' name='Submit'></td>
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