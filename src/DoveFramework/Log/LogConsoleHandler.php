<?php
namespace DoveFramework\Log;

use DoveFramework\Bootstrap\AbstractBootstrap;
use DoveFramework\Context\AbstractBase;
use DoveFramework\Interfaces\ILogger;
use DoveFramework\Interfaces\ILogHandler;
use DoveFramework\SYS;

/**
 * 控制台日志输出处理器。
 *
 * @package       DoveFramework\Log
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
class LogConsoleHandler extends AbstractBase implements ILogHandler {
    /**
     * 是否使用彩色标记警告/错误信息？
     *
     * @var bool
     */
    private $colour = true;

    /**
     * 构造函数。
     *
     * @param AbstractBootstrap $bootstrap 指定 AbstractBootstrap 实例引用。
     * @param bool              $colour    是否使用彩色标记警告/错误信息？
     */
    function __construct(AbstractBootstrap $bootstrap, bool $colour = true) {
        parent::__construct($bootstrap);

        $this->colour = $colour;
    }

    /**
     * 写入日志。
     *
     * @param ILogger $logger 管理器实例。
     * @param array   $msg    消息内容。
     * @param int     $level  级别。
     */
    function write(ILogger $logger, array $msg, int $level): void {
        if ($this->colour) {
            if ($level <= SYS::LOG_ERROR)
                $msg['m'] = "\033[1;31m" . $msg['m'] . "\033[0m";
            elseif ($level <= SYS::LOG_WARN)
                $msg['m'] = "\033[1;33m" . $msg['m'] . "\033[0m";
        }

        echo implode('', $msg);
    }
}