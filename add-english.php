<?php
require_once("connection.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Accounting</title>
        <script src="modification.js" async></script>
        <script src="https://kit.fontawesome.com/a211388c46.js" crossorigin="anonymous" defer></script>
        <style>
            :root{
                --account: #FDEEB6;
                --diary: #FDCE9E;
                --english: #8BBD90;
                --time: #b8a372;
                --bg: rgba(165, 204, 243, 1);
                --trans-bg: rgba(165, 204, 243, 0.5);
                --dark-bg: #748FAA;
            }
            * {
                box-sizing: border-box;
            }
            p {
                font-size: 16px;
            }
            i {
                font-size: 10vh;
                color: var(--dark-bg);
            }
            table {
                border: 1px solid; border-radius: 10px;
                width: 100%; padding: 20px; border-spacing: 2vw 5px;
            }
            th {
                border-bottom: 1px solid var(--dark-bg);
                height: 4vh;
                vertical-align: center;
            }
            td {
                text-align: center; vertical-align: center;
                padding: 2px;
            }
            tr:hover {
                background-color: var(--dark-bg);
            }
            table i {
                font-size: 20px;
                color: red;
            }
            button {
                background-color: var(--bg);
                margin: 16px;
                border: none; border-radius: 5px;
            }
            button:hover {
                background-color: var(--dark-bg);
            }
            button:active {
                background-color: var(--bg);
            }

            html { color-scheme: light dark; }
            body { width: 35em; margin: 0 auto; color: black; vertical-align: center;
                font-family: Tahoma, Verdana, Arial, sans-serif; }
            #header {
                position: fixed; top: 0px; left: 0px;

                float: right;
                display: grid; grid-template-columns: minmax(30px, 1fr) minmax(30px, 1fr) 6fr minmax(50px, 1fr);
                height: 10%;
                width: 100%;
                border-radius: 5%;
                background-color: rgba(255,255,255,0.5);
            }
            #main {
                /*position: absolute; top: 0px; left: 0px; z-index: -1;
                height: 100%;*/
                position: absolute; top: 16%; left: 0px; z-index: -1;
                min-height: 100%; width: 100%;
                background-color: var(--bg);
            }
            #main .block {
                position: relative; top:16%; left: 5%;
                /*height: 20%;*/ width: 90%;
                display: flex;
                flex-direction: row; flex-wrap: wrap;
                gap: 16px;
                align-items: center; justify-content: center;
            }
            .element {
                flex: 1 1 calc(12.5% - 16px); /* Adjusts to 3 columns */
                min-width: 50px; /* Ensures a minimum size */
                display: flex; flex-direction: row;
                text-align: center; justify-content: center;
            }
            .element i {
                font-size: 20px;
                color: red;
            }
            #term {
                display: flex;
                flex-direction: column; flex-wrap: wrap;
                align-items: center; justify-content: center;
            }
            label, input, select {
                font-size: 3vw;
            }
            #header .block {
                display: flex; justify-content: center; align-items: center;
            }
            #header .item {
                display: flex;
                align-items: center;
                padding-left: 30px; padding-right: 30px;
                height: 60%;
            }
            #p_account, #account {
                background-color: var(--account);
            }
            #p_diary, #diary {
                background-color: var(--diary);
            }
            #p_english, #english {
                background-color: var(--english);
            } 
            #p_time, #time {
                background-color: var(--time);
            }
            #login-button {
                background-color: var(--dark-bg);
                color: white;
                border-radius: 50%;
            }
            #warning {
                display: flex;
                align-items: center; justify-content: center;
            }
        </style>
    </head>
    <body>
        <div class="session" id="header">
            <div class="block" id="home">
                <a href="index.php">
                    <div class="item" id="home-button">
                        <p><i class="fa-solid fa-house"></i></p>
                    </div>
                </a>
            </div>
            <div class="block" id="home-account">
                <a href="home-account.php">
                    <div class="item" id="account-button">
                        <p><i class="fa-solid fa-clipboard-list"></i></p>
                    </div>
                </a>
            </div>
            <div class="block" id="progress">
                <div class="item" id="p_account" style="order: 1">
                    <p>days no account</p>
                </div>
                <div class="item" id="p_diary" style="order: 2">
                    <p>days no diary</p>
                </div>
                <div class="item" id="p_english" style="order: 4">
                    <p>words to review</p>
                </div>
                <div class="item" id="p_time" style="order: 3">
                    <p>more schedules</p>
                </div>
            </div>
            <div class="block" id="datetime">
                <p><?php echo date("m/d (D) H:i")?></p>
                <!--<p><?php echo date("m/d (D) H:i:s")?></p>-->
            </div>
        </div>
        <div class="session" id="main">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="block" id="list">
                <div class="item" id="search">
                    <label for="vol">New word</label>
                    <input type="text" name="vol" id="vol">
                    <input type="submit">
                </div>
            </form>
            <?php
            if($_SERVER["REQUEST_METHOD"] === "POST") {
                if(empty($_POST['vol'])) {
                    echo "<div class=\"item\" id=\"warning\"><h1 style=\"color:red;font-size:5vh;\">Fill all blanks!!</h1></div>\n";
                }
                else {
                    $vol = test_input($_POST['vol']);
                    $result = sql_query("SELECT content FROM english WHERE vocabulary=\"$vol\"");
                    $res = null;
                    if(mysqli_num_rows($result) > 0) {
                        $res = json_decode(mysqli_fetch_array($result, MYSQLI_ASSOC)['content']);
                    }
                    else {
                        // Call the python file to crawl for the word.
                        $status = null;
                        exec(escapeshellcmd("/usr/share/nginx/py/.venv/bin/python3 /usr/share/nginx/py/web_crawling.py $vol"), $res, $status);
                        if($status == 0) {
                            $res = json_decode(implode("", $res)); // Turn into object
                            if($res === null) {
                                http_response_code(500);
                                die('Internal Server Error: Format error in scrawling result.');
                            }
                            //print_r($res);
                            //$res = json_encode($res, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_QUOT | JSON_HEX_APOS);
                            $t = json_encode($res, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                            $dum = 1;
                            $mod->bind_param("sis", $vol, $dum, $t);
                            $mod->execute();
                            $mod->close();
                            #$res = addslashes(json_encode($res, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_QUOT | JSON_HEX_APOS));
                            #echo($res);
                            #sql_query("INSERT INTO english (vocabulary, source_id, content) VALUES (\"$vol\", 1, \'$res\';");
                        }
                        else {
                            echo "<div class=\"item\" id=\"warning\"><h1 style=\"color:red;font-size:5vh;\">Vocabulary Not Found!!</h1></div>\n";
                        }
                    }
                }
            }
            ?>
            <div class="block" id="Vocabulary">
                <h1 id="vocabulary"><?php echo $vol?></h1>
            </div>
            <div class="block" id="Pronounciations">
                <table>
                    <thead>
                        <tr>
                            <th scope="col">Part of Speech</th>
                            <th scope="col">Pronounciation(UK)</th>
                            <th scope="col">Pronounciation(US)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($res->Pronounciations !== null) {
                            //print_r($res);
                            $cnt = 0;
                            foreach($res->Pronounciations as $entry) {
                                echo "<tr id=\"Pronounciations_$cnt\">\n";
                                foreach($entry as $td) {
                                    echo "<td>{$td}</td>\n";
                                }
                                echo "<td><button onclick=\"remove(this)\"><i class=\"fa-solid fa-circle-xmark\"></i></button></td>\n";
                                echo "</tr>\n";
                                $cnt++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="block" id="Translates">
                <table>
                    <thead>
                        <th scope="col">Chinese</th>
                        <th scope="col">Usage</th>
                        <th scope="col">English</th>
                        <th scope="col">Example</th>
                    </thead>
                    <tbody>
                        <?php
                        if($res->Translates !== null) {
                            $cnt = 0;
                            foreach($res->Translates as $entry) {
                                $usage = implode("; ", array_map(fn($element)=> is_array($element)?end($element):$element, $entry->Usage)); // Implode the result, which is the element itself or the last element of the array.
                                $example = implode("<br>\n", $entry->Example);
                                echo <<<RESULT
                                <tr id=Translates_{$cnt}>
                                    <td>{$entry->Chinese}</td>
                                    <td>{$usage}</td>
                                    <td>{$entry->English}</td>
                                    <td>{$example}</td>
                                    <td><button onclick="remove(this)"><i class="fa-solid fa-circle-xmark"></i></button></td>
                                </tr>
                                RESULT;
                                $cnt++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="block" id="Examples">
                <table>
                    <thead>
                        <th scope="col">Usage</th>
                        <th scope="col">Sentence</th>
                    </thead>
                    <tbody>
                    <?php
                    if($res->Examples !== null) {
                        $cnt = 0;
                        foreach($res->Examples as $entry) {
                            $example = implode("<br>\n", $entry[1]);
                            echo <<<RESULT
                            <tr id=Examples_{$cnt}>
                                <td>{$entry[0]}</td>
                                <td>{$example}</td>
                                <td><button onclick="remove(this)"><i class="fa-solid fa-circle-xmark"></i></button></td>
                            <tr>
                            RESULT;
                            $cnt++;
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <div class="block" id="Extensions">
                <?php
                if($res->Extensions !== null) {
                    $cnt = 0;
                    foreach($res->Extensions as $entry) {
                        echo <<<RESULT
                        <div class="element" id="Extensions_{$cnt}">
                            <p>$entry</p>
                            <button onclick="remove(this)"><i class="fa-solid fa-circle-xmark"></i></button>
                        </div>
                        RESULT;
                        $cnt++;
                    }
                }
                ?>
            </div>
        </div>
    </body>
</html>
