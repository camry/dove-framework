<?php
namespace DoveFramework\Context;

use DoveFramework\Bootstrap\ProcessBootstrap;

/**
 * 抽象 AbstractProcessBase 类。
 *
 * @package       DoveFramework\Context
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
abstract class AbstractProcessBase {
    /**
     * ProcessBootstrap 实例。
     *
     * @var ProcessBootstrap
     */
    protected $bootstrap = NULL;

    /**
     * 构造函数。
     *
     * @param ProcessBootstrap $bootstrap 指定 ProcessBootstrap 实例引用。
     */
    function __construct(ProcessBootstrap $bootstrap) {
        $this->bootstrap = $bootstrap;
    }

    /**
     * 析构函数。
     */
    function __destruct() {
        unset($this->bootstrap);
    }
}