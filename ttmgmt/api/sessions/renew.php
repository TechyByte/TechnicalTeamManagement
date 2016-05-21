<?php

include_once("../includes/config.php");

function renewSession($sessionToken) {
    global $sessionTable;
    global $tokenRenewalLength;
    mySQLQuery("UPDATE `" . $sessionTable . "` SET `expiry` = '" . (time() + ($tokenRenewalLength * 60)) . "' WHERE `sessionToken` = '" . $sessionToken . "';");
}