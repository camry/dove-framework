<?php
namespace DoveFramework\Context;

use DoveFramework\Interfaces\IProcess;

/**
 * 抽象 ProcessBase 类。
 *
 * @package       DoveFramework\Context
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
abstract class ProcessBase extends AbstractProcessBase implements IProcess {
    /**
     * 初始化事件。
     */
    function initialize(): void { }

    /**
     * 释放资源。
     */
    function dispose(): void {
    }

    /**
     * 控制器执行完成时调用。
     */
    function after(): void { }

    /**
     * 自定义异常处理。
     *
     * @param \Throwable $ex
     */
    function exception(\Throwable $ex): void { }

    /**
     * 启动进程。
     */
    abstract function run(): void;

    /**
     * Echo 输出。(注: 此方法输出无需在末尾添加换行符.)
     *
     * @param mixed $str
     */
    function echo($str) {
        echo $str, PHP_EOL;
    }

    /**
     * 是否延迟初始化上下文实例？
     *
     * @return bool
     */
    function isInitContextDefered(): bool {
        return false;
    }
}