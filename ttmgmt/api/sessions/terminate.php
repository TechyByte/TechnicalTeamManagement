<?php

include_once("../includes/mysql.php");
include_once("../includes/config.php");

function terminateSession($sessionToken) {
    global $sessionTable;
    $count = mysqli_num_rows(mySQLQuery("SELECT `sessionToken` FROM `" . $sessionTable . "` WHERE `sessionToken`='" . $sessionToken . "' AND `expiry`>" . time() . ";"));
    mySQLQuery("UPDATE `". $sessionTable . "` SET `expiry` = '" . time() . "' WHERE `sessionToken` ='" . $sessionToken . "';");
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    mySQLQuery("UPDATE `". $sessionTable . "` SET `lastIp` = '" . $ip . "' WHERE `sessionToken` ='" . $sessionToken . "';");
    return $count;
}