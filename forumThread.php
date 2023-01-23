<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    if (isset($_POST['deletion']) && $_POST['deletion'] === 'Delete') {
        deleteThread($_GET['forumID']);
    }
    if (isset($_POST['editing']) && $_POST['editing'] === 'Edit'){
        $_SESSION['forumID'] = $_GET['forumID'];
        header("Location: https://localhost/SWAP-TP/editThread.php?editing=true");
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
        viewCounter($row['viewCount'],$_GET['forumID']);
        $uri = $_SERVER['REQUEST_URI'];
        $fullUri = "https://localhost${uri}";
    ?>
    <?php if($_SESSION['ID'] == $row['userId']) : ?>
        <div class='container forumTable'>
            <table class='forumTable2'>
                <tr><th>Email</th><th>Title</th><th>Content</th><th>Created on</th><th>Last edited</th><th>View count</th></tr>
                <tr>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['title'] ?></td>
                    <td class="Content"><span style="white-space: pre-wrap;"><?= $row['content'] ?></td>
                    <td><?= $row['createOn'] ?></td>
                    <td><?= $row['lastEdited'] ?></td>
                    <td><?= $row['viewCount']+1 ?></td>
                    <td>
                        <form action="<?= $fullUri ?>" method='POST'>
                            <input type='submit' name='editing' value='Edit'>
                        </form>
                    </td>
                    <td>
                        <form action="<?= $fullUri ?>" method='POST'>
                            <input type='submit' name='deletion' value='Delete'>
                        </form>
                    </td>
                </tr>
            </table>
        </div>
    <?php else : ?>
        <div class='container forumTable'>
            <table class='forumTable2'>
                <tr><th>Email</th><th>Title</th><th>Content</th><th>Created on</th><th>Last edited</th><th>View count</th></tr>
                <tr>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['title'] ?></td>
                    <td class="Content"><span style="white-space: pre-wrap;"><?= $row['content'] ?></td>
                    <td><?= $row['createOn'] ?></td>
                    <td><?= $row['lastEdited'] ?></td>
                    <td><?= $row['viewCount']+1 ?></td>
                </tr>
            </table>
        </div>  
    <?php endif; ?>
    <?php 
     if(isset($_POST['commentSubmit']) && $_POST['commentSubmit']=== "Create Comment"){
        $_SESSION['forumId'] = $_GET['forumID'];
        header("Location: https://localhost/SWAP-TP/createComment.php");
        die();
    }

    if (isset($_POST['editingComment']) && $_POST['editingComment'] === 'Edit') {
        $_SESSION['commentId'] = $_POST['editCommentID'];
        header("Location: https://localhost/SWAP-TP/editComment.php?editingComment=true");
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
    $fullUri = "https://localhost${uri}";
    if($row['status'] === 1){
        //checks if thread is archived
        echo "<form action=".$fullUri." method='POST' style='text-align: center;'><input type='submit' value='Create Comment' name='commentSubmit'></form>";
    }
    else{
        echo "<p style='text-align: center;'><b>This thread has since been archived. Thus, no further comments are able to be made.</b></p>";
    }
    echo "<p style='text-align: center;'>Comments:</p>";

    $query2=$con->prepare("SELECT comments.ID, comments.userId, comments.forumId, comments.comment, comments.createOn,users.department, users.role FROM comments INNER JOIN users ON comments.userId = users.ID WHERE comments.forumId = ?"); 
    $query2->bind_param('i',$_GET['forumID']);
    if($query2->execute()){ //executing query (processes and print the results)
        $result2 = $query2->get_result();
        echo "<div class='container commentTable'>
        <table class='forumTable2'>
            <tr><th>Comment ID</th><th>User ID</th><th>Forum ID</th><th>Comment</th><th>Created On</th><th>Department</th><th>Role</th><th>Rating</th></tr>";
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
                    "<tr><td>$id</td><td>$userId</td><td>$forumId</td><td>$comment</td><td>$createOn</td><td>$department</td><td>$role</td><td>$likeCounter</td>
                <td>
                <form action=".$fullUri." method='POST'>
                    <input type='hidden' name='likeCommentID' value=".$id.">
                    <input type='submit' name='likeComment' value=".$likeStatus.">
                </form>
                </td>
                <td>
                <form action=".$fullUri." method='POST'>
                    <input type='hidden' name='dislikeCommentID' value=".$id.">
                    <input type='submit' name='dislikeComment' value=".$dislikeStatus.">
                </form>
                </td>
                <td>
                <form action=".$fullUri." method='POST'>
                    <input type='hidden' name='editCommentID' value=".$id.">
	                <input type='submit' name='editingComment' value='Edit'>
                </form>
                </td>
                <td>
                <form action=".$fullUri." method='POST'>
                    <input type='hidden' name='commentID' value=".$id.">
	                <input type='submit' name='deletionComment' value='Delete'>
                </form>
                </td></tr>"; 
            }
            else {
                echo "<tr><td>$id</td><td>$userId</td><td>$forumId</td><td>$comment</td><td>$createOn</td><td>$department</td><td>$role</td><td>$likeCounter</td>
                <td>
                <form action=" . $fullUri . " method='POST'>
                    <input type='hidden' name='likeCommentID' value=" . $id . ">
                    <input type='submit' name='likeComment' value=" . $likeStatus . ">
                </form>
                </td>
                <td>
                <form action=" . $fullUri . " method='POST'>
                    <input type='hidden' name='dislikeCommentID' value=" . $id . ">
                    <input type='submit' name='dislikeComment' value=" . $dislikeStatus . ">
                </form>
                <td>
                </td>
                <td>
                </td>
                </td></tr>";
            }
        }
        echo "</table></div>";
        include "footer.php";
    }

    ?>

    <style>
        .Content{
            max-width: 300px;
        }
    </style>


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
