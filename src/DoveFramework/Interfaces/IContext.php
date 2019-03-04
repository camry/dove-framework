<?php
namespace DoveFramework\Interfaces;

/**
 * 自定义上下文管理器接口。(注: 适用于应用层初始化全局对象的场景.)
 *
 * @package       DoveFramework\Interfaces
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
interface IContext extends IDisposable {
    /**
     * 上下文管理器初始化。
     */
    function initialize(): void;
}