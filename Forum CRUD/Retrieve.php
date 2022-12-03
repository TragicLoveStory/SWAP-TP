<?php

retrieveAllForum();

function retrieveAllForum()
{
    require "db_config.php";
    require "displayResult.php";

    $connection = mysqli_connect($db_hostname, $db_username, $db_password);
    if (!$connection) die();

    $database = mysqli_select_db($connection, $db_database);
    if (!$database) die();

    $query = "SELECT `id`, `userId`, `title`, `createOn`, `viewCount` FROM `internalhr`.`forum` WHERE 1";
    $result = mysqli_query($connection, $query);
    if (!$result) die();

    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
    displayTable($data);

    // closing
    mysqli_free_result($result);
    mysqli_close($connection);
}