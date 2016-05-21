<?php

include_once("../includes/mysql.php");
include_once("../includes/config.php");
include_once("../permissions/includes.php");

/**
 * @param $sessionToken
 * @param $username
 * Will @return Integer: 1 or greater = session{s) invalidated, 0 = no sessions to invalidate, -1 = insufficient permissions
 */
function terminateAllUserSessions($sessionToken, $username) {
    global $sessionTable;
    global $usersTable;
    $usernameId =  mysqli_fetch_row(mySQLQuery("SELECT `userId` FROM `" . $usersTable ."` WHERE `username` = '" . $username . "';"))[0];
    $sessionUserId = mysqli_fetch_row(mySQLQuery("SELECT `userId` FROM `" . $sessionTable ."` WHERE `sessionToken` = '" . $sessionToken . "';"))[0];
    if ($usernameId == $sessionUserId || canDoFromSession($sessionToken, "Sessions.Terminate.Others")) {
        $count = mysqli_num_rows(mySQLQuery("SELECT `sessionToken` FROM `" . $sessionTable . "` WHERE `sessionToken`='" . $sessionToken . "' AND `expiry`>" . time() . ";"));
        mySQLQuery("UPDATE `sessions` SET `expiry` = '" . time() . "' WHERE `userId` ='" . $usernameId . "';");
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        mySQLQuery("UPDATE `sessions` SET `lastIp` = '" . $ip . "' WHERE `userId` ='" . $usernameId . "';");
        return $count;
    } else {
        return -1;
    }
}