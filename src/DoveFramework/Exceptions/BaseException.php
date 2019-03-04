<?php
namespace DoveFramework\Exceptions;

/**
 * 异常基类。
 *
 * @package       DoveFramework\Exceptions
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
class BaseException extends \Exception {
    /**
     * 异常构造函数。
     *
     * @param string     $message  抛出的异常消息内容。
     * @param int        $code     异常代码。
     * @param \Throwable $previous 异常链中的前一个异常。
     */
    function __construct($message = "", $code = 0, \Throwable $previous = NULL) {
        parent::__construct($message, is_int($code) ? $code : 500, $previous);
    }
}