<?php
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