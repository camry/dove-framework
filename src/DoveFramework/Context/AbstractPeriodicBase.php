<?php
namespace DoveFramework\Context;

use DoveFramework\Interfaces\IPeriodic;
use DoveFramework\Interfaces\IPeriodicHost;
use DoveFramework\Interfaces\IProcess;

/**
 * 抽象精准时刻定时器基类。
 *
 * @package       DYGame\Context
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
abstract class AbstractPeriodicBase extends ProcessBase implements IProcess, IPeriodicHost {
    /**
     * 定时器对象实例。
     *
     * @var \EvTimer
     */
    private $timer = NULL;

    /**
     * IPeriodic 实例列表。
     *
     * @var IPeriodic[]
     */
    private $periodics = [];

    /**
     * 启动进程。
     */
    final function run(): void {
        $this->configure();

        $this->timer = new \EvTimer(0, 1, [$this, 'doElapsedHandle']);

        \Ev::run();
    }

    /**
     * 定时器回调函数。
     *
     * @param \EvWatcher $watcher
     */
    final function doElapsedHandle(\EvWatcher $watcher) {
        $cts = time();

        foreach ($this->periodics as $key => $value) {
            if ($cts >= $value->getExecuteTime()) {
                try {
                    $value->handle($this);
                } catch (\Exception $ex) {
                    $this->bootstrap->logger->error($ex->getMessage(), [], $ex);
                }

                unset($this->periodics[$key]);
            }
        }
    }

    /**
     * 添加 IPeriodic 实例对象。
     *
     * @param IPeriodic $periodic
     */
    final function addPeriodic(IPeriodic $periodic): void {
        // 若已经过期, 则直接剔除 ...
        if ($periodic->getExecuteTime() <= time())
            return;

        $this->periodics[] = $periodic;
    }

    /**
     * 配置定时器实例。
     */
    abstract function configure(): void;
}