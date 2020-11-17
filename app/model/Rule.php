<?php

declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Rule extends Model
{
    // 角色-规则多对多关系
    public function roles()
    {
        return $this->belongsToMany('Role', 'role_rule');
    }
}
