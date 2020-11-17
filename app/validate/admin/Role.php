<?php

declare(strict_types=1);

namespace app\validate\admin;

use app\validate\BaseValidate;

class Role extends BaseValidate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [
        'id' => 'require|integer|>:0|isExist:Role',
        'page' => 'require|integer|>:0',
        'status' => 'require|integer|in:0,1',
        'name' => 'require',
        'rule_ids' => 'array',
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
        'save' => ['status', 'name'],
        'update' => ['id', 'status', 'name'],
        'updateStatus' => ['id', 'status'],
        'delete' => ['id'],
        'setRules' => ['id', 'rule_ids'],
    ];
}
