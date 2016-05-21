<?php

/**
 * @param $sessionToken
 *
 * Will @return integer: any of the following:
 *  -3 --> Expired Session
 *  -2 --> Invalid Session
 *  -1 --> Duplicate Session
 *  0 or more --> Session-linked UserID
 */
function checkSession($sessionToken) {
    global $sessionTable;
    $result = mySQLQuery("SELECT * FROM `" . $sessionTable . "` WHERE `sessionId`='" . $sessionToken . "';");
    if (mysqli_num_rows($result) == 1) {
        $session = mysqli_fetch_row($result);
        return currentUnix() < $session[3] ? $session[2] : -3;
    } elseif (mysqli_num_rows($result) > 1) {
        return -1;
    } else {
        return -2;
    }
}

function isSessionValid($sessionToken) {
    return checkSession($sessionToken) >= 0;
}