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
    protected $excludeValidateCheck  = ['index'];

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return showSuccess('hello');
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
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
