<?php
require_once("connection.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Accounting</title>
        <script src="extend.js" async></script>
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
            html { color-scheme: light dark; }
            body { width: 35em; margin: 0 auto; color: black; vertical-align: center;
                font-family: Tahoma, Verdana, Arial, sans-serif;
            }
            button {
                font-size: 30px; color: black;
                background-color: var(--dark-bg);
                text-align: center;
                border: none; border-radius: 5px;
                padding: 0 10px; margin: 10px;
            }
            button:hover {
                background-color: var(--bg);
            }
            button:active {
                background-color: var(--dark-bg);
            }
            #header {
                position: fixed; top: 0px; left: 0px;

                float: right;
                display: grid; grid-template-columns: 4fr minmax(50px, 1fr) minmax(50px, 1fr);
                height: 10%;
                width: 100%;
                border-radius: 5%;
                background-color: rgba(255,255,255,0.5);
            }
            #main {
                position: absolute; top: 0px; left: 0px; z-index: -1;
                height: 100%;
                width: 100%;
                background-color: var(--bg);
            }
            #main .block {
                position: relative; top:16%; left: 5%;
                height: 80%; width: 90%;
                display: flex;
                flex-direction: column;
                align-items: center; justify-content: center;
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
            #list {
                height: 100%; width: 100%;
                display: flex; flex-wrap: wrap;
                align-items: center; justify-content: center;
            }
        </style>
    </
    head>
    <body>
        <div class="session" id="header">
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
            <div class="block" id="login">
                <div class="item" id="login-button">
                    <p><b>Log in&nbsp;&nbsp;</b><i class="fa-solid fa-circle-user" style="font-size: 18px;"></i></p>
                </div>
            </div>
        </div>
        <div class="session" id="main">
            <div id="list">
                <?php
                $year = date("Y");
                $result_list = sql_query("SELECT vocabulary FROM english ORDER BY time_added LIMIT 25;");
                while($row = mysqli_fetch_assoc($result_list)) {
                    echo <<<TABLE
                    <button class="vol" onclick="lookup(this)">{$row['vocabulary']}</button>
                    TABLE;
                }
                ?>
            </div>
        </div>
    </body>
</html>
