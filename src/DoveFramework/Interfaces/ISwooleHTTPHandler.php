<?php
namespace DoveFramework\Interfaces;

/**
 * Swoole HTTP 事件处理器接口。
 *
 * @package       DoveFramework\Interfaces
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
interface ISwooleHTTPHandler extends ISwooleHandler {
    /**
     * HTTP 请求响应回调事件。
     *
     * @param \Swoole\Http\Request  $request
     * @param \Swoole\Http\Response $response
     */
    function onRequest(\Swoole\Http\Request $request, \Swoole\Http\Response $response);
}