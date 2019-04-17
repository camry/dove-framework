<?php
namespace DoveFramework\Core;

use DoveFramework\Context\AbstractBase;

/**
 * Swoole 服务启动参数注入。
 *
 * @package       DoveFramework\Core
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
abstract class AbstractServerInjection extends AbstractBase {
    /**
     * 注入参数。
     *
     * @return array
     */
    abstract function inject(): array;
}