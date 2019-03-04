<?php
namespace DoveFramework\Cache;

use DoveFramework\Interfaces\IRedis;

/**
 * Redis 客户端扩展。
 *
 * @package       DoveFramework\Cache
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
class RedisClient implements IRedis {
    /**
     * 主机地址。
     *
     * @var string
     */
    private $host = '127.0.0.1';

    /**
     * 连接端口。
     *
     * @var int
     */
    private $port = 6379;

    /**
     * 认证密码。
     *
     * @var string
     */
    private $password = NULL;

    /**
     * 键名前缀。
     *
     * @var string
     */
    private $prefix = NULL;

    /**
     * Redis 客户端实例。
     *
     * @var \Redis
     */
    private $instance = NULL;

    /**
     * 缺省过期时间。
     *
     * @var int
     */
    private $default_ttl = 0;

    /**
     * 缺省 DB 索引。
     *
     * @var int
     */
    private $dbindex = 0;

    /**
     * 序列化方式。
     *
     * @var int
     */
    private $serializer = 0;

    /**
     * 构造函数。
     *
     * @param string $host        主机地址。
     * @param int    $port        端口。
     * @param string $password    认证密码。
     * @param string $prefix      设定 Key 前缀字符串。
     * @param int    $serializer  指定序列化方式。(默认值: 0|禁用)
     * @param int    $default_ttl 缺省过期时间。
     * @param int    $dbindex     缺省数据库索引。
     */
    function __construct(string $host = '127.0.0.1', int $port = 6379, string $password = '', string $prefix = '', int $serializer = 0, int $default_ttl = 0, int $dbindex = 0) {
        $this->host        = $host;
        $this->port        = $port;
        $this->password    = $password;
        $this->prefix      = $prefix;
        $this->serializer  = $serializer;
        $this->default_ttl = $default_ttl;
        $this->dbindex     = $dbindex;
    }

    /**
     * 获取 Redis 客户端实例。
     *
     * @return \Redis
     */
    function getRedis(): \Redis {
        return $this->instance;
    }

    /**
     * 打开连接。
     */
    function connect(): void {
        $this->instance = new \Redis();

        if (false !== strpos($this->host, '/'))
            $this->instance->connect($this->host);
        else
            $this->instance->connect($this->host, $this->port);

        if ($this->password)
            $this->instance->auth($this->password);

        $this->instance->setOption(\Redis::OPT_SERIALIZER, $this->serializer);

        if ($this->prefix)
            $this->instance->setOption(\Redis::OPT_PREFIX, $this->prefix);

        $this->instance->select($this->dbindex);
    }

    /**
     * 释放资源。
     */
    function dispose(): void {
        if ($this->instance)
            $this->instance->close();

        $this->instance = NULL;
    }

    /**
     * 执行 Redis 命令。
     *
     * @param string $cmd
     * @param array  ...$args
     * @return mixed
     */
    private function exec(string $cmd, ...$args) {
        return $this->instance->{$cmd}(...$args);
    }

    /**
     * 切换当前数据库。
     *
     * @param int $dbindex 指定数据库索引。
     * @return bool
     */
    function select(int $dbindex): bool {
        return $this->exec('select', $dbindex);
    }

    /**
     * 获取 Redis 服务器状态信息。
     *
     * @return array
     */
    function info(): array {
        return $this->exec('info');
    }

    /**
     * 清理全部数据缓存。
     *
     * @return bool
     */
    function flushAll() {
        return $this->exec('flushAll');
    }

    /**
     * 清理指定的数据库缓存。
     *
     * @return bool
     */
    function flushDb() {
        return $this->exec('flushDb');
    }

    /**
     * 设置数组对象。
     *
     * @param string $key
     * @param array  $value
     * @param int    $ttl
     * @return mixed
     */
    function setArray(string $key, array $value, $ttl = 0) {
        if ($ttl <= 0)
            $ttl = $this->default_ttl;

        if ($ttl > 0)
            return $this->exec('setEx', $key, $ttl, $value);
        else
            return $this->exec('set', $key, $value);
    }

    /**
     * 获取数组对象。
     *
     * @param string $key
     * @return array|false
     */
    function getArray(string $key) {
        return $this->exec('get', $key);
    }

    /**
     * 设置缓存。
     *
     * @param string $key   指定键名。
     * @param mixed  $value 缓存值对象。
     * @param int    $ttl   设定缓存过期时间。(默认值: 0|永不过期)
     * @return bool
     */
    function set(string $key, $value, $ttl = 0) {
        if ($ttl <= 0)
            $ttl = $this->default_ttl;

        if ($ttl > 0)
            return $this->exec('setEx', $key, $ttl, $value);
        else
            return $this->exec('set', $key, $value);
    }

    /**
     * 获取缓存值。
     *
     * @param string $key 指定键名。
     * @return mixed
     */
    function get(string $key) {
        return $this->exec('get', $key);
    }

    /**
     * 删除缓存。
     *
     * @param string[] $keys
     * @return int 返回删除的数量。
     */
    function del(string ...$keys) {
        return $this->exec('del', ...$keys);
    }

    /**
     * 检查 key 是否存在？
     *
     * @param string $key 指定键名。
     * @return bool
     */
    function exists(string $key) {
        return $this->exec('exists', $key);
    }

    /**
     * 设置缓存过期。
     *
     * @param string $key 指定键名。
     * @param int    $ttl 设定 X 秒后过期。
     * @return bool
     */
    function expire(string $key, int $ttl) {
        return $this->exec('expire', $key, $ttl);
    }

    /**
     * 设置缓存过期。
     *
     * @param string $key       指定键名。
     * @param int    $timestamp 设定过期的时间戳。
     * @return bool
     */
    function expireAt(string $key, int $timestamp) {
        return $this->exec('expireAt', $key, $timestamp);
    }

    /**
     * 增量操作。
     *
     * @param string $key   指定键名。
     * @param int    $value 指定增量步长值。(默认值: 1)
     * @return int
     */
    function incrBy(string $key, int $value = 1) {
        return $this->exec('incrBy', $key, $value);
    }

    /**
     * 减量操作。
     *
     * @param string $key   指定键名。
     * @param int    $value 指定减量步长值。(默认值: 1)
     * @return int
     */
    function decrBy(string $key, int $value = 1) {
        return $this->exec('incrBy', $key, $value);
    }

    /**
     * 增量操作。
     *
     * @param string $key   缓存键名。
     * @param float  $value 增量步长值。
     * @return float 返回运算后的新值。
     */
    function incrByFloat(string $key, float $value) {
        return $this->exec('incrByFloat', $key, $value);
    }

    /**
     * Redis::hKeys().
     *
     * @param string $key
     * @return array
     */
    function hKeys(string $key) {
        return $this->exec('hKeys', $key);
    }

    /**
     * Redis::hMSet().
     *
     * @param string $key
     * @param array  $values
     * @return bool
     */
    function hMSet(string $key, array $values) {
        return $this->exec('hMSet', $key, $values);
    }

    /**
     * Redis::hMGet().
     *
     * @param string $key
     * @param array  $sub_keys
     * @return array
     */
    function hMGet(string $key, array $sub_keys) {
        return $this->exec('hMGet', $key, $sub_keys);
    }

    /**
     * Redis::hSet().
     *
     * @param string $key
     * @param string $sub_key
     * @param mixed  $value
     * @return int|false
     */
    function hSet(string $key, string $sub_key, $value) {
        return $this->exec('hSet', $key, $sub_key, $value);
    }

    /**
     * Redis::hSetNx().
     *
     * @param string $key
     * @param string $sub_key
     * @param mixed  $value
     * @return bool
     */
    function hSetNx(string $key, string $sub_key, $value) {
        return $this->exec('hSetNx', $key, $sub_key, $value);
    }

    /**
     * Redis::hGet().
     *
     * @param string $key
     * @param string $sub_key
     * @return int|false
     */
    function hGet(string $key, string $sub_key) {
        return $this->exec('hGet', $key, $sub_key);
    }

    /**
     * Redis::hGetAll().
     *
     * @param string $key
     * @return array
     */
    function hGetAll(string $key) {
        return $this->exec('hGetAll', $key);
    }

    /**
     * Redis::hDel().
     *
     * @param string    $key
     * @param \string[] $sub_key
     * @return int
     */
    function hDel(string $key, string ... $sub_key) {
        return $this->exec('hDel', $key, ... $sub_key);
    }

    /**
     * Redis::hExists().
     *
     * @param string $key
     * @param string $sub_key
     * @return bool
     */
    function hExists(string $key, string $sub_key) {
        return $this->exec('hExists', $key, $sub_key);
    }

    /**
     * Redis::hLen().
     *
     * @param string $key
     * @return int
     */
    function hLen(string $key) {
        return $this->exec('hLen', $key);
    }

    /**
     * Redis::hIncrBy().
     *
     * @param string $key
     * @param string $sub_key
     * @param int    $value
     * @return int
     */
    function hIncrBy(string $key, string $sub_key, int $value = 1) {
        return $this->exec('hIncrBy', $key, $sub_key, $value);
    }

    /**
     * Redis::hIncrByFloat().
     *
     * @param string $key
     * @param string $sub_key
     * @param float  $value
     * @return int
     */
    function hIncrByFloat(string $key, string $sub_key, float $value) {
        return $this->exec('hIncrByFloat', $key, $sub_key, $value);
    }

    /**
     * Redis::lPush().
     *
     * @param string     $key
     * @param int|string $value
     * @return int 返回新的 LIST 长度。
     */
    function lPush(string $key, $value) {
        return $this->exec('lPush', $key, $value);
    }

    /**
     * Redis::rPush().
     *
     * @param string     $key
     * @param int|string $value
     * @return int 返回新的 LIST 长度。
     */
    function rPush(string $key, $value) {
        return $this->exec('rPush', $key, $value);
    }

    /**
     * Redis::lPop().
     *
     * @param string $key
     * @return mixed 返回 LIST 中的第一个元素。
     */
    function lPop(string $key) {
        return $this->exec('lPop', $key);
    }

    /**
     * Redis::rPop().
     *
     * @param string $key
     * @return mixed 返回 LIST 中的最后一个元素。
     */
    function rPop(string $key) {
        return $this->exec('rPop', $key);
    }

    /**
     * Redis::lLen().
     *
     * @param string $key
     * @return int 返回 LIST 的长度。
     */
    function lLen(string $key) {
        return $this->exec('lLen', $key);
    }

    /**
     * Redis::lRem().
     *
     * @param string     $key
     * @param int|string $value
     * @param int        $count
     * @return false|int 返回 LIST 的长度。
     */
    function lRem(string $key, $value, int $count = 1) {
        return $this->exec('lRem', $key, $value, $count);
    }

    /**
     * Redis::lRange().
     *
     * @param string $key
     * @param int    $start
     * @param int    $end
     * @return array
     */
    function lRange(string $key, int $start = 0, int $end = -1) {
        return $this->exec('lRange', $key, $start, $end);
    }

    /**
     * Redis::blPop().
     *
     * @param string $key     指定键名。
     * @param int    $timeout 设定阻塞超时时长。(秒)
     * @return array
     */
    function blPop(string $key, int $timeout) {
        return $this->exec('blPop', $key, $timeout);
    }

    /**
     * Redis::brPop().
     *
     * @param string $key     指定键名。
     * @param int    $timeout 设定阻塞超时时长。(秒)
     * @return array
     */
    function brPop(string $key, int $timeout) {
        return $this->exec('brPop', $key, $timeout);
    }

    /**
     * Redis::sAdd().
     *
     * @param string $key    指定键名。
     * @param array  $values 指定一个或多个值。
     * @return array
     */
    function sAdd(string $key, ... $values) {
        return $this->exec('sAdd', $key, ... $values);
    }

    /**
     * Redis::sMembers().
     *
     * @param string $key 指定键名。
     * @return array
     */
    function sMembers(string $key) {
        return $this->exec('sMembers', $key);
    }

    /**
     * Redis::sRem().
     *
     * @param string     $key 指定键名。
     * @param string|int $member
     * @return int
     */
    function sRem(string $key, $member) {
        return $this->exec('sRem', $key, $member);
    }

    /**
     * Redis::sCard().
     *
     * @param string $key 指定键名。
     * @return int 若集合不存在，则返回整数零。
     */
    function sCard(string $key) {
        return $this->exec('sCard', $key);
    }

    /**
     * Redis::sIsMember().
     *
     * @param string     $key 指定键名。
     * @param string|int $member
     * @return bool
     */
    function sIsMember(string $key, $member) {
        return $this->exec('sIsMember', $key, $member);
    }

    /**
     * Redis::zAdd().
     *
     * @param string $key 指定键名。
     * @param int    $score
     * @param string $member
     * @return int
     */
    function zAdd(string $key, int $score, string $member) {
        return $this->exec('zAdd', $key, $score, $member);
    }

    /**
     * Redis::zRem().
     *
     * @param string $key 指定键名。
     * @param string $member
     * @return int
     */
    function zRem(string $key, string $member) {
        return $this->exec('zRem', $key, $member);
    }

    /**
     * Redis::zRange().
     *
     * @param string    $key 指定键名。
     * @param int       $start
     * @param int       $end
     * @param bool|null $withscores
     * @return array
     */
    function zRange(string $key, int $start = 0, int $end = -1, $withscores = NULL) {
        return $this->exec('zRange', $key, $start, $end, $withscores);
    }

    /**
     * Redis::zRevRange().
     *
     * @param string    $key 指定键名。
     * @param int       $start
     * @param int       $end
     * @param bool|null $withscores
     * @return array
     */
    function zRevRange(string $key, int $start = 0, int $end = -1, $withscores = NULL) {
        return $this->exec('zRevRange', $key, $start, $end, $withscores);
    }

    /**
     * Redis::zRangeByScore().
     *
     * @param string $key 指定键名。
     * @param int    $start
     * @param int    $end
     * @param array  $options
     * @return array
     */
    function zRangeByScore(string $key, int $start = 0, int $end = -1, array $options = []) {
        return $this->exec('zRangeByScore', $key, $start, $end, $options);
    }

    /**
     * Redis::zRevRangeByScore().
     *
     * @param string $key 指定键名。
     * @param int    $start
     * @param int    $end
     * @param array  $options
     * @return array
     */
    function zRevRangeByScore(string $key, int $start = 0, int $end = -1, array $options = []) {
        return $this->exec('zRevRangeByScore', $key, $start, $end, $options);
    }

    /**
     * Redis::zRank().
     *
     * @param string $key 指定键名。
     * @param string $member
     * @return int
     */
    function zRank(string $key, string $member) {
        return $this->exec('zRank', $key, $member);
    }

    /**
     * Redis::zRevRank().
     *
     * @param string $key 指定键名。
     * @param string $member
     * @return int
     */
    function zRevRank(string $key, string $member) {
        return $this->exec('zRevRank', $key, $member);
    }

    /**
     * Redis::zCount().
     *
     * @param string $key 指定键名。
     * @param int    $start
     * @param int    $end
     * @return int
     */
    function zCount(string $key, int $start, int $end) {
        return $this->exec('zCount', $key, $start, $end);
    }

    /**
     * Redis::zCard().
     *
     * @param string $key 指定键名。
     * @return int
     */
    function zCard(string $key) {
        return $this->exec('zCard', $key);
    }

    /**
     * Redis::zScore().
     *
     * @param string $key 指定键名。
     * @param string $member
     * @return int
     */
    function zScore(string $key, string $member) {
        return $this->exec('zScore', $key, $member);
    }

    /**
     * 获取一个或多个缓存对象。
     *
     * @param string ...$keys
     * @return array
     */
    function gets(string ...$keys) {
        return $this->exec('mGet', $keys);
    }

    /**
     * 查询过期时间。
     *
     * @param string $key
     * @return int
     */
    function ttl(string $key) {
        return $this->exec('ttl', $key);
    }
}