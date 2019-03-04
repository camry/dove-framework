<?php
namespace DoveFramework\Core;

use DoveFramework\Interfaces\ISerializable;

/**
 * 对象序列化抽象类。
 *
 * @package       DoveFramework\Core
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
abstract class ObjectSerializable implements ISerializable {
    /**
     * 对象转换为数组输出。
     *
     * @param array $options 指定选项参数。
     * @return array
     * @throws \ReflectionException
     */
    function toArray(array $options = []): array {
        $d     = [];
        $ref   = new \ReflectionClass($this);
        $props = $ref->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED);

        foreach ($props as $prop) {
            $prop->setAccessible(true);

            $nn = $prop->getName();
            $vv = $prop->getValue($this);

            if ($vv instanceof ISerializable) {
                $d[$nn] = $vv->toArray($options);
            } elseif ('array' == gettype($vv) || $vv instanceof \ArrayAccess) {
                foreach ($vv as $k => $v) {
                    if ($v instanceof ISerializable)
                        $d[$nn][$k] = $v->toArray($options);
                    else
                        $d[$nn][$k] = $v;
                }
            } else {
                $d[$nn] = $vv;
            }
        }

        return $d;
    }

    /**
     * 对象转换为 JSON 字符串输出。
     *
     * @param array $options      指定选项参数。
     * @param int   $json_options 指定 JSON 编码选项参数。(默认值: 320)
     * @return string
     * @throws \ReflectionException
     */
    function toJSONString(array $options = [], int $json_options = 320): string {
        return json_encode($this->toArray($options), $json_options);
    }

    /**
     * 对象转换为 MessagePack 编码字符串。
     *
     * @return string
     */
    function __toString(): string {
        return \msgpack_serialize($this);
    }
}