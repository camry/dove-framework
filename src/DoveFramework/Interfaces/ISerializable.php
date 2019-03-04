<?php
namespace DoveFramework\Interfaces;

/**
 * ISerializable 接口定义。
 *
 * @package       DoveFramework\Interfaces
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
interface ISerializable {
    /**
     * 对象转换为数组输出。
     *
     * @param array $options 指定选项参数。
     * @return array
     */
    function toArray(array $options = []): array;

    /**
     * 对象转换为 JSON 字符串输出。
     *
     * @param array $options      指定选项参数。
     * @param int   $json_options 指定 JSON 编码选项参数。(默认值: 320)
     * @return string
     */
    function toJSONString(array $options = [], int $json_options = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE): string;

    /**
     * 对象转换为字符串。
     *
     * @return string
     */
    function __toString(): string;
}