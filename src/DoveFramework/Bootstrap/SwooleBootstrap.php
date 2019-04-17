<?php
namespace DoveFramework\Bootstrap;

use DoveFramework\Context\SwooleProcess;
use DoveFramework\Core\AbstractServerInjection;
use DoveFramework\Core\AbstractServerManager;
use DoveFramework\Interfaces\ISwooleHandler;

/**
 * Swoole 服务启动器。
 *
 * @package       DoveFramework\Bootstrap
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
class SwooleBootstrap extends ProcessBootstrap {
    /**
     * Swoole 服务对象实例。
     *
     * @var \Swoole\Server
     */
    protected $swoole = NULL;

    /**
     * Socket 事件处理器对象实例。
     *
     * @var ISwooleHandler
     */
    protected $socket_handler = NULL;

    /**
     * Swoole Server 管理器。
     *
     * @var AbstractServerManager
     */
    protected $server_manager = NULL;

    /**
     * Swoole 自定义进程列表。
     *
     * @var SwooleProcess[]
     */
    protected $processes = [];

    /**
     * Swoole 用户进程管理接口名。
     *
     * @var string
     */
    protected $process_manager = NULL;

    /**
     * Swoole 服务启动参数注入。
     *
     * @var AbstractServerInjection
     */
    protected $server_injector = NULL;

    /**
     * 执行控制器行为方法。
     *
     * @throws \DoveFramework\Exceptions\ConfigurationException
     * @throws \DoveFramework\Exceptions\TypeException
     */
    function execute(): void {
        if (!$this->cfgs['swoole'])
            throw new \DoveFramework\Exceptions\ConfigurationException('未检测到 swoole 配置节点。');

        if (!$this->server_manager)
            throw new \RuntimeException('尚未设置 AbstractServerManager 对象。');

        $is_ms_startup = false;

        foreach ($this->server_manager->getServerPorts() as $listenPort) {
            if (!($listenPort instanceof \DoveFramework\Entity\ServerPort))
                throw new \DoveFramework\Exceptions\TypeException('ServerPort 对象类型错误。');

            $cls_serv_n = $listenPort->getServerInstanceName();

            if (!$is_ms_startup) {
                // 启动主服务
                $is_ms_startup = true;

                $this->swoole = new $cls_serv_n($listenPort->getHost(), $listenPort->getPort(), SWOOLE_PROCESS, $listenPort->getType());
                $this->swoole->set($this->getServerSettings($this->cfgs['swoole']));

                foreach ($listenPort->getBaseEvents() as $event => $cb) {
                    $this->swoole->on($event, [$this->socket_handler, $cb]);
                }
                foreach ($listenPort->getServerEvents() as $event => $cb) {
                    $this->swoole->on($event, [$this->socket_handler, $cb]);
                }
            } else {
                $port = $this->swoole->listen($listenPort->getHost(), $listenPort->getPort(), $listenPort->getType());

                if ($port instanceof \Swoole\Server\Port) {
                    foreach ($listenPort->getServerEvents() as $event => $cb) {
                        $port->on($event, [$this->socket_handler, $cb]);
                    }
                }
            }
        }

        // 检查是否设置了进程管理器 ...
        if ($this->process_manager) {
            call_user_func_array($this->process_manager . '::handle', [$this, $this->swoole]);
        }

        // 检测是否存在自定义工作进程？
        if ($this->processes) {
            foreach ($this->processes as $process) {
                $cls_n = $process->getProcessName();

                for ($i = 0; $i < $process->getProcessNum(); $i++) {
                    $cls_o = new $cls_n($this);

                    if (!($cls_o instanceof \DoveFramework\Context\SwooleProcessBase))
                        throw new \DoveFramework\Exceptions\TypeException('Swoole 自定义进程必须继承 SwooleProcessBase 类。');

                    $cls_o->setIndex($i);

                    $this->swoole->addProcess($cls_o);
                }
            }
        }

        $this->swoole->start();
    }

    /**
     * 设置 Socket 事件处理器实例。
     *
     * @param ISwooleHandler $socket_handler
     * @return SwooleBootstrap
     */
    function setSocketHandler(ISwooleHandler $socket_handler): SwooleBootstrap {
        $this->socket_handler = $socket_handler;

        return $this;
    }

    /**
     * 获取 Swoole 启动参数。
     *
     * @param array $settings
     * @return array
     */
    function getServerSettings(array $settings): array {
        if ($this->server_injector)
            return array_merge($settings, $this->server_injector->inject());

        return $settings;
    }

    /**
     * 添加 Swoole 自定义进程。
     *
     * @param SwooleProcess ...$processes
     * @return SwooleBootstrap
     */
    function addProcess(SwooleProcess ...$processes): SwooleBootstrap {
        array_push($this->processes, ...$processes);

        return $this;
    }

    /**
     * 设置进程管理器。
     *
     * @param string $process_manager 进程管理器类名。(注: 必须是 ISwooleProcessManager 接口类)
     * @return SwooleBootstrap
     */
    function setProcessManager(string $process_manager): SwooleBootstrap {
        $this->process_manager = $process_manager;

        return $this;
    }

    /**
     * 设置 Swoole 服务启动参数注入。
     *
     * @param AbstractServerInjection $server_injector
     * @return SwooleBootstrap
     */
    function setServerInjector(AbstractServerInjection $server_injector): SwooleBootstrap {
        $this->server_injector = $server_injector;

        return $this;
    }

    /**
     * 设置 Swoole Server 管理器。
     *
     * @param AbstractServerManager $server_manager
     * @return SwooleBootstrap
     */
    function setServerManager(AbstractServerManager $server_manager): SwooleBootstrap {
        $this->server_manager = $server_manager;

        return $this;
    }

    /**
     * 获取 Swoole 服务对象实例。
     *
     * @return \Swoole\Server
     */
    function getTcpServerInstance(): \Swoole\Server {
        return $this->swoole;
    }
}