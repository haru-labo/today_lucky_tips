<?php
require_once 'include\model\validation.php';
require_once 'include\model\encode.php';
require_once 'include\model\DbInsert.php';
require_once 'include\model\DbSelect.php';

//データベース格納用
$name = '';
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
    if ($arr_valid['result']) {
        $name = $in_name;
        //出来事は改行を削除
        $content = del_enter($in_content);
        //INSERTSQL実行
        insert_content($name, $content);
        //入力ボックスをクリア
        $in_name = '';
        $in_content = '';
    }
}

//読み込みして表示
$data = select_all_contents();

include_once 'include\view\happiness_list.php';