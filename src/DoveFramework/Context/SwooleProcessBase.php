<?php
namespace DoveFramework\Context;

use DoveFramework\Bootstrap\SwooleBootstrap;
use DoveFramework\Interfaces\IContext;

/**
 * 基于 Swoole\Server 管理的自定义工作进程基类。
 *
 * @package       DoveFramework\Context
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
abstract class SwooleProcessBase extends \Swoole\Process {
    /**
     * SwooleBootstrap 实例引用。
     *
     * @var SwooleBootstrap
     */
    protected $bootstrap = NULL;

    /**
     * 上下文管理器实例引用。
     *
     * @var IContext
     */
    protected $ctx = NULL;

    /**
     * 进程索引序号。
     *
     * @var int
     */
    protected $index = 0;

    /**
     * 构造函数。
     *
     * @param SwooleBootstrap $bootstrap 指定 SwooleBootstrap 实例引用。
     */
    function __construct(SwooleBootstrap $bootstrap) {
        $this->bootstrap = $bootstrap;
        $this->ctx       = $bootstrap->getContext();

        parent::__construct([$this, 'handle'], false, true);
    }

    /**
     * 析构函数。
     */
    function __destruct() {
        unset($this->ctx, $this->bootstrap);
    }

    /**
     * 获取进程索引序号。
     *
     * @return int
     */
    function getIndex(): int {
        return $this->index;
    }

    /**
     * 设置进程索引序号。
     *
     * @param int $index
     * @return SwooleProcessBase
     */
    function setIndex(int $index): SwooleProcessBase {
        $this->index = $index;

        return $this;
    }

    /**
     * Swoole 进程启动回调。
     *
     * @param SwooleProcessBase $process
     */
    function handle($process) {
        // 标记应用系统已启动完毕 ...
        $this->bootstrap->started();

        $this->run($process);
    }

    /**
     * 启动进程。
     *
     * @param SwooleProcessBase $process
     */
    abstract function run($process): void;
}