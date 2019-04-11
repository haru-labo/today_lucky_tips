<?php
$log_file = 'log/log.txt';
$name_date = '';
$tips = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (exist_check($_POST['name']) === TRUE && exist_check($_POST['tips']) === TRUE) {
        $name_date = date('Y-m-d H:i:s') . "\t" . $_POST['name'] . "\n";
        $tips = $_POST['tips'] . "\n";
    }

    if (($fp = fopen($log_file, 'a')) !== FALSE) {
        if (fwrite($fp, $name_date) === FALSE || fwrite($fp, $tips) === FALSE) {
            print '書き込みに失敗しました' . $log_file;
        }
        fclose($fp);
    }
}

function exist_check(string $str): bool {
    if (!isset($str) || $str === '') {
        return false;
    } elseif(preg_match( "/^\s|　+$/", $str)) {
        return false;
    } else {
        return true;
    }
}

function e(string $str, string $charset = 'UTF-8'):string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, $charset);
}



?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Today's Lucky Tips</title>
</head>
<body>
    <header>
        <h1>☆★☆Today's&nbsp;Lucky&nbsp;Tips★☆★</h1>
    </header>
    <article>
        <section class="share">
            <h2>今日の幸せをシェアしよう！</h2>
            <form method="post" class="form">
                <p>お名前&#040;Name&#041;:<input type="text" name="name"></p>
                <p>今日の幸せな出来事&#040;Lucky&nbsp;Tips&#041;:<input type="text" name="tips"></p>
                <p><input type="submit" name="submit" value="シェア(Share)"></p>
            </form>
        </section>
        <section class="contents">
            <h3>幸せな出来事たち</h3>
            <div class="tips">
                <p></p>
                <p></p>
            </div>
        </section>
    </article>
</body>
</html>