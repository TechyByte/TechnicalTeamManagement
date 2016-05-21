<?php
/**
 *  All Client-facing contact interactions begin and end here
 */

include_once("../includes/config.php");
include_once("../sessions/checkSession.php");

if (isset($_POST["session"]) && !empty($_POST["session"])) {
    if (isSessionValid($_POST["session"])) {
        if (isset($_GET["action"])) {
            switch (strtolower($_GET["action"])) {
                case "add":
                    include_once("addContact.php");
                    $result = addContact($_POST["session"], $_POST["firstName"], $_POST["lastName"], $_POST["nickname"], $_POST["organisation"], $_POST["dayPhone"], $_POST["evePhone"], $_POST["email"], $_POST["personType"]);
                    if ($result == -2) {
                        http_response_code(403);
                    } elseif ($result == -1) {
                        http_response_code(400);
                    } elseif ($result == 0) {
                        http_response_code(401);
                    } else {
                        http_response_code(201);
                    }
                    break;
                case "view":
                    include_once("viewContacts.php");
                    $result = viewContacts($_POST["session"]);
                    if ($result == -1) {
                        http_response_code(403);
                    } elseif ($result === 0) {
                        http_response_code(400);
                    } elseif ($result == "{[]}") {
                        http_response_code(202);
                    } else {
                        echo $result;
                    }
                    break;
                case "delete":
                    include_once("deleteContact.php");
                    $result = addContact($_POST["session"], $_POST["firstName"], $_POST["lastName"], $_POST["nickname"], $_POST["organisation"], $_POST["dayPhone"], $_POST["evePhone"], $_POST["email"], $_POST["personType"]);
                    if ($result == -2) {
                        http_response_code(403);
                    } elseif ($result == -1) {
                        http_response_code(400);
                    } elseif ($result == 0) {
                        http_response_code(401);
                    } else {
                        http_response_code(201);
                    }
                    break;
                default:
                    http_response_code(501);
            }
        } else {
            http_response_code(400);
        }
    } else {
        http_response_code(403);
    }
} else {
    http_response_code(401);
}