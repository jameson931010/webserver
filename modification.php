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
    if((! in_array($section, ["Pronounciations", "Translates", "Examples", "Extensions"])) || ! isset($res->$section)) {
        echo json_encode(["error" => "No such section.$section"]);
        exit;
    }

    if(count($res->$section) == 1) sql_query("UPDATE english SET content = JSON_REMOVE(content, '$.$section') WHERE vocabulary='$vocabulary'");
    else if($entry >= count($res->$section)) {
        echo json_encode(["error" => "No such entry."]);
        exit;
    }
    else sql_query("UPDATE english SET content = JSON_REMOVE(content, '$.{$section}[$entry]') WHERE vocabulary='$vocabulary'");

    $result = sql_query("SELECT content FROM english WHERE vocabulary = \"$vocabulary\"");
    if(empty((array) json_decode(mysqli_fetch_array($result, MYSQLI_ASSOC)['content']))) {
        sql_query("DELETE FROM english WHERE vocabulary = \"$vocabulary\"");
        echo json_encode(["success" => "$vocabulary removed"]);
        exit;
    }
    echo json_encode(["success" => "$section$entry removed"]);
}
