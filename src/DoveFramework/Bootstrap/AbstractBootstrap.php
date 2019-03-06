<?php
namespace DoveFramework\Bootstrap;

use DoveFramework\Context\DefaultContext;
use DoveFramework\Entity\SystemConfiguration;
use DoveFramework\Interfaces\IContext;
use DoveFramework\Interfaces\IExceptionHandler;
use DoveFramework\Interfaces\ILogger;
use DoveFramework\Interfaces\ILogHandler;
use DoveFramework\Log\LogFileHandler;
use DoveFramework\Log\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * 抽象启动器。
 *
 * @package       DoveFramework\Bootstrap
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
abstract class AbstractBootstrap {
    /**
     * 控制器命名空间。
     *
     * @var string
     */
    protected $controller_ns = NULL;

    /**
     * 系统配置对象。
     *
     * @var SystemConfiguration
     */
    public $cfg = NULL;

    /**
     * 是否 CLI 命令行模式？
     *
     * @var bool
     */
    public $cli = false;

    /**
     * 命令行参数列表。
     *
     * @var array
     */
    public $argv = [];

    /**
     * 自定义异常处理器。
     *
     * @var IExceptionHandler
     */
    protected $exception_handler = NULL;

    /**
     * Symfony 事件订阅器。
     *
     * @var array
     */
    protected $event_subscribers = [];

    /**
     * Symfony 事件分配器实例。
     *
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    public $dispatcher = NULL;

    /**
     * 日志模块名称。
     *
     * @var string
     */
    protected $log_name = NULL;

    /**
     * 日志处理器列表。
     *
     * @var ILogHandler[]
     */
    protected $log_handlers = [];

    /**
     * 日志对象实例。
     *
     * @var ILogger
     */
    public $logger = NULL;

    /**
     * Application 上下文实例。
     *
     * @var IContext
     */
    protected $context = NULL;

    /**
     * 标记上下文管理对象是否已初始化？
     *
     * @var bool
     */
    protected $context_initialized = false;

    /**
     * 是否延迟初始化上下文实例？
     *
     * @var bool
     */
    protected $context_defered = false;

    /**
     * 启动程序。
     *
     * @param SystemConfiguration $cfg  系统配置对象。
     * @param array|null          $argv 指定命令行参数列表。(注: 非 CLI 模式下无需传此参数)
     */
    function dispatch(SystemConfiguration $cfg, ?array &$argv = NULL): void {
        $this->cfg  = $cfg;
        $this->argv = &$argv;
        $this->cli  = 0 == strcmp('cli', PHP_SAPI);

        $this->initialize();
        $this->initializeComplete();
        $this->parse();
        $this->validate();
        $this->execute();
    }

    /**
     * 初始化方法。
     */
    final function initialize(): void {
        // 设置时区
        date_default_timezone_set($this->cfg->getTimezone());

        // 设置用户自定义的错误处理函数
        set_error_handler([$this, 'defErrorHandler'], E_ALL ^ E_NOTICE);
        // 设置用户自定义的异常处理函数
        set_exception_handler([$this, 'defExceptionHandler']);

        // 注册 shutdown 回调函数
        register_shutdown_function([$this, 'defShutdownHandler']);

        // 注册事件分配器
        if ($this->event_subscribers) {
            $this->dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();

            foreach ($this->event_subscribers as $subscriber)
                $this->dispatcher->addSubscriber($subscriber);
        }

        // 日志
        $this->logger = new Logger($this);

        if (empty($this->log_handlers)) {
            $this->logger->setModule($this->cfg->getLogName());
            $this->logger->setHandlers([new LogFileHandler($this)]);
        } else {
            $this->logger->setModule($this->log_name);
            $this->logger->setHandlers($this->log_handlers);
        }

        // 初始化上下文管理实例
        if (!$this->isContextDefered())
            $this->initContext();
    }

    /**
     * 缺省错误处理函数。
     *
     * @param int    $errno   错误的级别。
     * @param string $errstr  错误的信息。
     * @param string $errfile 发生错误的文件名。
     * @param int    $errline 错误发生的行号。
     * @throws \ErrorException
     */
    function defErrorHandler($errno, $errstr, $errfile, $errline): void {
        throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
    }

