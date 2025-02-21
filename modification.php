<?php
require_once("connection.php");
class RTN_CODE {
    const ERR_VOL = -1;
    const ERR_SEC = -2;
    const ERR_ENT = -3;
    const SUC_ENT = 0;
    const SUC_VOL = 1;
}
if($_SERVER["REQUEST_METHOD"] === "POST") {
    $vocabulary = test_input($_POST['vocabulary']);
    $section = test_input($_POST['section']);
    $entry = test_input($_POST['entry']);
    #$sentence = test_input($_POST['sentence']);
    $result = sql_query("SELECT content FROM english WHERE vocabulary=\"$vocabulary\"");
    if(mysqli_num_rows($result) <= 0) {
        echo json_encode(["error" => RTN_CODE::ERR_VOL]);
        exit;
    }
    $res = json_decode(mysqli_fetch_array($result, MYSQLI_ASSOC)['content']);
    if($section === "all") {
        sql_query("DELETE FROM english WHERE vocabulary = \"$vocabulary\"");
        echo json_encode(["success" => RTN_CODE::SUC_VOL]);
        exit;
    }
    if((! in_array($section, ["Pronounciations", "Translates", "Examples", "Extensions"])) || ! isset($res->$section)) {
        echo json_encode(["error" => RTN_CODE::ERR_SEC]);
        exit;
    }

    if(count($res->$section) == 1) sql_query("UPDATE english SET content = JSON_REMOVE(content, '$.$section') WHERE vocabulary='$vocabulary'");
    else if($entry >= count($res->$section)) {
        echo json_encode(["error" => RTN_CODE::ERR_ENT]);
        exit;
    }
    else sql_query("UPDATE english SET content = JSON_REMOVE(content, '$.{$section}[$entry]') WHERE vocabulary='$vocabulary'");

    $result = sql_query("SELECT content FROM english WHERE vocabulary = \"$vocabulary\"");
    if(empty((array) json_decode(mysqli_fetch_array($result, MYSQLI_ASSOC)['content']))) {
        sql_query("DELETE FROM english WHERE vocabulary = \"$vocabulary\"");
        echo json_encode(["success" => RTN_CODE::SUC_ENT]);
    }
    echo json_encode(["success" => RTN_CODE::SUC_ENT]);
}
