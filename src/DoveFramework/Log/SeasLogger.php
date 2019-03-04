<?php
namespace DoveFramework\Log;

use DoveFramework\Context\AbstractBase;
use DoveFramework\Exceptions\NonImplementedException;
use DoveFramework\Interfaces\ILogger;

/**
 * 基于 SeasLog 扩展的日志管理对象。
 *
 * @package       DoveFramework\Log
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
class SeasLogger extends AbstractBase implements ILogger {
    /**
     * 获取日志模块名称。
     *
     * @return string
     */
    function getModule(): string {
        return \SeasLog::getLastLogger();
    }

    /**
     * 设置日志模块名称。
     *
     * @param string $module
     */
    function setModule(string $module): void {
        \SeasLog::setLogger($module);
    }

    /**
     * 严重错误。
     *
     * @param string $message
     * @param array  $context
     */
    function fatal(string $message, array $context = []): void {
        \SeasLog::error($message, $context);
    }

    /**
     * 追踪信息。
     *
     * @param string $message
     * @param array  $context
     */
    function trace(string $message, array $context = []): void {
        \SeasLog::debug($message, $context);
    }

    /**
     * 捕获的异常。
     *
     * @param string     $message
     * @param array      $context
     * @param \Throwable $throwable
     */
    function error(string $message, array $context = [], \Throwable $throwable = NULL): void {
        if ($throwable instanceof \Throwable)
            \SeasLog::error($message . "\n" . $throwable->getTraceAsString(), $context);
        else
            \SeasLog::error($message, $context);
    }

    /**
     * 警告信息。
     *
     * @param string $message
     * @param array  $context
     */
    function warn(string $message, array $context = []): void {
        \SeasLog::warning($message, $context);
    }

    /**
     * 常规日志信息。
     *
     * @param string $message
     * @param array  $context
     */
    function info(string $message, array $context = []): void {
        \SeasLog::info($message, $context);
    }

    /**
     * 调试信息。
     *
     * @param string $message
     * @param array  $context
     */
    function debug(string $message, array $context = []): void {
        \SeasLog::debug($message, $context);
    }

    /**
     * 任意级别日志。
     *
     * @param int    $level
     * @param string $message
     * @param array  $context
     * @throws NonImplementedException
     */
    function log(int $level, string $message, array $context = []): void {
        throw new NonImplementedException('未实现的方法。');
    }
}