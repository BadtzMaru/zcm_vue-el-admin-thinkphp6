<?php

declare(strict_types=1);

namespace app\validate\admin;

use think\Validate;

class Manager extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'page' => 'require|integer|>:0',
        'username' => 'require|min:5|max:20',
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
        'save' => ['username', 'password', 'avatar', 'role_id', 'status'],
    ];
}
