<?php
namespace DoveFramework\Log;

use DoveFramework\Context\AbstractBase;
use DoveFramework\Interfaces\ILogger;
use DoveFramework\Interfaces\ILogHandler;
use DoveFramework\SYS;

/**
 * 日志管理器。
 *
 * @package       DoveFramework\Log
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
class Logger extends AbstractBase implements ILogger {
    /**
     * 级别。
     *
     * @var array
     */
    protected $levels = array(
        100 => 'FATAL',
        200 => 'ERROR',
        300 => 'WARN',
        400 => 'INFO',
        500 => 'DEBUG',
        600 => 'TRACE',
    );

    /**
     * 日志输出处理器列表。
     *
     * @var ILogHandler[]
     */
    protected $handlers = [];

    /**
     * 模块名称。
     *
     * @var string
     */
    private $module = 'app';

    /**
     * 获取日志模块名称。
     *
     * @return string
     */
    function getModule(): string {
        return $this->module;
    }

    /**
     * 设置模块名称。
     *
     * @param string $module
     */
    function setModule(string $module): void {
        $this->module = $module;
    }

    /**
     * 设置日志处理器列表。
     *
     * @param array $handlers
     */
    function setHandlers(array $handlers): void {
        $this->handlers = $handlers;
    }

    /**
     * 严重错误。
     *
     * @param string $message
     * @param array  $context
     */
    function fatal(string $message, array $context = []): void {
        $this->log(SYS::LOG_FATAL, $message, $context);
    }

    /**
     * 追踪信息。
     *
     * @param string $message
     * @param array  $context
     */
    function trace(string $message, array $context = []): void {
        $this->log(SYS::LOG_TRACE, $message, $context);
    }

    /**
     * 捕获的异常。
     *
     * @param string     $message
     * @param array      $context
     * @param \Throwable $throwable
     */
    function error(string $message, array $context = [], \Throwable $throwable = NULL): void {
        if ($throwable)
            $this->log(SYS::LOG_ERROR, $message . PHP_EOL . $throwable->getTraceAsString(), $context);
        else
            $this->log(SYS::LOG_ERROR, $message, $context);
    }

    /**
     * 警告信息。
     *
     * @param string $message
     * @param array  $context
     */
    function warn(string $message, array $context = []): void {
        $this->log(SYS::LOG_WARN, $message, $context);
    }

    /**
     * 常规日志信息。
     *
     * @param string $message
     * @param array  $context
     */
    function info(string $message, array $context = []): void {
        $this->log(SYS::LOG_INFO, $message, $context);
    }

    /**
     * 调试信息。
     *
     * @param string $message
     * @param array  $context
     */
    function debug(string $message, array $context = []): void {
        $this->log(SYS::LOG_DEBUG, $message, $context);
    }

    /**
     * 任意级别日志。
     *
     * @param int    $level
     * @param string $message
     * @param array  $context
     */
    function log(int $level, string $message, array $context = []): void {
        if ($level > $this->bootstrap->cfg->getLogLevel())
            return;

        $t     = microtime(true);
        $micro = sprintf("%03d", ($t - floor($t)) * 1000);

        if ($context) {
            foreach ($context as $key => $value) {
                $message = str_replace('{' . $key . '}', $value, $message);
            }
        }

        $msgs = ['t' => date('Y-m-d H:i:s') . '.' . $micro, 'l' => ' [' . $this->levels[$level] . ']', 'p' => '[#' . getmypid() . '] ', 'm' => $message, 'n' => PHP_EOL];

        foreach ($this->handlers as $handler) {
            $handler->write($this, $msgs, $level);
        }
    }
}