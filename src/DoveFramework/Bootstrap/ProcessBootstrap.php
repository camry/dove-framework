<?php
namespace DoveFramework\Bootstrap;

use DoveFramework\Exceptions\TypeException;
use DoveFramework\Helper\Util;
use DoveFramework\Interfaces\IContext;
use DoveFramework\Interfaces\IProcess;

/**
 * 命令行程序启动器。
 *
 * @package       DoveFramework\Bootstrap
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
class ProcessBootstrap extends AbstractBootstrap {
    /**
     * 初始化完成调用方法。
     */
    function initializeComplete(): void {
        // TODO: Implement initializeComplete() method.
    }

    /**
     * 解析请求/执行参数。
     */
    function parse(): void {
    }

    /**
     * 验证请求/参数合法性。
     */
    function validate(): void {
        // TODO: Implement validate() method.
    }

    /**
     * 设置自定义上下文管理器。(注: 适用于应用层初始化全局对象的业务场景!)
     *
     * @param IContext $context 上下文管理器实例。
     * @param bool     $defered 是否延迟初始化上下文对象？
     * @return AbstractBootstrap
     */
    function setContext(IContext $context, bool $defered = false): AbstractBootstrap {
        return parent::setContext($context, true);
    }

    /**
     * 执行控制器行为方法。
     *
     * @throws TypeException
     */
    function execute(): void {
        $cls_n = Util::ns($this->getControllerNs(), $this->argv[1]);
        $cls_o = new $cls_n($this);

        if ($cls_o instanceof IProcess) {
            // 检查上下文对象延迟初始化 ...
            if (!$cls_o->isInitContextDefered()) {
                $this->getContext()->initialize();
            }

            $cls_o->initialize();
            $cls_o->run();
            $cls_o->after();
            $cls_o->dispose();
        } else {
            throw new TypeException($cls_n . ' 必须实现 IProcess 接口。');
        }
    }
}