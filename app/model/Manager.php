<?php

declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Manager extends Model
{
    // 修改器
    public function setPasswordAttr($value, $data)
    {
        return password_hash($value, PASSWORD_DEFAULT);
    }
}
