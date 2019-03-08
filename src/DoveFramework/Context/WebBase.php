<?php
namespace DoveFramework\Context;

use DoveFramework\Bootstrap\WebBootstrap;

/**
 * Web 抽象基类。
 *
 * @package       DoveFramework\Context
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
abstract class WebBase {
    /**
     * WebBootstrap 实例。
     *
     * @var WebBootstrap
     */
    protected $bootstrap;

    /**
     * 构造函数。
     *
     * @param WebBootstrap $bootstrap 指定 WebBootstrap 实例引用。
     */
    function __construct(WebBootstrap $bootstrap) {
        $this->bootstrap = $bootstrap;
    }

    /**
     * 析构函数。
     */
    function __destruct() {
        unset($this->bootstrap);
    }
}