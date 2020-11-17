<?php

declare(strict_types=1);

namespace app;

use think\App;
use think\exception\ValidateException;
use think\Validate;

/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 自动实例化模型
    protected $M = null;
    // 记录当前控制器相关信息
    protected $cInfo = [];
    // 是否自动实例化模型
    protected $autoModel = true;
    // 自定义模型路径
    protected $modelPath = null;
    // 是否开启自动验证
    protected $autoValidate = true;
    // 自定义验证场景
    protected $autoValidateScenes = [];
    // 不需要自动验证的方法
    protected $excludeValidateCheck = [];

    // 初始化
    protected function initialize()
    {

        $this->cInfo = [
            'name' => class_basename($this),
            'path' => str_replace('.', '\\', $this->request->controller()),
            'action' => $this->request->action(),
        ];
        // 自动验证参数
        $this->autoValidateCheck();
        // 自动实例化模型
        $this->getCurrentModel();
    }

    // 自动验证参数
    protected function autoValidateCheck()
    {
        if ($this->autoValidate && !in_array($this->cInfo['action'], $this->excludeValidateCheck)) {
            $V = app('app\validate\\' . $this->cInfo['path']);
            $scene = array_key_exists($this->cInfo['action'], $this->autoValidateScenes) ? $this->autoValidateScenes[$this->cInfo['action']] : $this->cInfo['action'];
            if (!$V->scene($scene)->check($this->request->param())) {
                ApiException($V->getError());
            }
        }
    }

    // 自动实例化模型
    protected function getCurrentModel()
    {
        if ($this->autoModel) {
            $modelName = $this->modelPath ? str_replace('/', '\\', $this->modelPath)  : $this->cInfo['name'];
            $this->M = app('app\model\\' . $modelName);
        }
    }

    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }
}
