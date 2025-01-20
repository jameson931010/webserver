<?php
require_once("connection.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Accounting</title>
        <script src="https://kit.fontawesome.com/a211388c46.js" crossorigin="anonymous"></script>
        <!--
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
                color: var(--dark-bg);
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
                <div class="item" id="date">
                    <label for="day">DATE</label>
                    <input type="date" name="day" id="day">
                </div>
                <div class="item" id="term">
                    <label for="category">Category</label>
                    <select name="category" id="category">
                        <option value="others" selected>其他</option>
                        <option value="breakfast">早餐</option>
                        <option value="lunch">午餐</option>
                        <option value="dinner">晚餐</option>
                        <option value="snack">點心</option>
                        <option value="transportation">交通</option>
                        <option value="lending">借別人錢</option>
                        <option value="borrowing">跟別人借錢</option>
                        <option value="salary">薪水</option>
                        <option value="prize">獎金、意外收入</option>
                        <option value="pocket money">零用錢</option>
                        <option value="meals of others">別人的餐錢</option>
                        <option value="family item">家人的物品</option>
                        <option value="personal item">個人物品</option>
                        <option value="medical">醫藥</option>
                        <option value="fee and charge">入場費、規費和雜支</option>
                    </select>
                    <label for="store">Store</label>
                    <input type="text" name="store" id="store">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name">
                    <label for="money">Money</label>
                    <input type="text" name="money" id="money">
                </div>
                <input type="submit">
            </form>
            <?php
            if($_SERVER["REQUEST_METHOD"] === "POST") {
                $required = ["day", "category", "store", "name", "money"];
                $checked = TRUE;
                foreach($required as $term) {
                    if(empty($_POST[$term])) {
                        $checked = FALSE;
                        break;
                    }
                }
                if($checked) {
                    $day = test_input($_POST['day']);
                    $category = test_input($_POST['category']);
                    $store = test_input($_POST['store']);
                    $name = test_input($_POST['name']);
                    $money = test_input($_POST['money']);
                    #foreach($required as $term) {
                    #    echo "<div><p>${$term}</p></div>\n";
                    #}
                    sql_query("INSERT INTO account (day, category, store, name, money) VALUES ('$day','$category','$store','$name','$money');");
                }
                else {
                    echo "<div class=\"item\" id=\"warning\"><h1 style=\"color:red;font-size:5vh;\">Fill all blanks!!</h1></div>\n";
                }
            }
            ?>
        </div>
    </body>
</html>
