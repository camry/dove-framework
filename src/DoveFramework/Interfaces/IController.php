<?php
namespace DoveFramework\Interfaces;

/**
 * 控制器接口。
 *
 * @package       DoveFramework\Interfaces
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
interface IController extends IDisposable {
    /**
     * 初始化事件。
     */
    function initialize(): void;

    /**
     * 控制器执行完成时调用。
     */
    function after(): void;

    /**
     * 自定义异常处理。
     *
     * @param \Throwable $ex
     */
    function exception(\Throwable $ex): void;
}