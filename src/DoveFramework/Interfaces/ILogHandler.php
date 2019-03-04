<?php
namespace DoveFramework\Interfaces;

/**
 * 日志输出处理器。
 *
 * @package       DoveFramework\Interfaces
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
interface ILogHandler {
    /**
     * 写入日志。
     *
     * @param ILogger $logger 管理器实例。
     * @param array   $msg    消息内容。
     * @param int     $level  级别。
     */
    function write(ILogger $logger, array $msg, int $level): void;
}