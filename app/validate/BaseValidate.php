<?php

declare(strict_types=1);

namespace app\validate;

use think\Validate;

class BaseValidate extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名' =>  ['规则1','规则2'...]
     *
     * @var array
     */
    protected $rule = [];

    /**
     * 定义错误信息
     * 格式：'字段名.规则名' =>  '错误信息'
     *
     * @var array
     */
    protected $message = [];

    // 自定义验证规则 | 验证记录是否存在
    protected function isExist($value, $rule = '', $data = '', $field = '', $title = '记录')
    {
        if (!$value) return true;
        $model = '\app\model\\' . $rule;
        $m = $model::find($value);
        if (!$m) return '该' . $title . '不存在';
        request()->Model = $m;
        return true;
    }
}
