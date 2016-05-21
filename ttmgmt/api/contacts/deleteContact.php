<?php

include_once("../includes/mysql.php");
include_once("../includes/config.php");
include_once("../permissions/includes.php");

/**
 * @param $sessionToken
 * @param $contactId
 * @return Integer: -2 for no permissions, -1 for bad request, 0 for no token, else will @return 1 if successful
 */
function deleteContact($sessionToken, $contactId) {
    global $contactsTable;
    if (!empty($sessionToken)) {
        if (canDoFromSession($_POST["session"], "Contacts.Delete")) {
            if (isset($contactID) && !empty($contactId)) {
                mySQLQuery("DELETE FROM `" . $contactsTable . "` WHERE `contactId` = " . $contactId);
            } else {
                return -1;
            }
        } else {
            return -2;
        }
    } else {
        return 0;
    }
}