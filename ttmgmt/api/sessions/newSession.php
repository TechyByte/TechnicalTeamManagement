<?php

include_once("../includes/config.php");
include_once("../includes/mysql.php");

/**
 * @param $username
 * @param $password
 * Will @return String sessionToken if credentials valid or @return Integer 1 if credentials are NOT valid.
 *      NOTE: If @return Integer 2 the user account is disabled
 */
function newSession($username, $password) {
    global $sessionTable;
    global $tokenValidity;
    global $usersTable;
    $user = mySQLQuery("SELECT * FROM `" . $usersTable . "` WHERE username='" . $username ."';");
    if (mysqli_num_rows($user)==1) {
        $user = mysqli_fetch_row($user);
        if ($user[4]=="PHP") {
            if ($username==$user[2]) {
                if (password_verify($password, $user[3])) {
                    if ($user[6]==1) {
                        $userId = $user[0];
                    } else {
                        return 2;
                    }
                }
            } else {
                return 1;
            }
        } else {
            if (sha1($password . $user[4])==$user[3]) {
                if ($user[6]==1) {
                    $userId = $user[0];
                } else {
                    return 2;
                }
            } else {
                return 1;
            }
        }
    } else {
        return 1;
    }
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $randomString = "";
    for ($i = 0; $i < 16; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    $sessionToken = md5($randomString . "TechyByteEasterEgg" . $username);
    $expiryUnix = time() + ($tokenValidity * 60);
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    $newSessionId = mysqli_fetch_row(mySQLQuery("SELECT MAX(sessionId) FROM `" . $sessionTable . "`;"))[0]+1;
    mySQLQuery("INSERT INTO `" . $sessionTable . "` (`sessionId`, `sessionToken`, `userId`, `expiry`, `startIp`, `lastIP`, `platform`) VALUES ('" . $newSessionId . "', '" . $sessionToken . "', '" . $userId . "', '" . $expiryUnix . "', '" . $ip . "', '" . $ip . "', 'API');");
    return $sessionToken;
}