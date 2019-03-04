<?php
namespace DoveFramework\Context;

use DoveFramework\Bootstrap\HTTPBootstrap;

/**
 *  抽象 AbstractSwooleHTTPBase 基类。
 *
 * @package       DoveFramework\Context
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
abstract class AbstractSwooleHTTPBase {
    /**
     * HTTPBootstrap 实例。
     *
     * @var HTTPBootstrap
     */
    protected $bootstrap = NULL;

    /**
     * 构造函数。
     *
     * @param HTTPBootstrap $bootstrap 指定 HTTPBootstrap 实例。
     */
    function __construct(HTTPBootstrap $bootstrap) {
        $this->bootstrap = $bootstrap;
    }

    /**
     * 析构函数。
     */
    function __destruct() {
        unset($this->bootstrap);
    }
}