<?php
namespace DoveFramework\Entity;

/**
 * Web Socket 服务对象。
 *
 * @package       DoveFramework\Entity
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
final class WebSocketPort extends ServerPort {
    /**
     * 获取服务实例类名。
     *
     * @return string
     */
    function getServerInstanceName(): string {
        return \Swoole\WebSocket\Server::class;
    }

    /**
     * 获取服务器事件列表。
     *
     * @return array
     */
    function getServerEvents(): array {
        return ['handshake' => 'onHandShake', 'open' => 'onOpen', 'message' => 'onMessage'];
    }
}