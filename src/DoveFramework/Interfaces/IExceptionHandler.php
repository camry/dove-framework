<?php
namespace DoveFramework\Interfaces;

/**
 * 自定义异常处理器接口。
 *
 * @package       DoveFramework\Interfaces
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
interface IExceptionHandler {
    /**
     * 异常处理。
     *
     * @param \Throwable $ex
     */
    function exception(\Throwable $ex): void;
}