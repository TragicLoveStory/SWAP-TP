<?php


UpdateForumTitleById(117, "Updated");

function UpdateForumTitleById($id, $title)
{
    require "db_config.php";
    require "displayResult.php";

    $connection = mysqli_connect($db_hostname, $db_username, $db_password);
    if (!$connection) die();

    $database = mysqli_select_db($connection, $db_database);
    if (!$database) die();

    $query = "UPDATE `internalhr`.`forum` SET `title`='$title' WHERE `id` = $id";
    $result = mysqli_query($connection, $query);

    if (!$result) display('Error while inserting the data');

    // closing
    mysqli_close($connection);
}
