<?php

include_once("config.php");
include_once("tokens.php");

$sql = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);

if (!$sql) {
    die("Connection failed: " . mysqli_connect_error());
}

function mySQLQuery($query) {
    global $sql;
    return mysqli_query($sql, $query);
}

