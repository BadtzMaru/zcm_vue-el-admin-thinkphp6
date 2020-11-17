<?php

declare(strict_types=1);

namespace app\controller\admin;

use think\Request;
use app\BaseController;

class Manager extends BaseController
{
    // 关闭自动实例化模型
    // protected $autoModel = false;
    // protected $modelPath = 'admin/Manager';

    // 自定义验证场景
    // protected $autoValidateScenes = [
    //     '方法名' => '自定义场景'
    // ];

    // 不需要自动验证的方法
    protected $excludeValidateCheck  = ['logout'];

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $param = request()->param();
        $limit = intval(getValByKey('limit', $param, 10));
        $keyword = getValByKey('keyword', $param, '');
        $where = [
            ['username', 'like', '%' . $keyword . '%']
        ];
        $totalCount = $this->M->where($where)->count();
        $list = $this->M->page($param['page'], $limit)
            ->with(['role'])
            ->where($where)
            ->order(['id' => 'desc'])
            ->select()
            ->hidden(['password']);
        $role = \app\model\Role::field(['id', 'name'])->select();
        return showSuccess([
            'list' => $list,
            'totalCount' => $totalCount,
            'role' => $role
        ]);
    }

    /**
     * 创建管理员
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $param = $request->only(['username', 'password', 'avatar', 'role_id', 'status']);
        $res = $this->M->save($param);
        return showSuccess($res);
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
        $param = $request->only(['id', 'username', 'password', 'avatar', 'role_id', 'status']);
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
        $manager = $this->request->Model;
        // 不能删除自己
        if ($this->request->UserModel->id === $manager->id) ApiException('不能删除自己');
        // 不能删除管理员帐号
        if ($manager->super === 1) ApiException('不能删除超级管理员');
        return showSuccess($manager->delete());
    }

    // 登陆
    public function login()
    {
        $user = cms_login([
            'data' => $this->request->UserModel,
        ]);
        return showSuccess($user);
    }

    // 退出
    public function logout()
    {
        $res = cms_logout([
            'token' => $this->request->header('token'),
            'password' => false
        ]);
        return showSuccess($res);
    }

    // 修改状态
    public function updateStatus()
    {
        $manager = $this->request->Model;
        // 不能禁用自己
        if ($this->request->UserModel->id === $manager->id) ApiException('不能禁用自己');
        $manager->status = $this->request->param['status'];
        return showSuccess($manager->save());
    }
}
