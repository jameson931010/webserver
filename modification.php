<?php
require_once("connection.php");
if($_SERVER["REQUEST_METHOD"] === "POST") {
    $vocabulary = test_input($_POST['vocabulary']);
    $section = test_input($_POST['section']);
    $entry = test_input($_POST['entry']);
    #$sentence = test_input($_POST['sentence']);
    $result = sql_query("SELECT content FROM english WHERE vocabulary=\"$vocabulary\"");
    if(mysqli_num_rows($result) <= 0) {
        echo json_encode(["error" => "No vocabulay found."]);
        exit;
    }
    $res = json_decode(mysqli_fetch_array($result, MYSQLI_ASSOC)['content']);
    switch($section) {
        case 'pronounciation':
            if(count($res->Pronounciations) == 1) sql_query("UPDATE english SET content = JSON_REMOVE(content, '$.Pronounciations') WHERE vocabulary='$vocabulary'");
            else if($entry >= count($res->Pronounciations)) {
                echo json_encode(["error" => "No such entry."]);
                exit;
            }
            else sql_query("UPDATE english SET content = JSON_REMOVE(content, '$.Pronounciations[$entry]') WHERE vocabulary='$vocabulary'");
            break;
        default:
            echo json_encode(["error" => "No such section."]);
            exit;
    }
    echo json_encode(["success" => "$section$entry removed"]);
}