    /**
     * 缺省异常处理函数。
     *
     * @param \Throwable $ex
     */
    function defExceptionHandler(\Throwable $ex) {
        if ($this->exception_handler) {
            $this->exception_handler->exception($ex);
        } else {
            echo '(#', $ex->getCode(), ')', $ex->getMessage(), PHP_EOL;

            if ($this->cfg->isDebug())
                echo $ex->getTraceAsString(), PHP_EOL;
        }
        exit(2);
    }

    /**
     * 缺省 Shutdown 事件回调函数。
     */
    function defShutdownHandler(): void {
        // 释放上下文对象.
        if ($this->context)
            $this->context->dispose();

        $this->dispatcher = NULL;
        $this->logger     = NULL;
        $this->context    = NULL;
    }

    /**
     * 获取 Symfony 事件订阅器实例列表。
     *
     * @return array
     */
    function getEventSubscribers(): array {
        return $this->event_subscribers;
    }

    /**
     * 添加 Symfony 事件订阅器实例列表。
     *
     * @param EventSubscriberInterface ...$subscribers
     * @return AbstractBootstrap
     */
    function addEventSubscribers(EventSubscriberInterface ...$subscribers): AbstractBootstrap {
        $this->event_subscribers = $subscribers;

        return $this;
    }

    /**
     * 添加日志处理器。
     *
     * @param string      $log_name        日志模块名称。
     * @param ILogHandler ...$log_handlers 日志处理器。
     * @return AbstractBootstrap
     */
    function addLogHandler(string $log_name, ILogHandler ...$log_handlers): AbstractBootstrap {
        $this->log_name     = $log_name;
        $this->log_handlers = $log_handlers;

        return $this;
    }

    /**
     * 当应用系统真正进入运行阶段时调用此方法。(注: 适用于异步系统的延迟上下文初始化场景!)
     */
    final function started(): void {
        $this->initContext();
    }

    /**
     * 初始化上下文管理器。
     */
    final function initContext(): void {
        if (false === $this->context_initialized) {
            if (!$this->context)
                $this->context = new DefaultContext($this);

            if ($this->context) {
                $this->context->initialize();
            }

            $this->context_initialized = true;
        }
    }

    /**
     * 初始化完成调用方法。
     */
    abstract function initializeComplete(): void;

    /**
     * 执行路由解析。
     */
    abstract function parse(): void;

    /**
     * 验证请求/参数合法性。
     */
    abstract function validate(): void;

    /**
     * 执行控制器行为方法。
     */
    abstract function execute(): void;

    /**
     * 获取当前系统时间戳。
     *
     * @return int
     */
    function cts(): int {
        return time();
    }

    /**
     * 获取当前系统时间戳。(注: 精确到毫秒!)
     *
     * @return float
     */
    function ctms(): float {
        return floor(microtime(true) * 1000);
    }

    /**
     * 设置控制器命名空间。
     *
     * @param string $controller_ns
     * @return AbstractBootstrap
     */
    function setControllerNs(string $controller_ns): AbstractBootstrap {
        $this->controller_ns = $controller_ns;

        return $this;
    }

    /**
     * 获取控制器命名空间。
     *
     * @return string
     */
    function getControllerNs(): string {
        return $this->controller_ns;
    }

    /**
     * 是否延迟初始化上下文？
     *
     * @return bool
     */
    function isContextDefered(): bool {
        return $this->context_defered;
    }

    /**
     * 获取上下文对象实例。
     *
     * @return IContext
     */
    function getContext() {
        return $this->context;
    }

    /**
     * 设置自定义上下文管理器。(注: 适用于应用层初始化全局对象的业务场景!)
     *
     * @param IContext $context 上下文管理器实例。
     * @param bool     $defered 是否延迟初始化上下文对象？
     * @return AbstractBootstrap
     */
    function setContext(IContext $context, bool $defered = false): AbstractBootstrap {
        $this->context         = $context;
        $this->context_defered = $defered;

        return $this;
    }
}