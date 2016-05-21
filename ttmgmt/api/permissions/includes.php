<?php

include_once("../includes/config.php");
include_once("../sessions/checkSession.php");

function canDoFromPerm($permLevel, $activity) {
    global $permTable;
    return mysqli_fetch_row(mySQLQuery("SELECT `level" . $permLevel . "` FROM `" . $permTable . "` WHERE `permName`='" . $activity . "';"))[0]==1;
}

function canDoFromSession($sessionToken, $activity) {
    global $usersTable;
    $userId = checkSession($sessionToken);
    $permLevel = mysqli_fetch_row(mySQLQuery("SELECT `permLevel` FROM `" . $usersTable . "` WHERE `userId`=" . $userId . ";"))[0];
    return canDoFromPerm($permLevel, $activity);
}