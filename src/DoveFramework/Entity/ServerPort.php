<?php
namespace DoveFramework\Entity;

use DoveFramework\Core\ObjectSerializable;

/**
 * Server 端口对象。
 *
 * @package       DYGame\Entity
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
abstract class ServerPort extends ObjectSerializable {
    /**
     * 监听主机地址。
     *
     * @var string
     */
    protected $host = '0.0.0.0';

    /**
     * 端口。
     *
     * @var int
     */
    protected $port = 0;

    /**
     * Socket 类型。
     *
     * @var int
     */
    protected $type = 0;

    /**
     * 构造函数。
     *
     * @param string $host 主机地址。(如: 0.0.0.0)
     * @param int    $port 端口。
     * @param int    $type 指定 Socket 类型。
     */
    function __construct(string $host, int $port, int $type = SWOOLE_SOCK_TCP | SWOOLE_SOCK_ASYNC) {
        $this->host = $host;
        $this->port = $port;
        $this->type = $type;
    }

    /**
     * 获取 Socket 监听地址。
     *
     * @return string
     */
    function getHost(): string {
        return $this->host;
    }

    /**
     * 获取 Socket 监听端口。
     *
     * @return int
     */
    function getPort(): int {
        return $this->port;
    }

    /**
     * 获取 Socket 类型。
     *
     * @return int
     */
    function getType(): int {
        return $this->type;
    }

    /**
     * 获取基本事件列表。
     *
     * @return array
     */
    function getBaseEvents(): array {
        return ['start' => 'onStart', 'shutdown' => 'onShutdown', 'managerstart' => 'onManagerStart', 'managerstop' => 'onManagerStop', 'workerstart' => 'onWorkerStart', 'workerstop' => 'onWorkerStop', 'workererror' => 'onWorkerError', 'connect' => 'onConnect', 'close' => 'onClose', 'task' => 'onTask', 'pipemessage' => 'onPipeMessage'];
    }

    /**
     * 获取服务实例类名。
     *
     * @return string
     */
    abstract function getServerInstanceName(): string;

    /**
     * 获取服务器事件列表。
     *
     * @return array
     */
    abstract function getServerEvents(): array;
}