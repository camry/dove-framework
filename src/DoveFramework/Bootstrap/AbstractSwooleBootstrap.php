<?php
namespace DoveFramework\Bootstrap;

use DoveFramework\Context\SwooleProcess;

/**
 * 基于 Swoole 服务启动器抽象基类。
 *
 * @package       DoveFramework\Bootstrap
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
abstract class AbstractSwooleBootstrap extends ProcessBootstrap {
    /**
     * Swoole 自定义进程列表。
     *
     * @var SwooleProcess[]
     */
    protected $processes = [];

    /**
     * Swoole 用户进程管理接口名。
     *
     * @var string
     */
    protected $process_manager = NULL;

    /**
     * 添加 Swoole 自定义进程。
     *
     * @param SwooleProcess ...$processes
     * @return HTTPBootstrap
     */
    function addProcess(SwooleProcess ...$processes): AbstractSwooleBootstrap {
        array_push($this->processes, ...$processes);

        return $this;
    }

    /**
     * 设置进程管理器。
     *
     * @param string $process_manager 进程管理器类名。(注: 必须是 ISwooleProcessManager 接口类)
     * @return AbstractSwooleBootstrap
     */
    function setProcessManager(string $process_manager): AbstractSwooleBootstrap {
        $this->process_manager = $process_manager;

        return $this;
    }
}