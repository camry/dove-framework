<?php
namespace DoveFramework\Bootstrap;

use DoveFramework\Context\AbstractSwooleProcessBase;
use DoveFramework\Context\SwooleProcess;
use DoveFramework\Exceptions\ConfigurationException;
use DoveFramework\Exceptions\TypeException;
use DoveFramework\Interfaces\ISwooleHTTPHandler;
use DoveFramework\Interfaces\ISwooleProcessManager;

/**
 * 基于 Swoole 的 HTTP 服务启动器。
 *
 * @package       DoveFramework\Bootstrap
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
class HTTPBootstrap extends AbstractSwooleBootstrap {
    /**
     * Swoole HTTP 服务器实例。
     *
     * @var \Swoole\Http\Server
     */
    protected $serv = NULL;

    /**
     * 事件处理器。
     *
     * @var ISwooleHTTPHandler
     */
    protected $request_handler = NULL;

    /**
     * Swoole 自定义进程列表。
     *
     * @var SwooleProcess[]
     */
    protected $processes = [];

    /**
     * 执行控制器行为方法。
     *
     * @throws ConfigurationException
     * @throws TypeException
     */
    function execute(): void {
        $cfgs = $this->cfg->getConfigs();

        if (!isset($cfgs['http']['sets']))
            throw new ConfigurationException('尚未配置 HTTP 服务参数。');

        $this->serv = new \Swoole\Http\Server(isset($cfgs['http']['bind']) ? $cfgs['http']['bind'] : '0.0.0.0', isset($cfgs['http']['port']) ? $cfgs['http']['port'] : 80, SWOOLE_PROCESS, SWOOLE_SOCK_TCP | SWOOLE_ASYNC);
        $this->serv->set($cfgs['http']['sets']);

        // 检查 ipv6 支持
        if (isset($cfgs['http']['enable_ipv6']) && true === $cfgs['http']['enable_ipv6']) {
            $this->serv->addlistener('::', $cfgs['http']['port'], SWOOLE_SOCK_TCP6 | SWOOLE_ASYNC);
        }

        $this->serv->on('start', [$this->request_handler, 'onStart']);
        $this->serv->on('shutdown', [$this->request_handler, 'onShutdown']);
        $this->serv->on('workerstart', [$this->request_handler, 'onWorkerStart']);
        $this->serv->on('workerstop', [$this->request_handler, 'onWorkerStop']);
        $this->serv->on('managerstart', [$this->request_handler, 'onManagerStart']);
        $this->serv->on('managerstop', [$this->request_handler, 'onManagerStop']);
        $this->serv->on('workererror', [$this->request_handler, 'onWorkerError']);
        $this->serv->on('task', [$this->request_handler, 'onTask']);
        $this->serv->on('finish', [$this->request_handler, 'onFinish']);
        $this->serv->on('pipemessage', [$this->request_handler, 'onPipeMessage']);
        $this->serv->on('request', [$this->request_handler, 'onRequest']);

        // 检测是否存在自定义工作进程？
        if ($cfgs['http']['enable_user_process']) {
            // 检查是否设置了进程管理器 ...
            if ($this->process_manager instanceof ISwooleProcessManager)
                call_user_func_array($this->process_manager . '::handle', [$this, $this->swoole]);

            foreach ($this->processes as $process) {
                $cls_n = $process->getProcessName();

                for ($i = 0; $i < $process->getProcessNum(); $i++) {
                    $cls_o = new $cls_n($this);

                    if (!($cls_o instanceof AbstractSwooleProcessBase))
                        throw new TypeException('Swoole 自定义进程必须继承 SwooleProcessBase 类。');

                    $cls_o->setIndex($i);

                    $this->serv->addProcess($cls_o);
                }
            }
        }

        $this->serv->start();
    }

    /**
     * 获取 Swoole HTTP 服务对象实例。
     *
     * @return \Swoole\Http\Server
     */
    function getTcpServerInstance(): \Swoole\Http\Server {
        return $this->serv;
    }

    /**
     * 获取 HTTP 处理器实例。
     *
     * @return ISwooleHTTPHandler
     */
    function getRequestHandler(): ISwooleHTTPHandler {
        return $this->request_handler;
    }

    /**
     * 设置 HTTP 处理器实例。
     *
     * @param ISwooleHTTPHandler $request_handler
     * @return HTTPBootstrap
     */
    function setRequestHandler(ISwooleHTTPHandler $request_handler): HTTPBootstrap {
        $this->request_handler = $request_handler;

        return $this;
    }
}