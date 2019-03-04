<?php
namespace DoveFramework\Interfaces;

/**
 * Redis 客户端接口。
 *
 * @package       DoveFramework\Interfaces
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
interface IRedis extends ICache {
    /**
     * 切换当前数据库。
     *
     * @param int $dbindex 指定数据库索引。
     * @return bool
     */
    function select(int $dbindex): bool;

    /**
     * 获取 Redis 服务器状态信息。
     *
     * @return array
     */
    function info(): array;

    /**
     * 清理全部数据缓存。
     *
     * @return bool
     */
    function flushAll();

    /**
     * 清理指定的数据库缓存。
     *
     * @return bool
     */
    function flushDb();

    /**
     * 设置数组对象。
     *
     * @param string $key
     * @param array  $value
     * @param int    $ttl
     * @return mixed
     */
    function setArray(string $key, array $value, $ttl = 0);

    /**
     * 获取数组对象。
     *
     * @param string $key
     * @return array|false
     */
    function getArray(string $key);

    /**
     * 增量操作。
     *
     * @param string $key   缓存键名。
     * @param float  $value 增量步长值。
     * @return float 返回运算后的新值。
     */
    function incrByFloat(string $key, float $value);

    /**
     * 设置缓存过期。
     *
     * @param string $key 指定键名。
     * @param int    $ttl 设定 X 秒后过期。
     * @return bool
     */
    function expire(string $key, int $ttl);

    /**
     * 设置缓存过期。
     *
     * @param string $key       指定键名。
     * @param int    $timestamp 设定过期的时间戳。
     * @return bool
     */
    function expireAt(string $key, int $timestamp);

    /**
     * Redis::hKeys().
     *
     * @param string $key
     * @return array
     */
    function hKeys(string $key);

    /**
     * Redis::hMSet().
     *
     * @param string $key
     * @param array  $values
     * @return bool
     */
    function hMSet(string $key, array $values);

    /**
     * Redis::hMGet().
     *
     * @param string $key
     * @param array  $sub_keys
     * @return bool
     */
    function hMGet(string $key, array $sub_keys);

    /**
     * Redis::hSet().
     *
     * @param string $key
     * @param string $sub_key
     * @param mixed  $value
     * @return int|false
     */
    function hSet(string $key, string $sub_key, $value);

    /**
     * Redis::hSetNx().
     *
     * @param string $key
     * @param string $sub_key
     * @param mixed  $value
     * @return bool
     */
    function hSetNx(string $key, string $sub_key, $value);

    /**
     * Redis::hGet().
     *
     * @param string $key
     * @param string $sub_key
     * @return int|false
     */
    function hGet(string $key, string $sub_key);

    /**
     * Redis::hGetAll().
     *
     * @param string $key
     * @return array
     */
    function hGetAll(string $key);

    /**
     * Redis::hDel().
     *
     * @param string    $key
     * @param \string[] $sub_key
     * @return int
     */
    function hDel(string $key, string ... $sub_key);

    /**
     * Redis::hExists().
     *
     * @param string $key
     * @param string $sub_key
     * @return bool
     */
    function hExists(string $key, string $sub_key);

    /**
     * Redis::hLen().
     *
     * @param string $key
     * @return int
     */
    function hLen(string $key);

    /**
     * Redis::hIncrBy().
     *
     * @param string $key
     * @param string $sub_key
     * @param int    $value
     * @return int
     */
    function hIncrBy(string $key, string $sub_key, int $value = 1);

    /**
     * Redis::hIncrByFloat().
     *
     * @param string $key
     * @param string $sub_key
     * @param float  $value
     * @return int
     */
    function hIncrByFloat(string $key, string $sub_key, float $value);

    /**
     * Redis::lPush().
     *
     * @param string     $key
     * @param int|string $value
     * @return int 返回新的 LIST 长度。
     */
    function lPush(string $key, $value);

    /**
     * Redis::rPush().
     *
     * @param string     $key
     * @param int|string $value
     * @return int 返回新的 LIST 长度。
     */
    function rPush(string $key, $value);

    /**
     * Redis::lPop().
     *
     * @param string $key
     * @return mixed 返回 LIST 中的第一个元素。
     */
    function lPop(string $key);

    /**
     * Redis::rPop().
     *
     * @param string $key
     * @return mixed 返回 LIST 中的最后一个元素。
     */
    function rPop(string $key);

    /**
     * Redis::lLen().
     *
     * @param string $key
     * @return int 返回 LIST 的长度。
     */
    function lLen(string $key);

    /**
     * Redis::lRem().
     *
     * @param string     $key
     * @param int|string $value
     * @param int        $count
     * @return false|int 返回 LIST 的长度。
     */
    function lRem(string $key, $value, int $count = 1);

    /**
     * Redis::lRange().
     *
     * @param string $key
     * @param int    $start
     * @param int    $end
     * @return array
     */
    function lRange(string $key, int $start = 0, int $end = -1);

    /**
     * Redis::blPop().
     *
     * @param string $key     指定键名。
     * @param int    $timeout 设定阻塞超时时长。(秒)
     * @return array
     */
    function blPop(string $key, int $timeout);

    /**
     * Redis::brPop().
     *
     * @param string $key     指定键名。
     * @param int    $timeout 设定阻塞超时时长。(秒)
     * @return array
     */
    function brPop(string $key, int $timeout);

    /**
     * Redis::sAdd().
     *
     * @param string $key    指定键名。
     * @param array  $values 指定一个或多个值。
     * @return array
     */
    function sAdd(string $key, ... $values);

    /**
     * Redis::sMembers().
     *
     * @param string $key 指定键名。
     * @return array
     */
    function sMembers(string $key);

    /**
     * Redis::sRem().
     *
     * @param string     $key 指定键名。
     * @param string|int $member
     * @return int
     */
    function sRem(string $key, $member);

    /**
     * Redis::sCard().
     *
     * @param string $key 指定键名。
     * @return int 若集合不存在，则返回整数零。
     */
    function sCard(string $key);

    /**
     * Redis::sIsMember().
     *
     * @param string     $key 指定键名。
     * @param string|int $member
     * @return bool
     */
    function sIsMember(string $key, $member);

    /**
     * Redis::zAdd().
     *
     * @param string $key 指定键名。
     * @param int    $score
     * @param string $member
     * @return int
     */
    function zAdd(string $key, int $score, string $member);

    /**
     * Redis::zRem().
     *
     * @param string $key 指定键名。
     * @param string $member
     * @return int
     */
    function zRem(string $key, string $member);

    /**
     * Redis::zRange().
     *
     * @param string    $key 指定键名。
     * @param int       $start
     * @param int       $end
     * @param bool|null $withscores
     * @return array
     */
    function zRange(string $key, int $start = 0, int $end = -1, $withscores = NULL);

    /**
     * Redis::zRevRange().
     *
     * @param string    $key 指定键名。
     * @param int       $start
     * @param int       $end
     * @param bool|null $withscores
     * @return array
     */
    function zRevRange(string $key, int $start = 0, int $end = -1, $withscores = NULL);

    /**
     * Redis::zRangeByScore().
     *
     * @param string $key 指定键名。
     * @param int    $start
     * @param int    $end
     * @param array  $options
     * @return array
     */
    function zRangeByScore(string $key, int $start = 0, int $end = -1, array $options = []);

    /**
     * Redis::zRevRangeByScore().
     *
     * @param string $key 指定键名。
     * @param int    $start
     * @param int    $end
     * @param array  $options
     * @return array
     */
    function zRevRangeByScore(string $key, int $start = 0, int $end = -1, array $options = []);

    /**
     * Redis::zRank().
     *
     * @param string $key 指定键名。
     * @param string $member
     * @return int
     */
    function zRank(string $key, string $member);

    /**
     * Redis::zRevRank().
     *
     * @param string $key 指定键名。
     * @param string $member
     * @return int
     */
    function zRevRank(string $key, string $member);

    /**
     * Redis::zCount().
     *
     * @param string $key 指定键名。
     * @param int    $start
     * @param int    $end
     * @return int
     */
    function zCount(string $key, int $start, int $end);

    /**
     * Redis::zCard().
     *
     * @param string $key 指定键名。
     * @return int
     */
    function zCard(string $key);

    /**
     * Redis::zScore().
     *
     * @param string $key 指定键名。
     * @param string $member
     * @return int
     */
    function zScore(string $key, string $member);

    /**
     * 查询过期时间。
     *
     * @param string $key
     * @return int
     */
    function ttl(string $key);
}