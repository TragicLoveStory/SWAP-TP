<?php
#
$now = new DateTime("now", new DateTimeZone('Asia/Singapore'));
$now = $now->format('Y-m-d H:i:s');


CreateForum(111, 31, "'User 31 title'", "'$now'", 0);

function CreateForum($id, $userId, $title, $createOn, $viewCount)
{
    require "db_config.php";
    require "displayResult.php";

    $connection = mysqli_connect($db_hostname, $db_username, $db_password);
    if (!$connection) die();

    $database = mysqli_select_db($connection, $db_database);
    if (!$database) die();

    $query = "INSERT INTO `internalhr`.`forum`(`id`, `userId`, `title`, `createOn`, `viewCount`) VALUES ($id, $userId, $title, $createOn, $viewCount)";
    $result = mysqli_query($connection, $query);

    if (!$result) display('Error while inserting the data');

    // closing
    mysqli_close($connection);
}
