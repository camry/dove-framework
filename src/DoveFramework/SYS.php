<?php
namespace DoveFramework;

/**
 * 系统全局常量定义。
 *
 * @package       DoveFramework
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
class SYS {
    const SQL_TYPE_INSERT     = 1;
    const SQL_TYPE_UPDATE     = 2;
    const SQL_TYPE_DELETE     = 3;
    const SQL_TYPE_FETCH      = 11;
    const SQL_TYPE_FETCH_ALL  = 12;
    const SQL_TYPE_SCALAR     = 13;
    const QUERY_RESULT_SINGLE = 1;
    const QUERY_RESULT_MULTI  = 2;
    const QUERY_RESULT_SCALAR = 3;
    const ENC_DEFAULTS        = 2;
    const ENC_JSON            = 1;
    const ENC_MSGPACK         = 2;
    const ENC_IGBINARY        = 3;
    const LOG_OFF             = 0;
    const LOG_FATAL           = 100;
    const LOG_ERROR           = 200;
    const LOG_WARN            = 300;
    const LOG_INFO            = 400;
    const LOG_DEBUG           = 500;
    const LOG_TRACE           = 600;
    const LOG_ALL             = 9999;
}