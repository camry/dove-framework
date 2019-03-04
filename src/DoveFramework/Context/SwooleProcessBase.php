<?php
namespace DoveFramework\Context;

use DoveFramework\Bootstrap\AbstractBootstrap;
use DoveFramework\Bootstrap\HTTPBootstrap;
use DoveFramework\Bootstrap\SwooleBootstrap;
use DoveFramework\Bootstrap\WebSocketBootstrap;
use DoveFramework\Exceptions\TypeException;
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
     * 抽象启动器。
     *
     * @var AbstractBootstrap
     */
    protected $bootstrap;

    /**
     * 上下文管理器。
     *
     * @var IContext
     */
    protected $ctx;

    /**
     * 进程索引序号。
     *
     * @var int
     */
    protected $index = 0;

    /**
     * 构造函数。
     *
     * @param AbstractBootstrap $bootstrap
     * @throws TypeException
     */
    public function __construct(AbstractBootstrap $bootstrap) {
        if (!($bootstrap instanceof SwooleBootstrap || $bootstrap instanceof HTTPBootstrap || $bootstrap instanceof WebSocketBootstrap))
            throw new TypeException('Bootstrap 对象必须是 SwooleBootstrap/WebSocketBootstrap/HTTPBootstrap 类型。');

        $this->bootstrap = $bootstrap;
        $this->ctx       = $bootstrap->getContext();

        parent::__construct([$this, 'handle'], false, true);
    }

    /**
     * 析构函数。
     */
    public function __destruct() {
        unset($this->bootstrap, $this->ctx);
    }

    /**
     * 获取进程索引序号。
     *
     * @return int
     */
    public function getIndex(): int {
        return $this->index;
    }

    /**
     * 设置进程索引序号。
     *
     * @param int $index
     */
    public function setIndex(int $index): void {
        $this->index = $index;
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