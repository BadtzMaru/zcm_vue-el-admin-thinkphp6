<?php

use think\facade\Route;

// 需要验证 (管理员)
Route::group('admin', function () {
    // 创建管理员
    Route::post('manager', 'admin.Manager/save');
    // 管理员列表
    Route::get('manager/:page', 'admin.Manager/index');
    // 更新管理员
    Route::post('manager/:id', 'admin.Manager/update');
    // 删除管理员
    Route::delete('manager/:id/delete', 'admin.Manager/delete');
    // 修改管理员状态
    Route::post('manager/:id/update_status', 'admin.Manager/updateStatus');
    // 角色列表
    Route::post('role/:page', 'admin.Role/index');
    // 添加角色
    Route::post('role', 'admin.Role/save');
    // 修改角色
    Route::post('role/:id', 'admin.Role/update');
    // 修改角色状态
    Route::post('role/:id/update_status', 'admin.Role/updateStatus');
    // 删除角色
    Route::post('role/:id/delete', 'admin.Role/delete');
})->middleware(\app\middleware\checkManagerToken::class);

// 不需要验证 (游客)
Route::group('admin', function () {
    // 管理员登录
    Route::post('login', 'admin.Manager/login');
    // 退出登录
    Route::post('logout', 'admin.Manager/logout');
});
