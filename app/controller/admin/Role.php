<?php

declare(strict_types=1);

namespace app\controller\admin;

use think\Request;
use app\BaseController;

class Role extends BaseController
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $param = request()->param();
        $limit = intval(getValByKey('limit', $param, 10));
        $totalCount = $this->M->count();
        $list = $this->M->page($param['page'], $limit)
            ->with(['rules' => function ($q) {
                $q->alias('a')->field('a.id');
            }])
            ->order(['id' => 'desc'])
            ->select();
        return showSuccess([
            'list' => $list,
            'totalCount' => $totalCount
        ]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $res = $this->M->save($request->only([
            'status',
            'desc',
            'name',
        ]));
        return showSuccess($res);
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        $param = $request->only([
            'id',
            'status',
            'desc',
            'name',
        ]);
        $res = $request->Model->save($param);
        return showSuccess($res);
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        if ($this->request->Model->managers->count() > 0) {
            ApiException('请先修改该角色对应的管理员');
        }

        $role = $this->request->Model;
        return showSuccess($role->delete());
    }

    // 修改角色状态
    public function updateStatus()
    {
        $role = $this->request->Model;
        $role->status = $this->request->param('status');
        return showSuccess($role->save());
    }

    // 给角色授予权限
    public function setRules()
    {
        $param = request()->param();
        $rules = getValByKey('rule_ids', $param, []);
        return showSuccess(request()->Model->setRules($rules));
    }
}
