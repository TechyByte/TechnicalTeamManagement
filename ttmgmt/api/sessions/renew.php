<?php

include_once("../includes/config.php");

function renewSession($sessionToken) {
    global $sessionTable;
    global $tokenRenewalLength;
    mySQLQuery("UPDATE `" . $sessionTable . "` SET `expiry` = '" . (time() + ($tokenRenewalLength * 60)) . "' WHERE `sessionToken` = '" . $sessionToken . "';");
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    mySQLQuery("UPDATE `". $sessionTable . "` SET `lastIp` = '" . $ip . "' WHERE `sessionToken` ='" . $sessionToken . "';");
}