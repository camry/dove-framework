<?php
namespace DoveFramework\Interfaces;

use DoveFramework\DB\DbParameter;
use DoveFramework\SYS;

/**
 * IDb 数据库接口。
 *
 * @package       DoveFramework\Interfaces
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
interface IDb {
    /**
     * 连接数据库。
     */
    function connect();

    /**
     * 关闭数据库。
     */
    function close();

    /**
     * 切换数据库上下文。
     *
     * @param string $db 指定数据库名称。
     */
    function useDb(string $db);

    /**
     * 开启事务。(注: 当事务计数器非零时, 此方法返回 False 值。)
     *
     * @return bool
     */
    function begin();

    /**
     * 提交事务。(注: 当事务计数器不等于 1 时, 此方法返回 False 值。)
     *
     * @return bool
     */
    function commit();

    /**
     * 回滚事务。(注: 当事务计数器不等于 1 时, 此方法返回 False 值。)
     *
     * @return bool
     */
    function rollback();

    /**
     * 读取一行记录。
     *
     * @param string $sql    指定 SQL 指令。
     * @param array  $params 预编译参数列表。
     * @return array|bool
     */
    function fetch($sql, $params = NULL);

    /**
     * 读取全部记录。
     *
     * @param string $sql    指定 SQL 指令。
     * @param array  $params 预编译参数列表。
     * @return array|bool
     */
    function fetchAll($sql, $params = NULL);

    /**
     * 读取单行单列数据。
     *
     * @param string $sql    指定 SQL 指令。
     * @param array  $params 预编译参数列表。
     * @param int    $column 指定读取第一行中的第几列。
     * @return int|string
     */
    function scalar($sql, $params = NULL, $column = 0);

    /**
     * 执行一条或多条 SQL 更新指令并返回影响行数。(例如: DDL/INSERT/UPDATE/DELETE/CREATE/ALTER/DROP ...)
     *
     * @param string|array $sql 指定一条(组) SQL 指令。
     * @return int              更新影响的行数。
     */
    function exec($sql);

    /**
     * 执行 SQL 查询并返回生成器。
     *
     * @param string     $sql    指定 SQL 指令。
     * @param array|NULL $params 指定参数列表。
     * @return \Generator
     */
    function each(string $sql, array $params = NULL): \Generator;

    /**
     * 执行预编译 SQL 指令。
     *
     * @param string $sql      指定需要执行的 SQL 指令。
     * @param array  $params   指定参数列表。
     * @param int    $sql_type 指定 SQL 类型。(注: 务必使用 SYS::SQL_TYPE_* 常量定义)
     * @return mixed
     */
    function execute($sql, $params = NULL, $sql_type = SYS::SQL_TYPE_UPDATE);

    /**
     * 设置 DB 连接参数对象。
     *
     * @param DbParameter $dbParameter
     * @return IDb
     */
    function setDbParameter(DbParameter $dbParameter);

    /**
     * 设置 DB 自动重新连接开关。
     *
     * @param $auto_reconnect
     * @return IDb
     */
    function setAutoReconnect($auto_reconnect);

    /**
     * 获取事务计数器值。
     *
     * @return int
     */
    function getTransactionCount();

    /**
     * 获取自动重连的次数。
     *
     * @return int
     */
    function getReconnectTimes();

    /**
     * 获取当前客户端连接 ID。
     *
     * @return int
     */
    function getConnectionId();

    /**
     * 获取状态信息。
     *
     * @return array
     */
    function getStatusInfos();

    /**
     * 为 SQL 查询里的字符串添加引号。（注：不是所有的 PDO 驱动都实现了此功能（例如 PDO_ODBC）。 考虑使用 prepare 代替。）
     *
     * @param string $str            要添加引号的字符串。
     * @param int    $parameter_type 为驱动提示数据类型，以便选择引号风格。
     * @return string
     */
    function quote($str, $parameter_type = \PDO::PARAM_STR);

    /**
     * 获取 PDO 实例。
     *
     * @return \PDO
     */
    function getDbo(): \PDO;
}