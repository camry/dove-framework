<?php
namespace DoveFramework\Entity;

/**
 * HTTP 服务对象。
 *
 * @package       DoveFramework\Entity
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
final class HttpServerPort extends ServerPort {
    /**
     * 启用 HTTP 协议。
     *
     * @var bool
     */
    protected $open_http_protocol = false;

    /**
     * 启用 HTTP 2 协议。
     *
     * @var bool
     */
    protected $open_http2_protocol = false;

    /**
     * ServerPort constructor.
     *
     * @param string $host         监听地址。
     * @param int    $port         监听端口。
     * @param int    $type         指定 Socket 类型。
     * @param bool   $enable_http2 是否启用 HTTP 2 协议？
     */
    function __construct(string $host, int $port, int $type = SWOOLE_SOCK_TCP | SWOOLE_SOCK_ASYNC, bool $enable_http2 = false) {
        parent::__construct($host, $port, $type);

        if ($enable_http2)
            $this->open_http2_protocol = true;
        else
            $this->open_http_protocol = true;
    }

    /**
     * 是否 HTTP 1.1 模式？
     *
     * @return bool
     */
    function isOpenHttpProtocol(): bool {
        return $this->open_http_protocol;
    }

    /**
     * 是否 HTTP 2 模式？
     *
     * @return bool
     */
    function isOpenHttp2Protocol(): bool {
        return $this->open_http2_protocol;
    }

    /**
     * 获取服务实例类名。
     *
     * @return string
     */
    function getServerInstanceName(): string {
        return \Swoole\Http\Server::class;
    }

    /**
     * 获取服务器事件列表。
     *
     * @return array
     */
    function getServerEvents(): array {
        return ['request' => 'onRequest'];
    }
}