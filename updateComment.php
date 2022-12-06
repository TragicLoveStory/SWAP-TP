<html>
<body>
<?php
require "config.php";  //connect to database
$con = mysqli_connect("127.0.0.1","root","","internalhr");
if (!$con){
    die('Could not connect: ' . mysqli_connect_errno()); //return error is connect fail
}
?>
<?php 
if(isset($_POST['Submit'])){
    
    if (!empty($_POST['comment']) &&
        !empty($_POST['ID'])
        ) {
            echo "OK: fields are not empty<br>";
        }
        else {
            echo "Error: No fields should be empty<br>";
        }
        
        $comment=$_POST['comment'];
        $ID=$_POST['ID'];
        
        $query= $con->prepare("UPDATE comments SET comment=? WHERE ID=?");
        $query->bind_param('si', $comment, $ID);
        
        if($query->execute()) {
            echo "Query executed.";
            header("location: commentPage.php");
        }else {
            echo "Error executing query.";
        }
}

if(isset($_GET['Submit']) && $_GET['Submit']==="GetUpdate"){
    $ID=$_GET['ID'];
    $query="SELECT ID, comment FROM comments WHERE ID=?";
    $pQuery = $con->prepare($query);
    $pQuery->bind_param('i', $ID); //bind the parameters
    
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

    if ($row=$result->fetch_assoc()) {
?>
	<b>Edit comment</b><br>
	<form action="updateComment.php" method="post">
		<table>
        <tr><td>Comment : </td><td><input type="text" name="comment" value="<?php echo $row['comment']?>"></td></tr>
		<tr><td></td><td>
		<input type="hidden" name="ID" value="<?php echo $row['ID']?>">
		<input type="submit" name="Submit" value="Update"></td></tr>
		</table>
	</form>
    
<?php 
    }
}
echo "Disconnecting now<br>";
$con->close();
?>

</body>
</html>


