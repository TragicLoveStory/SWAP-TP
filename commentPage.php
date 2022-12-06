<html>
<?php
require "config.php";  //connect to database
$db_host="127.0.0.1";
$db_username="root";
$db_password="";
$db_database="internalhr";

$con = mysqli_connect($db_host,$db_username,$db_password,$db_database);
if (!$con){
    echo $con->errno ."<br>";
    die('Could not connect: '. $con->error);
}
else {
    echo "Connection to DB server at $db_host successful<br>";
}
?>
<?php //post comment
if(isset($_POST['Submit']) && $_POST['Submit'] === "Submit"){
    
    if (!empty($_POST['userId']) &&
        !empty($_POST['comment'])) {
        echo "OK: fields are not empty<br>";
    }
    else {
        echo "Error: No fields should be empty<br>";
    }
    
    $userId=$_POST['userId'];
    $comment=$_POST['comment'];
    $query = $con->prepare("INSERT INTO `comments` (`userId`, `comment`) VALUES(?, ?)");
    $query->bind_param('is', $userId, $comment);
    
    if ($query->execute()){  //execute query
        echo "Query executed.";
    }else{
        echo "Error executing query.";
    }
}
?>
<?php //delete comment
if(isset($_GET['Submit']) && $_GET['Submit'] === "Delete"){
    $ID=$_GET['ID'];
    
    $query= $con->prepare("Delete from comments where ID = ?");
    $query->bind_param('i', $ID); //bind the parameters
    
    if ($query->execute()){  //execute query
        echo "Query executed.";
    }else{
        echo "Error executing query.";
    }
}
?>
<body> 
	<b>Comment CRUD</b><br>
    	<form action="commentPage.php" method="post">
    		<table border=0>
        	<tr><td>userId :</td><td><input type="text" name="userId"></td></tr>
        	<tr><td>comment : </td><td><input type="text" name="comment"></td></tr>
        	<tr><td></td><td>
        	<input type="submit" name="Submit" value="Submit"></td></tr> 
    		</table>
    	</form>
<?php 

//Select statement
//$query="SELECT ID, userId, department, role, comment, createOn from comments";
$query="SELECT comments.ID, users.first_name, users.department, users.role, comments.comment, comments.createOn from ((comments INNER JOIN 
users ON comments.userId = users.ID))";
$pQuery = $con->prepare($query);
$result=$pQuery->execute();
$result=$pQuery->get_result();
if(!$result) {
    die("SELECT query failed<br> ".$con->error);
}
else {
    echo "SELECT query successful<br>";
}
$nrows=$result->num_rows;
echo "#rows=$nrows<br>";

if ($nrows>0) {
    echo "<table border=1>";
        echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Name</th>";
            echo "<th>Department</th>";
            echo "<th>Role</th>";
            echo "<th>Comment</th>";
            echo "<th>createOn</th>";
        echo "</tr>";
        while ($row=$result->fetch_assoc()) {
        echo "<tr>";
            echo "<td>";
            echo $row['ID'];
            echo "</td>";
            echo "<td>";
            echo $row['first_name'];
            echo "</td>";
            echo "<td>";
            echo $row['department'];
            echo "</td>";
            echo "<td>";
            echo $row['role'];
            echo "</td>";
            echo "<td>";
            echo $row['comment'];
            echo "</td>";
            echo "<td>";
            echo $row['createOn'];
            echo "</td>";
            echo "<td>";
            echo "<a href='updateComment.php?Submit=GetUpdate&ID=".$row['ID']."'>Edit</a>";
            echo "</td>";
            echo "<td>";
            echo "<a href='commentPage.php?Submit=Delete&ID=".$row['ID']."'>Delete</a>";
            echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}
else {
    echo "0 records<br>";
}

echo "Disconnecting now<br>";
$con->close();
?>

</body>
</html>


