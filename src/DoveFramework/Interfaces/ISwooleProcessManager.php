<?php
namespace DoveFramework\Interfaces;

use DoveFramework\Bootstrap\AbstractBootstrap;

/**
 * Swoole 用户进程管理器接口。
 *
 * @package       DoveFramework\Interfaces
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
interface ISwooleProcessManager {
    /**
     * 配置自定义进程。
     *
     * @param AbstractBootstrap $bootstrap 引用 AbstractBootstrap 实例。
     * @param \Swoole\Server    $server    引用 Server 实例。
     */
    static function handle(AbstractBootstrap $bootstrap, \Swoole\Server $server): void;
}