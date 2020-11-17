<?php

declare(strict_types=1);

namespace app\middleware;

class checkManagerToken
{
    /**
     * 处理请求
     *
     * @param \think\Request $request
     * @param \Closure       $next
     * @return Response
     */
    public function handle($request, \Closure $next)
    {
        // 获取token
        $token = $request->header('token');
        // token不存在
        if (!$token) ApiException('header未携带token');
        // 没有登录
        $user = cms_getUser([
            'token' => $token,
        ]);
        if (!$user) ApiException('非法token,请先登录');
        $request->UserModel = \app\model\Manager::find($user['id']);
        // 当前用户被禁用
        if (!$request->UserModel || $request->UserModel->status === 0) {
            ApiException('当前用户被禁用');
        }
        // 验证当前用户的权限(超级管理员无需验证)
        if (!$request->UserModel->super) {
        }
        return  $next($request);
    }
}
