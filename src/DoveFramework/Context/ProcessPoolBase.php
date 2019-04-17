<?php
namespace DoveFramework\Context;

/**
 * 进程池基类。
 *
 * @package       DoveFramework\Context
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
abstract class ProcessPoolBase extends ProcessBase {
    /**
     * 启动进程。
     */
    final function run(): void {
        $pool = new \Swoole\Process\Pool($this->getProcessNum());

        $pool->on('workerStart', [$this, 'onStart']);
        $pool->on("workerStop", [$this, 'onStop']);

        $pool->start();
    }

    /**
     * 获取进程数量。
     *
     * @return int
     */
    abstract function getProcessNum(): int;

    /**
     * 工作进程启动事件回调。
     *
     * @param \Swoole\Process\Pool $pool
     * @param int                  $worker_id
     */
    abstract function onStart(\Swoole\Process\Pool $pool, int $worker_id): void;

    /**
     * 工作进程退出事件回调。
     *
     * @param \Swoole\Process\Pool $pool
     * @param int                  $worker_id
     */
    abstract function onStop(\Swoole\Process\Pool $pool, int $worker_id): void;
}