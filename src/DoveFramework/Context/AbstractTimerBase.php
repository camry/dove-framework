<?php
namespace DoveFramework\Context;

use DoveFramework\Interfaces\IProcess;
use DoveFramework\Interfaces\ITimer;

/**
 * 抽象秒级定时器基类。
 *
 * @package       DoveFramework\Context
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
abstract class AbstractTimerBase extends ProcessBase implements IProcess {
    /**
     * 定时器实例列表。
     *
     * @var \EvWatcher[]
     */
    protected $timers = [];

    /**
     * 启动进程。
     */
    final function run(): void {
        $this->configution();

        \Ev::run();
    }

    /**
     * 添加定时器。
     *
     * @param ITimer $timer        定时器实例。
     * @param bool   $round_enable 指示起始时间是否取整？(默认值: True)
     * @return AbstractTimerBase
     */
    final function addTimer(ITimer $timer, bool $round_enable = true): AbstractTimerBase {
        $this->timers[] = new \EvTimer($round_enable ? 60 - (time() % 60) : 0, $timer->getRepeat(), [$timer, 'handle']);

        return $this;
    }

    /**
     * 配置 Ev 定时器实例。
     */
    abstract function configution(): void;
}