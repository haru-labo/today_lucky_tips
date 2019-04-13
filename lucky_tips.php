<?php
$log_file = 'log/log.txt';
$name_date = '';
$tips = '';

//読み込んだデータ格納用配列
$data = [];

//エラー用
$arr_valid = ['msg' => [], 'result' => true];
$valid_msg = [];

//ログへの書き込み
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $arr_valid = validation($_POST);
    if ($arr_valid['result'] === TRUE) {
        $name_date = date('Y/m/d H:i:s') . "\n" . $_POST['name'] . "\n";
        //出来事は改行を削除
        $tips = del_enter($_POST['tips']) . "\n";
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
    <title>Today's Lucky Tips</title>
    <link rel="stylesheet" href="css/lucky_tips.css">
    <link href="https://fonts.googleapis.com/css?family=Kosugi+Maru" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Today&#39;s&nbsp;Lucky&nbsp;Tips</h1>
    </header>
    <article>
    <?php
        if(is_array($arr_valid['msg'])){
            foreach ($arr_valid['msg'] as $msg) {
                print '<ul class="error_msg">';
                print '<li>' . e($msg) . '</li>';
                print '</ul>';
            }
        }
    ?>
        <section class="share">
            <h2>今日の幸せをシェアしよう！</h2>
            <form method="post">
                <p class="form_label"><label for="name">お名前&#040;Your&nbsp;Name&#041;</label></p>
                <p class="form_name"><input type="text" id="name" name="name" placeholder="20文字以内"></p>
                <p class="form_label"><label for="tips">今日の幸せな出来事&#040;Today&#39;s&nbsp;Lucky&nbsp;Tips&#041;</label></p>
                <p class="form_tips"><textarea id="tips" name="tips" row="8" cols="13" wrap="soft" placeholder="100文字以内"></textarea></p>
                <p><input id=submit type="submit" name="submit" value="シェア(Share)"></p>
            </form>
        </section>
        <section class="contents">
            <h3>幸せな出来事たち</h3>
            <?php
            if(is_array($data)){
                foreach ($data as $disp_array) {
                    print '<div class="content_wrapper">';
                        print '<p class="tips">' . $disp_array[2] . '</p>';
                        print '<div class="date_name_wrapper">';
                            print '<p class="name">' . $disp_array[1] . '</p>';
                            print '<p class="input_date">' . $disp_array[0] . '</p>';
                        print '</div>';
                    print '</div>';
                }
            }
            ?>
        </section>
    </article>
</body>
</html>