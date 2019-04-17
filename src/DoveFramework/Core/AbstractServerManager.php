<?php
namespace DoveFramework\Core;

/**
 * Swoole Server 管理器。
 *
 * @package       DYGame\Core
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
abstract class AbstractServerManager {
    /**
     * 获取 Server 对象列表。(注: 返回的第一个服务对象必须是主服务对象!)
     *
     * @return \DoveFramework\Entity\ServerPort[]
     */
    abstract function getServerPorts(): array;
}