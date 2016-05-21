<?php

include_once("../includes/mysql.php");
include_once("../includes/config.php");
include_once("../permissions/includes.php");

/**
 * @param $sessionToken
 * @param $contactId
 * @param $fieldToEdit
 * @return Integer: -2 for no permissions, -1 for bad request, 0 for no token, else will @return 1 if successful
 */
function editContact($sessionToken, $contactId, $fieldToEdit) {
    global $contactsTable;
    if (!empty($sessionToken)) {
        if (canDoFromSession($_POST["session"], "Contacts.Edit")) {
            if (isset($contactID) && !empty($contactId) && isset($fieldToEdit) && !empty($fieldToEdit) && isset($_POST["data"]) && !empty($_POST["data"])) {
                $fieldsToDb = array(
                    "first_name" => "firstName",
                    "last_name" => "lastName",
                    "nickname" => "nickname",
                    "organisation" => "organisation",
                    "day_phone" => "dayPhone",
                    "eve_phone" => "evePhone",
                    "email" => "email",
                    "person_type" => "personType"
                );
                if (array_key_exists($fieldToEdit, $fieldsToDb)) {
                    mySQLQuery("UPDATE `" . $contactsTable . "` SET `" . $fieldsToDb->$fieldToEdit . "` = '" . $_POST["data"] . "'WHERE `contactId` = " . $contactId);
                } else {
                    return -1;
                }
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