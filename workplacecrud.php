<html>
<?php
$mysql_host="localhost"; // MySQL's ip address
$mysql_user="root";
$mysql_password="";
$mysql_db="internalhr";

$con = new mysqli($mysql_host,$mysql_user,$mysql_password,$mysql_db);
if (!$con){
    echo $con->errno ."<br>";
    die('Could not connect: '. $con->error);
}
else {
    echo "Connection to DB server at $mysql_host successful<br>";
}
?>

<?php 
if(isset($_POST['Submit']) && $_POST['Submit'] === "Submit"){
    
    if (!empty($_POST['videoTitle']) &&
        !empty($_POST['videoLink'])) {
            echo "OK: fields are not empty<br>";
        }
        else {
            echo "Error: No fields should be empty<br>";
        }
        $now = new DateTime("now", new DateTimeZone('Asia/Singapore'));
        $now = $now->format('Y-m-d H:i:s');
        $videoTitle=$_POST['videoTitle'];
        $videoLink=$_POST['videoLink'];
        $query= $con->prepare("INSERT INTO `workplacesafety` (`videoTitle`,`videoLink`,`createOn`) VALUES
    (?,?,?)");
        $query->bind_param('sss', $videoTitle,$videoLink,$now); //bind the parameters
        
        
        if ($query->execute()){  //execute query
            echo "Query executed.";
        }else{
            echo "Error executing query.";
        }
        
}



if(isset($_GET['Submit']) && $_GET['Submit'] === "Delete"){
    $id=$_GET['id'];
    
    $query= $con->prepare("Delete from workplacesafety where id = ?");
    $query->bind_param('i', $id); //bind the parameters
    
    if ($query->execute()){  //execute query
        echo "Query executed.";
    }else{
        echo "Error executing query.";
    }
}
?>
<body>
    <b>CRUD</b><br>
    <form action="workplacecrud.php" method="post">
    	<table>
        <tr><td>Video Title:</td><td><input type="text" name="videoTitle"></td></tr>
        <tr><td>Video Link : </td><td><input type="text" name="videoLink"></td></tr>

        <tr><td></td><td>
        <input type="submit" name="Submit" value="Submit"></td></tr>
    	</table>
    </form>
</html>
<?php 

// select statement
$query="SELECT id, videoTitle,videoLink, createOn FROM workplacesafety";
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
    echo "<table>";
        echo "<tr>";
            echo "<th>id</th>";
            echo "<th>videoTitle</th>";
            echo "<th>videoLink</th>";
            echo "<th>createOn</th>";
        echo "</tr>";
        while ($row=$result->fetch_assoc()) {
        echo "<tr>";
            echo "<td>";
            echo $row['id'];
            echo "</td>";
            echo "<td>";
            echo $row['videoTitle'];
            echo "</td>";
            echo "<td>";
            echo $row['videoLink'];
            echo "</td>";
            echo "<td>";
            echo $row['createOn'];
            echo "</td>";
            echo "<td>";
            echo "<a href='editworkplace.php?Submit=GetUpdate&id=".$row['id']."'>Edit</a>";
            echo "</td>";
            echo "<td>";
            echo "<a href='workplacecrud.php?Submit=Delete&id=".$row['id']."'>Delete</a>";
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
