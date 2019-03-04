<?php
namespace DoveFramework\Interfaces;

/**
 * IDisposable 接口定义。
 *
 * @package       DoveFramework\Interfaces
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
interface IDisposable {
    /**
     * 释放资源。
     */
    function dispose(): void;
}