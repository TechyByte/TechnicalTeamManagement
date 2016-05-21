<?php

/**
 * @param $sessionToken
 *
 * Will @return integer: any of the following:
 *  -3 --> Expired Session
 *  -2 --> Invalid Session
 *  -1 --> Duplicate Session
 *  0 or more --> UNIX Expiry Time
 */
function checkSessionExpiry($sessionToken) {
    global $sessionTable;
    /** @var MySQLi Object $result: has session information for matching session token*/
    $result = mySQLQuery("SELECT * FROM `" . $sessionTable . "` WHERE `sessionId`='" . $sessionToken . "';");
    if (mysqli_num_rows($result) == 1) {
        $session = mysqli_fetch_row($result);
        return time() < $session[3] ? $session[3] : -3;
    } elseif (mysqli_num_rows($result) > 1) {
        return -1;
    } else {
        return -2;
    }
}