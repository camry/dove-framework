<?php
namespace DoveFramework\Interfaces;

/**
 * EvTimer 定时器接口。
 *
 * @package       DYGame\Interfaces
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
interface ITimer {
    /**
     * 是否零秒对齐？
     *
     * @return bool
     */
    function isZeroSecAlign(): bool;

    /**
     * 获取定时器轮循间隔秒数。
     *
     * @return int
     */
    function getRepeat(): int;

    /**
     * 定时处理器回调。
     *
     * @param \EvWatcher $w
     */
    function handle(\EvWatcher $w): void;
}