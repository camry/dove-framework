<?php
namespace DoveFramework\Template;

use DoveFramework\Bootstrap\WebBootstrap;
use DoveFramework\Context\WebBase;
use DoveFramework\Interfaces\ITemplate;

/**
 * 基于 Twig 模板引擎适配器。（注：只适用 Swoole）
 *
 * @package       DoveFramework\Template
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
class TwigTemplate extends WebBase implements ITemplate {
    /**
     * Twig 实例。
     *
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * 数据集合。
     *
     * @var array
     */
    private $data = [];

    /**
     * 模板目录前缀。
     *
     * @var string
     */
    private $tpl_prefix = '';

    /**
     * 构造函数。
     *
     * @param WebBootstrap $bootstrap  指定 Web 启动器实例。
     * @param string       $tpl_prefix 模板目录前缀。
     */
    public function __construct(WebBootstrap $bootstrap, string $tpl_prefix) {
        parent::__construct($bootstrap);

        $this->tpl_prefix = $tpl_prefix;
    }

    /**
     * 析构函数。
     */
    function __destruct() {
        parent::__construct($this->bootstrap);

        unset($this->twig);
    }

    /**
     * 获取模板引擎实例。
     *
     * @return \Twig_Environment
     */
    function getInstance() {
        if (!($this->twig instanceof \Twig\Environment)) {
            $loader     = new \Twig\Loader\FilesystemLoader($this->tpl_prefix . 'tpl');
            $this->twig = new \Twig\Environment($loader, [
                'auto_reload' => true,
                'cache'       => $this->tpl_prefix . 'tpl_c',
            ]);
        }

        return $this->twig;
    }

    /**
     * 模板变量赋值。
     *
     * @param string $key
     * @param mixed  $value
     * @return ITemplate
     */
    function assign(string $key, $value): ITemplate {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * 输出模板内容。
     *
     * @param string $name
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    function display(string $name): string {
        return $this->render($name, $this->data);
    }

    /**
     * 模板渲染。
     *
     * @param string $name
     * @param array  $context
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    function render(string $name, array $context = []): string {
        return $this->getInstance()->render($name, $context);
    }

    /**
     * 添加扩展对象。
     *
     * @param mixed $extension
     * @return ITemplate
     */
    function addExtension($extension): ITemplate {
        $this->getInstance()->addExtension($extension);

        return $this;
    }
}