<?php
/**
 *  All Client-facing session interactions begin and end here
 */

include_once("../includes/config.php");

if (isset($_GET["action"])) {
    switch(strtolower($_GET["action"])) {
        case "create":
            if (isset($_POST["username"]) && isset($_POST["password"])) {
                if (!empty($_POST["username"]) && !empty($_POST["username"])) {
                    include_once("newSession.php");
                    $result = newSession($_POST["username"], $_POST["password"]);
                    if ($result == 1) {
                        http_response_code(401);
                    } elseif ($result == 2) {
                        http_response_code(403);
                    } else {
                        echo($result);
                    }
                } else {
                    http_response_code(401);
                }
            } else {
                http_response_code(400);
            }
            break;
        case "check":
            if (isset($_POST["session"])) {
                if (!empty($_POST["session"])) {
                    include_once("checkSession.php");
                    $result = checkSession($_POST["session"]);
                    if ($result == -3 || $result == -2) {
                        http_response_code(440);
                    } elseif ($result == -1) {
                        http_response_code(403);
                    }
                } else {
                    http_response_code(401);
                }
            } else {
                http_response_code(400);
            }
            break;
        case "check_expiry":
            if (isset($_POST["session"])) {
                if (!empty($_POST["session"])) {
                    include_once("checkSessionExpiry.php");
                    $result = checkSessionExpiry($_POST["session"]);
                    if ($result == -3 || $result == -2) {
                        http_response_code(440);
                    } elseif ($result == -1) {
                        http_response_code(403);
                    }
                } else {
                    http_response_code(401);
                }
            } else {
                http_response_code(400);
            }
            break;
        case "renew":
            if (isset($_POST["session"])) {
                if (!empty($_POST["session"])) {
                    include_once("checkSession.php");
                    if (isSessionValid($_POST["session"])) {
                        include_once("renew.php");
                        renewSession($$_POST["session"]);
                        http_response_code(201);
                    } else {
                        http_response_code(440);
                    }
                } else {
                    http_response_code(401);
                }
            } else {
                http_response_code(400);
            }
            break;
        case "terminate":
            if (isset($_POST["session"])) {
                if (!empty($_POST["session"])) {
                    include_once("terminate.php");
                    $result = terminateSession($_POST["session"]);
                    if ($result > 0) {
                        http_response_code(202);
                    } else {
                        http_response_code(402);
                    }
                } else {
                    http_response_code(401);
                }
            } else {
                http_response_code(400);
            }
            break;
        case "user_terminate":
            if (isset($_POST["session"]) && isset($_GET["username"])) {
                if (!empty($_POST["session"]) && !empty($_POST["username"])) {
                    include_once("terminateAllUser.php");
                    $result = terminateAllUserSessions($_POST["session"], $_POST["username"]);
                    if ($result > 0) {
                        http_response_code(202);
                    } elseif ($result == 0) {
                        http_response_code(402);
                    } else {
                        http_response_code(403);
                    }
                } else {
                    http_response_code(401);
                }
            } else {
                http_response_code(400);
            }
            break;
        default:
            http_response_code(501);
            break;
    }
} else {
    http_response_code(400);
}