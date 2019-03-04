<?php
namespace DoveFramework\Context;

/**
 * 自定义进程上下文类。
 *
 * @package       DoveFramework\Context
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
class SwooleProcess {
    /**
     * 进程名。
     *
     * @var string
     */
    protected $process_name = NULL;

    /**
     * 进程数量。
     *
     * @var int
     */
    protected $thread_num = 1;

    /**
     * 构造函数。
     *
     * @param string $process_name 指定进程名称。(注: 包含 NS 的完全限定名.)
     */
    function __construct(string $process_name) {
        $this->process_name = $process_name;
    }

    /**
     * 获取 SwooleProcessBase 进程完全限定名。
     *
     * @return string
     */
    function getProcessName(): string {
        return $this->process_name;
    }

    /**
     * 获取进程数量。
     *
     * @return int
     */
    function getThreadNum(): int {
        return $this->thread_num;
    }
}