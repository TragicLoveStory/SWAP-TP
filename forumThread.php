<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<body>
    <?php 
     require "config.php";
     require "userFunctions.php";
     require "forumFunctions.php";
     session_start();
     if (!isset($_SESSION["ID"]) || !isset($_SESSION["role"])){
        echo "Must be logged in.";
         die();
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
        // loading of thread details
        $query=$con->prepare("SELECT forum.userId, users.email,users.first_name,users.last_name, forum.title, forum.content, forum.createOn, forum.lastEdited, forum.viewCount, forum.status FROM forum INNER JOIN users ON forum.userId = users.ID WHERE forum.id=?"); 
        $query->bind_param('i',$_GET['forumID']);
        if($query->execute()){ //executing query (processes and print the results)
            $result = $query->get_result();
            $row = $result->fetch_assoc();
        }
        if (isset($_POST['deletion']) && $_POST['deletion'] === 'Delete') {
            deleteThread($_GET['forumID']);
        }
        if (isset($_POST['editing']) && $_POST['editing'] === 'Edit'){
            $_SESSION['forumID'] = $_GET['forumID'];
            header("Location: http://localhost/SWAP-TP/editThread.php?editing=true");
            die();
        }

        viewCounter($row['viewCount'],$_GET['forumID']);
        $uri = $_SERVER['REQUEST_URI'];
        $fullUri = "http://localhost${uri}";
    ?>
    <?php if($_SESSION['ID'] == $row['userId']) : ?>
        <table align='center' border='1' height='100' WIDTH="1500">
            <tr>
                <th>email</th><th>first name</th><th>last name</th><th>title</th><th>content</th><th>createOn</th><th>lastEdited</th><th>viewCount</th>
            </tr>
            <tr>
                <th><?= $row['email'] ?></th>
                <th><?= $row['first_name'] ?></th>
                <th><?= $row['last_name'] ?></th>
                <th><?= $row['title'] ?></th>
                <th class="Content"><span style="white-space: pre-wrap;"><?= $row['content'] ?></span></th>
                <th><?= $row['createOn'] ?></th><th><?= $row['lastEdited'] ?></th>
                <th><?= $row['viewCount']+1 ?></th>
                <th>
                    <form action="<?= $fullUri ?>" method='POST'>
                        <input type='submit' name='editing' value='Edit'>
                    </form>
                </th>
                <th>
                    <form action="<?= $fullUri ?>" method='POST'>
                        <input type='submit' name='deletion' value='Delete'>
                    </form>
                </th>
            </tr>
        </table>
    <?php else : ?>


        <table align='center' border='1' height='100' WIDTH="1500">
            <tr>
                <th>email</th><th>first name</th><th>last name</th><th>title</th><th>content</th><th>createOn</th><th>lastEdited</th><th>viewCount</th>
            </tr>
            <tr>
                <th><?= $row['email'] ?></th><th><?= $row['first_name'] ?></th><th><?= $row['last_name'] ?></th><th><?= $row['title'] ?></th><th class="Content"><span style="white-space: pre-wrap;"><?= $row['content'] ?></span></th><th><?= $row['createOn'] ?></th><th><?= $row['lastEdited'] ?></th><th><?= $row['viewCount']+1 ?></th>
            </tr>
        </table>
    <?php endif; ?>
    <?php 
     if(isset($_POST['commentSubmit']) && $_POST['commentSubmit']=== "Create Comment"){
        $_SESSION['forumId'] = $_GET['forumID'];
        header("Location: http://localhost/SWAP-TP/createComment.php");
        die();
    }

    if (isset($_POST['editingComment']) && $_POST['editingComment'] === 'Edit') {
        $_SESSION['commentId'] = $_POST['editCommentID'];
        header("Location: http://localhost/SWAP-TP/editComment.php?editingComment=true");
        die();
    }

    if (isset($_POST['deletionComment']) && $_POST['deletionComment'] === 'Delete') {
        if(!empty($_POST['commentID'])){
            deleteComment($_POST['commentID'],$_GET['forumID']);
        }
        else{
            echo "Error.";
            die();
        }
        
    }

    if (isset($_POST['likeComment']) && $_POST['likeComment'] == "Like"){
        if(!empty($_POST['likeCommentID'])){
            likeComment($_POST['likeCommentID'],$_GET['forumID']);
        }
    }
    if (isset($_POST['likeComment']) && $_POST['likeComment'] == "Liked!"){
        if(!empty($_POST['likeCommentID'])){
            likeComment($_POST['likeCommentID'],$_GET['forumID']);
        }
    }

    if (isset($_POST['dislikeComment']) && $_POST['dislikeComment'] == "Dislike"){
        if(!empty($_POST['dislikeCommentID'])){
            dislikeComment($_POST['dislikeCommentID'],$_GET['forumID']);
        }
    }
    if (isset($_POST['dislikeComment']) && $_POST['dislikeComment'] == "Disliked!"){
        if(!empty($_POST['dislikeCommentID'])){
            dislikeComment($_POST['dislikeCommentID'],$_GET['forumID']);
        }
    }
    $uri = $_SERVER['REQUEST_URI'];
    $fullUri = "http://localhost${uri}";
    if($row['status'] === 1){
        echo "<br><form  action=".$fullUri." method='POST' style='text-align: center;'><input type='submit' value='Create Comment' name='commentSubmit'></form>";
    }
    else{
        echo "<br><p style='text-align: center;'><b>This thread has since been archived. Thus, no further comments are able to be made.</b></p>";
    }
    echo "<p style='text-align: left;'>  <p style='padding-left: 20px'><b>Comments:</b>";

    $query2=$con->prepare("SELECT comments.ID, comments.userId, comments.forumId, comments.comment, comments.createOn,users.department, users.role FROM comments INNER JOIN users ON comments.userId = users.ID WHERE comments.forumId = ?"); 

    $query2->bind_param('i',$_GET['forumID']);
    if($query2->execute()){ //executing query (processes and print the results)
        $result2 = $query2->get_result();
        echo "<div><table align='center' border='1' height='100' WIDTH='1500'><tr>";
        echo"<th>ID</th><th>userId</th><th>forumId</th><th>comment</th><th>createOn</th><th>department</th><th>role</th><th>Rating</th></tr>";
        while ($row2 = $result2->fetch_assoc()) {
            $id = $row2['ID'];
            $userId = $row2['userId'];
            $forumId = $row2['forumId'];
            $comment = $row2['comment'];
            $createOn = $row2['createOn'];
            $department = $row2['department'];
            $role = $row2['role'];

            $query3=$con->prepare("SELECT `id`,`commentId`,`userId`,`status` FROM `commentlikes` WHERE `commentId` = ?");
            $query3->bind_param('i', $id); //bind the parameters
            $query3->execute();
            $result3 = $query3-> get_result();
            $likeCounter = 0;
            $likeStatus = "Like";
            $dislikeStatus ="Dislike";
            while($row3 = $result3 -> fetch_assoc()){
                if($row3['status'] === 1){
                    $likeCounter += 1;
                }
                else{
                    $likeCounter -=1;
                }
                if($_SESSION['ID'] === $row3['userId']){
                    if($row3['status'] === 1){
                        $likeStatus = "Liked!";
                        $dislikeStatus = "Dislike";
                    }
                    elseif($row3['status'] === 0){
                        $likeStatus = "Like";
                        $dislikeStatus = "Disliked!";
                    }
                    
                }
            }

            if($_SESSION['ID'] === $row2['userId']){
                echo
                    "<tr><th>$id</th><th>$userId</th><th>$forumId</th><th>$comment</th><th>$createOn</th><th>$department</th><th>$role</th><th>$likeCounter</th>
                <th>
                <form action=".$fullUri." method='POST'>
                    <input type='hidden' name='likeCommentID' value=".$id.">
                    <input type='submit' name='likeComment' value=".$likeStatus.">
                </form>
                </th>
                <th>
                <form action=".$fullUri." method='POST'>
                    <input type='hidden' name='dislikeCommentID' value=".$id.">
                    <input type='submit' name='dislikeComment' value=".$dislikeStatus.">
                </form>
                </th>
                <th>
                <form action=".$fullUri." method='POST'>
                    <input type='hidden' name='editCommentID' value=".$id.">
	                <input type='submit' name='editingComment' value='Edit'>
                </form>
                </th>
                <th>
                <form action=".$fullUri." method='POST'>
                    <input type='hidden' name='commentID' value=".$id.">
	                <input type='submit' name='deletionComment' value='Delete'>
                </form>
                </th></tr>";
            }

            else {
                echo "<tr><th>$id</th><th>$userId</th><th>$forumId</th><th>$comment</th><th>$createOn</th><th>$department</th><th>$role</th><th>$likeCounter</th>
                <th>
                <form action=" . $fullUri . " method='POST'>
                    <input type='hidden' name='likeCommentID' value=" . $id . ">
                    <input type='submit' name='likeComment' value=" . $likeStatus . ">
                </form>
                </th>
                <th>
                <form action=" . $fullUri . " method='POST'>
                    <input type='hidden' name='dislikeCommentID' value=" . $id . ">
                    <input type='submit' name='dislikeComment' value=" . $dislikeStatus . ">
                </form>
                </th></tr>";
            }
        }
    }

    ?>

    <style>
        .Content{
            max-width: 300px;
        }
        th {
            text-align: center;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        form {
            float: right;
            padding-right: 15px;
        }

    </style>


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
