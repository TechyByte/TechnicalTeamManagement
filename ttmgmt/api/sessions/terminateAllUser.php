<?php

include_once("../includes/mysql.php");
include_once("../includes/config.php");
include_once("../permissions/includes.php");

/**
 * @param $sessionToken
 * @param $usernameId
 * @return Integer: 1 or greater = session{s) invalidated, 0 = no sessions to invalidate, -1 = insufficient permissions
 */
function terminateAllUserSessions($sessionToken, $usernameId) {
    global $sessionTable;
    global $usersTable;
    $sessionUserId = mysqli_fetch_row(mySQLQuery("SELECT `userId` FROM `" . $sessionTable ."` WHERE `sessionToken` = '" . $sessionToken . "';"))[0];
    if ($usernameId == $sessionUserId || canDoFromSession($sessionToken, "Sessions.Terminate.Others")) {
        $count = mysqli_num_rows(mySQLQuery("SELECT `sessionToken` FROM `" . $sessionTable . "` WHERE `sessionToken`='" . $sessionToken . "' AND `expiry`>" . time() . ";"));
        mySQLQuery("UPDATE `". $sessionTable . "` SET `expiry` = '" . time() . "' WHERE `userId` ='" . $usernameId . "';");
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        mySQLQuery("UPDATE `". $sessionTable . "` SET `lastIp` = '" . $ip . "' WHERE `userId` ='" . $usernameId . "';");
        return $count;
    } else {
        return -1;
    }
}