<?php
    declare(strict_types=1);
    $file = fopen("/etc/nginx/password", 'r');
    if($file) {
        $PW = fgets($file);
        if($PW != false) {
            $PW = trim($PW);
        }
        else {
            http_response_code(500);
            die('Internal Server Error: Format error in required file.');
        }
        fclose($file);
    }
    else {
        http_response_code(500);
        die('Internal Server Error: Unable to open the required file.');
    }
    $conn = mysqli_connect("localhost", "jaime", $PW, "jaime");
    if(!$conn) {
        http_response_code(500);
        die("Connection failed: " . mysqli_connect_error());
    }
    
    function sql_query(string $sql) {
        global $conn;
        $result = mysqli_query($conn, $sql);
        if(!$result) {
            http_response_code(500);
            die("Query failed: " . mysqli_error($conn));
        }
        return $result;
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data); # add_slashes($data) add slashes before quotes.
        $data = htmlspecialchars($data); # strip_tags($dara) completely remove PHP and HTML tags.
        return $data;
    }
?>
