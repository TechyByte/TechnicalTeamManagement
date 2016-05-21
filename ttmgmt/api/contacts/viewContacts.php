<?php

include_once("../includes/config.php");
include_once("../includes/mysql.php");

/**
 * @param $sessionToken
 * @requires POST variables: id, nameQuery
 * @requires GET variables: action, filter, detail
 * Will @return String JSON response (see API docs) if successful ("{[]}" if no contacts found), Integer -1 insufficient permissions or 0 for malformed request.
 */
function viewContacts($sessionToken) {
    global $contactsTable;
    if (canDoFromSession($sessionToken, "Contacts.View")) {
        switch (strtolower($_GET["filter"])) {
            case "id":
                if (isset($_POST["id"]) && !empty($_POST["id"])) {
                    $whereClause = " WHERE `contactId` = " . $_POST["id"];
                } else {
                    return 0;
                }
                break;
            case "name":
                if (isset($_POST["nameQuery"]) && !empty($_POST["nameQuery"])) {
                    $whereClause = " WHERE `firstName` LIKE '%" . $_POST["nameQuery"] . "%' OR `lastName` LIKE '%" . $_POST["nameQuery"] . "%'";
                } else {
                    return 0;
                }
                break;
            default:
                $whereClause = "";
                break;
        }
        switch (strtolower($_GET["detail"])) {
            case "name":
                $fieldClause = "`contactId`, `firstName`, `lastName`, `nickname`, `organisation`";
                $fields = ["contact_id", "first_name", "last_name", "nickname", "organisation"];
                break;
            case "contact":
                $fieldClause = "`contactId`, `firstName`, `lastName`, `dayPhone`, `evePhone`, `email`";
                $fields = ["contact_id", "first_name", "last_name", "day_phone", "eve_phone", "email"];
                break;
            default:
                $fieldClause = "*";
                $fields = ["contact_id", "first_name", "last_name", "nickname", "organisation", "day_phone", "eve_phone", "email", "person_type"];
        }
        $query = "SELECT " . $fieldClause . " FROM " . $contactsTable . $whereClause . ";";
        $results = mySQLQuery($query);
        $json = "{";
        $numResults = mysqli_num_rows($results);
        if ($numResults > 0) {
            $i = 1;
            while($row = mysqli_fetch_array($results)) {
                $json .= "[";
                $j = 0;
                foreach ($row as $field) {
                    $json .= '"' . $fields[$j] . '": "' . $field . '"';
                    if ($j != count($row)-1) {
                        $json .= ",";
                        $j+=1;
                    } else {
                        $json .= "]";
                    }
                }
                if ($i != $numResults) {
                    $json .= ",";
                    $i+=1;
                } else {
                    $json .= "}";
                }
                return $json;
            }
        } else {
            return "{[]}";
        }
    } else {
        return -1;
    }
}