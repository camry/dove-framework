<?php
namespace DoveFramework\Context;

/**
 * SwooleProcessBase 进程组对象。
 *
 * @package       DoveFramework\Context
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
final class SwooleProcessGroup extends SwooleProcess {
    /**
     * 构造函数。
     *
     * @param string $process_name 指定进程名称。(注: 包含 NS 的完全限定名.)
     * @param int    $process_num  指定启动的进程数量。
     */
    function __construct(string $process_name, int $process_num) {
        parent::__construct($process_name);

        $this->process_num = $process_num;
    }
}