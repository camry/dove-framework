<?php
namespace DoveFramework\Interfaces;

/**
 * 遵循 PSR-3 规范的日志接口声明。
 *
 * @package       DoveFramework\Interfaces
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
interface ILogger {
    /**
     * 获取日志模块名称。
     *
     * @return string
     */
    function getModule(): string;

    /**
     * 设置日志模块名称。
     *
     * @param string $module
     */
    function setModule(string $module): void;

    /**
     * 严重错误。
     *
     * @param string $message
     * @param array  $context
     */
    function fatal(string $message, array $context = []): void;

    /**
     * 追踪信息。
     *
     * @param string $message
     * @param array  $context
     */
    function trace(string $message, array $context = []): void;

    /**
     * 捕获的异常。
     *
     * @param string     $message
     * @param array      $context
     * @param \Throwable $throwable
     */
    function error(string $message, array $context = [], \Throwable $throwable = NULL): void;

    /**
     * 警告信息。
     *
     * @param string $message
     * @param array  $context
     */
    function warn(string $message, array $context = []): void;

    /**
     * 常规日志信息。
     *
     * @param string $message
     * @param array  $context
     */
    function info(string $message, array $context = []): void;

    /**
     * 调试信息。
     *
     * @param string $message
     * @param array  $context
     */
    function debug(string $message, array $context = []): void;

    /**
     * 任意级别日志。
     *
     * @param int    $level
     * @param string $message
     * @param array  $context
     */
    function log(int $level, string $message, array $context = []): void;
}