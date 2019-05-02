<?php
require_once 'include/model/validation.php';
require_once 'include/model/encode.php';

$log_file = 'log/log.txt';
$name_date = '';
$content = '';

//読み込んだデータ格納用配列
$data = [];

//入力ボックス表示用
$in_name = '';
$in_content = '';

//エラー用
$arr_valid = ['msg' => [], 'result' => true];
$valid_msg = [];

//POST時の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //入力ボックス表示用にPOSTされたデータを代入
    $in_name = e($_POST['name']);
    $in_content = e($_POST['content']);

    //バリデーション
    $arr_valid = validation($_POST);
    if ($arr_valid['result'] === TRUE) {
        $name_date = date('Y/m/d H:i:s') . PHP_EOL . $_POST['name'] . PHP_EOL;
        //出来事は改行を削除
        $tips = del_enter($_POST['content']) . PHP_EOL;

        //入力ボックスをクリア
        $in_name = '';
        $in_content = '';
    }

    //ログへの書き込み
    if (($fp = fopen($log_file, 'a')) !== FALSE) {
        if (fwrite($fp, $name_date) === FALSE || fwrite($fp, $content) === FALSE) {
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

include_once 'include\view\happiness_list.php';
?>