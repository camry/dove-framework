<?php
namespace DoveFramework\Context;

use DoveFramework\Bootstrap\SwooleBootstrap;

/**
 * 抽象 SwooleBase 类。
 *
 * @package       DoveFramework\Context
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
abstract class SwooleBase {
    /**
     * SwooleBootstrap 实例。
     *
     * @var SwooleBootstrap
     */
    protected $bootstrap = NULL;

    /**
     * 构造函数。
     *
     * @param SwooleBootstrap $bootstrap 指定 SwooleBootstrap 实例引用。
     */
    function __construct(SwooleBootstrap $bootstrap) {
        $this->bootstrap = $bootstrap;
    }

    /**
     * 析构函数。
     */
    function __destruct() {
        unset($this->bootstrap);
    }
}