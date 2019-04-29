<?php
$log_file = 'log/log.txt';
$name_date = '';
$tips = '';

//読み込んだデータ格納用配列
$data = [];

//入力ボックス表示用
$in_name = '';
$in_tips = '';

//エラー用
$arr_valid = ['msg' => [], 'result' => true];
$valid_msg = [];

//POST時の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //入力ボックス表示用にPOSTされたデータを代入
    $in_name = e($_POST['name']);
    $in_tips = e($_POST['tips']);

    //バリデーション
    $arr_valid = validation($_POST);
    if ($arr_valid['result'] === TRUE) {
        $name_date = date('Y/m/d H:i:s') . PHP_EOL . $_POST['name'] . PHP_EOL;
        //出来事は改行を削除
        $tips = del_enter($_POST['tips']) . PHP_EOL;

        //入力ボックスをクリア
        $in_name = '';
        $in_tips = '';
    }

    //ログへの書き込み
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
        $count = 0;
        $arr_number = 0;

        //データを表示ブロックごとにまとめる
        while (($tmp = fgets($fp)) !== FALSE) {
            $data[$arr_number][] = e($tmp);
            $count++;
            if ($count % 3 === 0) {
                $arr_number++;
            }
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

    if (!exist_check($check_data['name'])) {
        $error_msg[] = '名前を入力してください';
        $result = false;
    }

    if (!exist_check($check_data['tips'])) {
        $error_msg[] = '出来事を入力してください';
        $result = false;
    }

    if (!$result) {
        $arr_ret['msg'] = $error_msg;
        $arr_ret['result'] = $result;
        return $arr_ret;
    }
        return max_length_check($check_data);
}

//null、空文字、空白文字のチェック
function exist_check(string $str): bool {
    return !(!isset($str) || $str === '' || preg_match( "/^\s|　+$/", $str));
}

//文字数チェック
function max_length_check($check_data) {
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

//改行の削除
function del_enter(string $str): string {
    return str_replace(PHP_EOL, '', $str);
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
    <title>Today's&nbsp;happiness</title>
    <link rel="stylesheet" href="css/todays_happiness.css">
    <link href="https://fonts.googleapis.com/css?family=Kosugi+Maru" rel="stylesheet">
    <link rel="apple-touch-icon" type="image/png" href="img/favicons/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="img/favicons/icon-192x192.png">
</head>
<body>
    <header>
        <h1>Today&#39;s&nbsp;Happiness</h1>
    </header>
    <article>
    <?php
        if(is_array($arr_valid['msg'])){
            print '<ul class="error-msg">';
            foreach ($arr_valid['msg'] as $msg) {
                print '<li>' . e($msg) . '</li>';
            }
            print '</ul>';
        }
    ?>
        <section class="share">
            <h2>今日の幸せをシェアしよう！</h2>
            <form method="post">
                <p class="form-label"><label for="name">お名前&#040;Your&nbsp;Name&#041;</label></p>
                <p class="form-name"><input type="text" id="name" name="name" placeholder="20文字以内" <?php print "value = '$in_name'"; ?>></p>
                <p class="form-label"><label for="tips">今日の幸せな出来事&#040;Today&#39;s&nbsp;Happiness&#41;</label></p>
                <p class="form-tips"><textarea id="tips" name="tips" row="8" cols="13" wrap="soft" placeholder="100文字以内"><?php print $in_tips; ?></textarea></p>
                <p><input id=submit type="submit" name="submit" value="シェア(Share)"></p>
            </form>
        </section>
        <section class="contents">
            <h3>幸せな出来事たち</h3>
            <?php
            if(is_array($data)){
                foreach ($data as $disp_array) {
                    print '<div class="content-wrapper">';
                        print '<p class="tips">' . $disp_array[2] . '</p>';
                        print '<div class="date-name-wrapper">';
                            print '<p class="name">' . $disp_array[1] . '</p>';
                            print '<p class="input-date">' . $disp_array[0] . '</p>';
                        print '</div>';
                    print '</div>';
                }
            }
            ?>
        </section>
    </article>
</body>
</html>