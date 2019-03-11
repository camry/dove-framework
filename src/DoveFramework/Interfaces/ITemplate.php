<?php
namespace DoveFramework\Interfaces;

/**
 * 模板引擎接口。
 *
 * @package       DoveFramework\Interfaces
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
interface ITemplate {
    /**
     * 获取模板引擎实例。
     *
     * @return mixed
     */
    function getInstance();

    /**
     * 模板变量赋值。
     *
     * @param string $key
     * @param mixed  $value
     * @return ITemplate
     */
    function assign(string $key, $value): ITemplate;

    /**
     * 输出模板内容。
     *
     * @param string $name 模板文件名称。
     * @return string
     */
    function display(string $name): string;

    /**
     * 模板渲染。
     *
     * @param string $name    模板文件名称。
     * @param array  $context 模板参数。
     * @return string
     */
    function render(string $name, array $context = []): string;

    /**
     * 添加扩展对象。
     *
     * @param mixed $extension
     * @return ITemplate
     */
    function addExtension($extension): ITemplate;
}