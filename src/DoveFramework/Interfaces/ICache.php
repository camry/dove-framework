<?php
namespace DoveFramework\Interfaces;

/**
 * 缓存接口。
 *
 * @package       DoveFramework\Interfaces
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
interface ICache extends IDisposable {
    /**
     * 打开连接。
     */
    function connect(): void;

    /**
     * 检查 key 是否存在？
     *
     * @param string $key 指定键名。
     * @return bool
     */
    function exists(string $key);

    /**
     * 设置缓存。
     *
     * @param string $key   缓存键名。
     * @param mixed  $value 缓存值对象。
     * @param int    $ttl   过期时间。(单位: 秒)
     * @return bool
     */
    function set(string $key, $value, $ttl = 0);

    /**
     * 获取缓存对象。
     *
     * @param string $key 缓存键名。
     * @return mixed|false 当缓存数据不存在时，返回布尔值 False。
     */
    function get(string $key);

    /**
     * 获取一个或多个缓存对象。
     *
     * @param string ...$keys
     * @return array
     */
    function gets(string ...$keys);

    /**
     * 删除缓存。
     *
     * @param string ...$keys
     * @return array
     */
    function del(string ...$keys);

    /**
     * 增量操作。
     *
     * @param string $key   缓存键名。
     * @param int    $value 增量步长值。
     * @return int 返回运算后的新值。
     */
    function incrBy(string $key, int $value = 1);

    /**
     * 减量操作。
     *
     * @param string $key   缓存键名。
     * @param int    $value 减量步长值。
     * @return int 返回运算后的新值。
     */
    function decrBy(string $key, int $value = 1);
}