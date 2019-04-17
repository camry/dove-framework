<?php
namespace DoveFramework\Interfaces;

/**
 * IPeriodic 定时器宿主对象接口。
 *
 * @package       DoveFramework\Interfaces
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
interface IPeriodicHost {
    /**
     * 添加 IPeriodic 实例对象。
     *
     * @param IPeriodic $periodic
     */
    function addPeriodic(IPeriodic $periodic): void;
}