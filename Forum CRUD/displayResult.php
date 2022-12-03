<?php

function displayTable($data)
{
    echo "<pre>";
    require "./array2texttable.php";
    $renderer = new ArrayToTextTable($data);
    $renderer->showHeaders(true);
    $renderer->render();
    echo "</pre>";
}

function display($message) {
    echo "<pre>";
    echo "$message<br />";
    echo "</pre>";
}