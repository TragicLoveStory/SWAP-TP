<?php
#
$now = new DateTime("now", new DateTimeZone('Asia/Singapore'));
$now = $now->format('Y-m-d H:i:s');


CreateForum(2, "'User 31 title'", "'$now'", 32);

function CreateForum($userId, $title, $createOn, $viewCount)
{
    require "db_config.php";
    require "displayResult.php";

    $connection = mysqli_connect($db_hostname, $db_username, $db_password);
    if (!$connection) die();

    $database = mysqli_select_db($connection, $db_database);
    if (!$database) die();

    $query = "INSERT INTO `internalhr`.`forum`(`userId`, `title`, `createOn`, `viewCount`) VALUES ($userId, $title, $createOn, $viewCount)";
    $result = mysqli_query($connection, $query);

    if (!$result) display('Error while inserting the data');

    // closing
    mysqli_close($connection);
}
