<?php
require_once("connection.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Home Page</title>
        <script src="https://kit.fontawesome.com/a211388c46.js" crossorigin="anonymous"></script>
        <!--
        <link href="../asset/css/fontawesome.css" rel="stylesheet" />
        <link href="../asset/css/brands.css" rel="stylesheet" />
        <link href="../asset/css/solid.css" rel="stylesheet" />
        -->
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
            a {
                text-decoration: none;
                color: black;
            }
            html { color-scheme: light dark; }
            body { width: 35em; margin: 0 auto; color: black; vertical-align: center;
                font-family: Tahoma, Verdana, Arial, sans-serif; }
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
            #header .block {
                display: flex; justify-content: center; align-items: center;
            }
            #header .item {
                display: flex;
                align-items: center;
                padding-left: 30px; padding-right: 30px;
                height: 60%;
            }
            #main .block {
                display: grid; grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr;
                grid-gap: 8% 5%;
                position: relative; top: 16%; left: 5%;
                height: 80%; width: 90%;
            }
            #main .item {
                height: 100%; width: 100%;
                display: flex;
                align-items: center; justify-content: center;
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
        </style>
    </head>
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
            <div class="block" id="task">
                <a href="add-account.php">
                    <div class="item" id="account">
                        <p><i class="fa-solid fa-file-invoice-dollar"></i></p>
                    </div>
                </a>
                <div class="item" id="diary">
                    <h1><i class="fa-solid fa-book"></i></h1>
                </div>
                <div class="item" id="english">
                    <h1><i class="fa-solid fa-a"></i><i class="fa-solid fa-b"></i><i class="fa-solid fa-c"></i></h1>
                </div>
                <div class="item" id="time">
                    <h1><i class="fa-solid fa-calendar-days"></i><h1/>
                </div>
            </div>
        </div>
    </body>
</html>
