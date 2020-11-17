<?php

declare(strict_types=1);

namespace app\validate\admin;

use app\validate\BaseValidate;

class Manager extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id|管理员ID' => 'require|integer|>:0|isExist:manager',
        'page' => 'require|integer|>:0',
        'username|管理员用户名' => 'require|min:5|max:20',
        'password' => 'require|min:5|max:20',
        'avatar' => 'url',
        'role_id' => 'require|integer|>:0',
        'status' => 'require|integer|in:0,1',
    ];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [];

    protected $scene = [
        'index' => ['page'],
        'delete' => ['id'],
        'updateStatus' => ['id', 'status'],
    ];

    // 管理员登陆
    public function sceneLogin()
    {
        return $this->only(['username', 'password'])->append('password', 'checklogin');
    }

    // 验证登陆
    public function checklogin($value, $rule = '', $data = '', $field = '')
    {
        // 验证帐号
        $M = \app\model\Manager::where('username', $data['username'])->find();
        if (!$M) return '用户名不存在';
        // 验证密码
        if (!password_verify($data['password'], $M->password)) return '密码错误';
        // 将当前用户实例挂到request中
        request()->UserModel = $M;
        return true;
    }

    // 创建管理员验证场景
    public function sceneSave()
    {
        return $this->only(['username', 'password', 'avatar', 'role_id', 'status'])->append('username', 'unique:manager');
    }

    // 更新管理员验证场景
    public function sceneUpdate()
    {
        $id = request()->param('id');
        return $this->only(['id', 'username', 'password', 'avatar', 'role_id', 'status'])->append('username', 'unique:manager,username,' . $id);
    }
}
