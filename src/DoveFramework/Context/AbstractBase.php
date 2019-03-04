<?php
namespace DoveFramework\Context;

use DoveFramework\Bootstrap\AbstractBootstrap;

/**
 * 抽象 AbstractBase 类。
 *
 * @package       DoveFramework\Context
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
abstract class AbstractBase {
    /**
     * AbstractBootstrap 实例。
     *
     * @var AbstractBootstrap
     */
    protected $bootstrap = NULL;

    /**
     * 构造函数。
     *
     * @param AbstractBootstrap $bootstrap 指定 AbstractBootstrap 实例引用。
     */
    function __construct(AbstractBootstrap $bootstrap) {
        $this->bootstrap = $bootstrap;
    }

    /**
     * 析构函数。
     */
    function __destruct() {
        unset($this->bootstrap);
    }
}