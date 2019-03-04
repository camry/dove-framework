<?php
namespace DoveFramework\Interfaces;

/**
 * Interface IProcess
 *
 * @package       DoveFramework\Interfaces
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
interface IProcess extends IController {
    /**
     * 启动进程。
     */
    function run(): void;

    /**
     * 是否延迟初始化上下文实例？
     *
     * @return bool
     */
    function isInitContextDefered(): bool;
}