<?php

use think\facade\Route;

Route::group('admin', function () {
    // 创建管理员
    Route::post('manager', 'admin.Manager/save');
});
