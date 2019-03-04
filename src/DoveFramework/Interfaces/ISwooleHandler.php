<?php
namespace DoveFramework\Interfaces;

/**
 * Swoole 事件处理器接口。
 *
 * @package       DoveFramework\Interfaces
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
interface ISwooleHandler {
    /**
     * Server 主线程启动回调事件。
     *
     * @param \Swoole\Server $server
     */
    function onStart(\Swoole\Server $server): void;

    /**
     * Server 关闭回调事件。
     *
     * @param \Swoole\Server $server
     */
    function onShutdown(\Swoole\Server $server): void;

    /**
     * 工作进程启动事件。
     *
     * @param \Swoole\Server $server
     * @param int            $worker_id
     */
    function onWorkerStart(\Swoole\Server $server, int $worker_id): void;

    /**
     * 工作进程关闭事件。
     *
     * @param \Swoole\Server $server
     * @param int            $worker_id
     */
    function onWorkerStop(\Swoole\Server $server, int $worker_id): void;

    /**
     * 工作进程退出事件。（注：仅在开启 reload_async 特性后有效。）
     *
     * @param \Swoole\Server $server
     * @param int            $worker_id
     */
    function onWorkerExit(\Swoole\Server $server, int $worker_id): void;

    /**
     * 客户端连接事件。
     *
     * @param \Swoole\Server $server
     * @param int            $fd
     * @param int            $reactor_id
     */
    function onConnect(\Swoole\Server $server, int $fd, int $reactor_id): void;

    /**
     * 收到客户端消息触发此事件。
     *
     * @param \Swoole\Server $server
     * @param int            $fd
     * @param int            $reactor_id
     * @param string         $data
     */
    function onReceive(\Swoole\Server $server, int $fd, int $reactor_id, string $data): void;

    /**
     * 收到UDP数据包时触发此事件。
     *
     * @param \Swoole\Server $server
     * @param string         $data
     * @param array          $client_info
     */
    function onPacket(\Swoole\Server $server, string $data, array $client_info): void;

    /**
     * 客户端断开事件。
     *
     * @param \Swoole\Server $server
     * @param int            $fd
     * @param int            $reactor_id
     */
    function onClose(\Swoole\Server $server, int $fd, int $reactor_id): void;

    /**
     * 当缓存区达到最高水位时触发此事件。
     *
     * @param \Swoole\Server $server
     * @param int            $fd
     */
    function onBufferFull(\Swoole\Server $server, int $fd): void;

    /**
     * 当缓存区低于最低水位线时触发此事件。
     *
     * @param \Swoole\Server $server
     * @param int            $fd
     */
    function onBufferEmpty(\Swoole\Server $server, int $fd): void;

    /**
     * 任务进程收到消息回调此事件。
     *
     * @param \Swoole\Server $server
     * @param int            $task_id
     * @param int            $src_worker_id
     * @param string         $data
     */
    function onTask(\Swoole\Server $server, int $task_id, int $src_worker_id, string $data): void;

    /**
     * 任务执行完成回调此事件。
     *
     * @param \Swoole\Server $server
     * @param int            $task_id
     * @param string         $data
     */
    function onFinish(\Swoole\Server $server, int $task_id, string $data): void;

    /**
     * 工作进程收到管道消息时触发此事件。
     *
     * @param \Swoole\Server $server
     * @param int            $src_worker_id
     * @param string         $data
     */
    function onPipeMessage(\Swoole\Server $server, int $src_worker_id, string $data): void;

    /**
     * 当 Worker/Task 进程发生异常后会在 Manager 进程内回调此函数。
     *
     * @param \Swoole\Server $server
     * @param int            $worker_id
     * @param int            $worker_pid
     * @param int            $exit_code
     * @param int            $signal
     */
    function onWorkerError(\Swoole\Server $server, int $worker_id, int $worker_pid, int $exit_code, int $signal): void;

    /**
     * 管理进程启动事件。
     *
     * @param \Swoole\Server $server
     */
    function onManagerStart(\Swoole\Server $server): void;

    /**
     * 管理进程关闭事件。
     *
     * @param \Swoole\Server $server
     */
    function onManagerStop(\Swoole\Server $server): void;
}