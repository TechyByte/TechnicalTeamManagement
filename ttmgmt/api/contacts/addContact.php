<?php

include_once("../includes/mysql.php");
include_once("../includes/config.php");
include_once("../permissions/includes.php");

/**
 * @param $sessionToken
 * @param $firstName
 * @param $lastName
 * @param $nickname
 * @param $organisation
 * @param $dayPhone
 * @param $evePhone
 * @param $email
 * @param $personType
 * Will @return Integer: -2 for no permissions, -1 for bad request, 0 for no token, else will @return Integer of new contactId
 */
function addContact($sessionToken, $firstName, $lastName, $nickname, $organisation, $dayPhone, $evePhone, $email, $personType) {
    global $contactsTable;
    if (!empty($sessionToken)) {
            if (canDoFromSession($_POST["session"], "Contacts.Add")) {
            if (!empty($firstName) && !empty($lastName) && !empty($nickname) && !empty($organisation) && (!empty($dayPhone) || !empty($evePhone) || !empty($email)) && !empty($personType)) {
                if (isset($_POST["nickname"])) {
                    $nickname = $_POST["nickname"];
                } else {
                    $nickname = $_POST["firstName"];
                }
                $newContactId = mysqli_fetch_row(mySQLQuery("SELECT MAX(contactId) FROM `" . $contactsTable . "`;"))[0] + 1;
                $query = "INSERT INTO `" . $contactsTable . "` (`contactId`, `firstName`, `lastName`, `nickname`, `organisation`, `dayPhone`, `evePhone`, `email`, `personType`) VALUES (" . $newContactId . ", '" . $_GET["firstName"] . "', '" . $_GET["lastName"] . "', '" . $nickname . "', '" . $_GET["organisation"] . "', '" . strtolower(["dayPhone"]) . "', '" . strtolower($_GET["evePhone"]) . "', '" . strtolower($_GET["email"]) . "', '" . strtolower($_GET["personType"]) . "');";
                mySQLQuery($query);
                return $newContactId;
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