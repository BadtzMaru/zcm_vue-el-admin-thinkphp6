<?php
// 应用公共文件

// 成功返回
function showSuccess($data = '', $msg = 'ok', $code = 200) {
    return json([
        'msg' => $msg,
        'data' => $data,
    ], $code);
}
// 失败返回
function showError($msg = 'fail', $code = 400){
    return json([
        'msg' => $msg,
    ], $code);
}