<?php
namespace DoveFramework\Log;

use DoveFramework\Context\AbstractBase;
use DoveFramework\Interfaces\ILogger;
use DoveFramework\Interfaces\ILogHandler;

/**
 * 按天分拆的日志文件处理器。
 *
 * @package       DoveFramework\Log
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
class LogFileHandler extends AbstractBase implements ILogHandler {
    /**
     * 写入日志。
     *
     * @param ILogger $logger 管理器实例。
     * @param array   $msg    消息内容。
     * @param int     $level  级别。
     */
    function write(ILogger $logger, array $msg, int $level): void {
        $cts = time();

        $f = $this->bootstrap->cfg->getLogDirectory() . DIRECTORY_SEPARATOR . $logger->getModule() . '.' . date('Ymd', $cts) . '.log';

        $fp = fopen($f, 'a');

        if ($fp) {
            fwrite($fp, implode('', $msg));
            fclose($fp);
        }
    }
}