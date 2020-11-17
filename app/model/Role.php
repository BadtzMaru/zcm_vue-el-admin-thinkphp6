<?php

declare(strict_types=1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class Role extends Model
{
    // 角色-管理员 一对多
    public function managers()
    {
        return $this->hasMany('Manager');
    }

    // 当前角色的所有权限
    public function rules()
    {
        return $this->belongsToMany('Rule', 'role_rule');
    }

    public function delRules($ruleId)
    {
        return $this->rules()->detach($ruleId);
    }

    public static function onBeforeDelete($role)
    {
        // 清除当前角色的所有权限
        $ruleIds = $role->rules->map(function ($v) {
            return $v->id;
        })->toArray();
        if (count($ruleIds)) $role->delRules($ruleIds);
    }

    // 给角色授予权限
    public function setRules($ruleId)
    {
        // 获取当前角色的所有权限Id
        $Ids = \app\model\RoleRule::where('role_id', $this->id)->column('rule_id');
        // 需要添加的
        $addIds = array_diff($ruleId, $Ids);
        // 需要删除的
        $delIds = array_diff($Ids, $ruleId);

        if (count($addIds) > 0) {
            $RoleRule = new \app\model\RoleRule();
            $addData = [];
            foreach ($addIds as $value) {
                $addData[] = [
                    'rule_id' => $value,
                    'role_id' => $this->id
                ];
            }
            $RoleRule->saveAll($addData);
        }

        if (count($delIds) > 0) {
            \app\model\RoleRule::where([
                ['rule_id', 'in', $delIds],
                ['role_id', '=', $this->id]
            ])->delete();
        }
        return true;
    }
}
