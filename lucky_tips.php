<?php
$log_file = 'log/log.txt';
$name_date = '';
$tips = '';
$data = [];
$arr_valid = ['msg' => [], 'result' => true];
$valid_msg = [];

//ログへの書き込み
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $arr_valid = validation($_POST);
    if ($arr_valid['result'] === TRUE) {
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

//読み込みして表示
if (is_readable($log_file) === TRUE) {
    if (($fp = fopen($log_file, 'r')) !== FALSE) {
        while (($tmp = fgets($fp)) !== FALSE) {
            $data[] = e($tmp);
        }
        fclose($fp);
    }
} else {
    $data[] = 'ファイルが存在していません';
}


//バリデーション
function validation($check_data) {
    $arr_ret = [];
    $error_msg = [];
    $result = true;

    if (exist_check($check_data['name']) === FALSE) {
        $error_msg[] = '名前を入力してください';
        $result = false;
    }

    if (exist_check($check_data['tips']) === FALSE) {
        $error_msg[] = '出来事を入力してください';
        $result = false;
    }

    if ($result === FALSE) {
        $arr_ret['msg'] = $error_msg;
        $arr_ret['result'] = $result;
        return $arr_ret;
    } else {
        return length_check($check_data);
    }
}

//null、空文字、空白文字のチェック
function exist_check(string $str): bool {
    if (!isset($str) || $str === '' || preg_match( "/^\s|　+$/", $str)) {
        return false;
    } else {
        return true;
    }
}

//文字数チェック
function length_check($check_data) {
    $arr_ret['result'] = true;
    $error_msg = [];
    if (mb_strlen($check_data['name']) > 20) {
        $error_msg[] = 'お名前は20文字以内で入力してください';
        $arr_ret['result'] = false;
    }

    if (mb_strlen($check_data['tips']) > 100) {
        $error_msg[] = '出来事は100文字以内で入力してください';
        $arr_ret['result'] = false;
    }

    $arr_ret['msg'] = $error_msg;

    return $arr_ret;
}

//エスケープ
function e(string $str, string $charset = 'UTF-8'):string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, $charset);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Today's Lucky Tips</title>
    <link rel="stylesheet" href="css/lucky_tips.css">
</head>
<body>
    <header>
        <h1>☆★☆Today's&nbsp;Lucky&nbsp;Tips★☆★</h1>
    </header>
    <article>
        <section class="share">
            <h2>今日の幸せをシェアしよう！</h2>
            <?php 
            if(is_array($arr_valid['msg'])){
               foreach ($arr_valid['msg'] as $msg) {
                    print '<ul class="error_msg">';
                    print '<li>' . e($msg) . '</li>';
                    print '</ul>';
                }
            }
            ?>
            <form method="post" class="form">
                <p>お名前&#040;Name&#041;:<input type="text" name="name" placeholder="20文字以内"></p>
                <p>今日の幸せな出来事&#040;Lucky&nbsp;Tips&#041;:<input type="text" name="tips" placeholder="100文字以内"></p>
                <p><input type="submit" name="submit" value="シェア(Share)"></p>
            </form>
        </section>
        <section class="contents">
            <h3>幸せな出来事たち</h3>
            <?php foreach ($data as $key => $line) {
                if (intval($key) % 2 === 0) {
                    print '<div>';
                    print '<p>' . $line . '</p>';
                } else {
                    print '<p>' . $line . '</p>';
                    print '</div>';
                }
            }
            ?>
        </section>
    </article>
</body>
</html>