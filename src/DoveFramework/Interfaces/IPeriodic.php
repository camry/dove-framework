<?php
namespace DoveFramework\Interfaces;

/**
 * IPeriodic 定时器接口。
 *
 * @package       DoveFramework\Interfaces
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
interface IPeriodic {
    /**
     * 获取 Periodic 执行的时间。(格式: Timestamp)
     *
     * @return int
     */
    function getExecuteTime(): int;

    /**
     * 执行逻辑。
     *
     * @param IPeriodicHost $periodicHost
     */
    function handle(IPeriodicHost $periodicHost): void;
}