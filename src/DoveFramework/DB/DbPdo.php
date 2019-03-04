<?php
namespace DoveFramework\DB;

use DoveFramework\Bootstrap\AbstractBootstrap;
use DoveFramework\Context\AbstractBase;
use DoveFramework\Exceptions\NonImplementedException;
use DoveFramework\Exceptions\SQLException;
use DoveFramework\Helper\Util;
use DoveFramework\Interfaces\IDb;
use DoveFramework\SYS;

/**
 * 数据库 PDO 管理器。
 *
 * @package       DoveFramework\DB
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
class DbPdo extends AbstractBase implements IDb {
    /**
     * PDO 对象。
     *
     * @var \PDO
     */
    private $dbo = NULL;

    /**
     * 连接参数对象。
     *
     * @var DbParameter
     */
    private $dbParameter = NULL;

    /**
     * 是否已连接？
     *
     * @var bool
     */
    private $connected = false;

    /**
     * 是否自动提交事务？
     *
     * @var bool
     */
    private $auto_commit = true;

    /**
     * 是否断开后自动重新连接？
     *
     * @var bool
     */
    private $auto_reconnect = false;

    /**
     * 事务计数器值。
     *
     * @var int
     */
    private $transaction_count = 0;

    /**
     * 已重连的次数。
     *
     * @var int
     */
    private $reconnect_times = 0;

    /**
     * 构造函数。
     *
     * @param AbstractBootstrap $bootstrap
     * @param DbParameter       $dbParameter
     */
    public function __construct(AbstractBootstrap $bootstrap, DbParameter $dbParameter) {
        parent::__construct($bootstrap);

        $this->dbParameter = $dbParameter;
    }

    /**
     * 连接数据库。
     */
    function connect() {
        if (!$this->connected) {
            $options = [
                \PDO::ATTR_ERRMODE                  => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_STRINGIFY_FETCHES        => false,
                \PDO::ATTR_AUTOCOMMIT               => $this->auto_commit,
                \PDO::ATTR_CASE                     => \PDO::CASE_NATURAL,
                \PDO::ATTR_DEFAULT_FETCH_MODE       => \PDO::FETCH_ASSOC,
                \PDO::MYSQL_ATTR_INIT_COMMAND       => 'SET NAMES `' . $this->dbParameter->getCharset() . '`',
                \PDO::ATTR_PERSISTENT               => false,
                \PDO::ATTR_EMULATE_PREPARES         => false,
                \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false,
            ];

            if ($this->dbParameter->hasUnixSocket()) {
                $dsn = $this->dbParameter->getDriver() . ':dbname=' . $this->dbParameter->getDbname() . ';unix_socket=' . $this->dbParameter->getUnixSocket();
            } else {
                $dsn = $this->dbParameter->getDriver() . ':dbname=' . $this->dbParameter->getDbname() . ';host=' . $this->dbParameter->getHost() . ';port=' . $this->dbParameter->getPort();
            }

            try {
                $this->dbo = new \PDO($dsn, $this->dbParameter->getUser(), $this->dbParameter->getPass(), $options);

                if ($this->dbParameter->enableIdleTimeout()) {
                    $this->dbo->exec(($this->dbParameter->hasIoslationLevel() ? 'SET SESSION TRANSACTION ISOLATION LEVEL ' . $this->dbParameter->getIoslationLevel() . ';' : '') . "SET SESSION wait_timeout = " . $this->dbParameter->getTimeout() . ", interactive_timeout = " . $this->dbParameter->getTimeout() . ";");

                    if ($this->bootstrap->cfg->isDebug())
                        $this->bootstrap->logger->debug('[DbPdo] 已连接 DB 主机. (TIMEOUT: ' . $this->dbParameter->getTimeout() . ')');
                } else {
                    $this->dbo->exec(($this->dbParameter->hasIoslationLevel() ? 'SET SESSION TRANSACTION ISOLATION LEVEL ' . $this->dbParameter->getIoslationLevel() . ';' : '') . "SET SESSION wait_timeout = 2592000, interactive_timeout = 2592000;");

                    if ($this->bootstrap->cfg->isDebug())
                        $this->bootstrap->logger->debug('[DbPdo] 已连接 DB 主机. (TIMEOUT: 30 days)');
                }
            } catch (\PDOException $ex) {
                throw $ex;
            }

            $this->connected = true;
        }
    }

    /**
     * 关闭数据库。
     */
    function close() {
        if ($this->dbo)
            $this->dbo = NULL;

        $this->connected         = false;
        $this->transaction_count = 0;
    }

    /**
     * 切换数据库上下文。
     *
     * @param string $db 指定数据库名称。
     */
    function useDb(string $db) {
        if (!$this->connected)
            $this->connect();

        $this->dbo->exec('USE `' . $db . '`');
    }

    /**
     * 开启事务。
     *
     * @return bool
     */
    function begin() {
        if (!$this->connected)
            $this->connect();

        if (0 === $this->transaction_count) {
            $this->dbo->beginTransaction();
        }

        $this->transaction_count++;

        return true;
    }

    /**
     * 提交事务。
     *
     * @return bool
     */
    function commit() {
        $this->transaction_count--;

        if (0 === $this->transaction_count) {
            $this->dbo->commit();
        }

        return true;
    }

    /**
     * 回滚事务。(注: 当事务计数器不等于 1 时, 此方法返回 False 值。)
     *
     * @return bool
     */
    function rollback() {
        $this->transaction_count--;

        if (0 === $this->transaction_count) {
            $this->dbo->rollBack();
        }

        return true;
    }

    /**
     * 读取一行记录。
     *
     * @param string $sql    指定 SQL 指令。
     * @param array  $params 预编译参数列表。
     * @return array|bool
     * @throws \Exception
     */
    function fetch($sql, $params = NULL) {
        $d = $this->execute($sql, $params, SYS::SQL_TYPE_FETCH);

        return $d;
    }

    /**
     * 读取全部记录。
     *
     * @param string $sql    指定 SQL 指令。
     * @param array  $params 预编译参数列表。
     * @return array|bool
     * @throws \Exception
     */
    function fetchAll($sql, $params = NULL) {
        $d = $this->execute($sql, $params, SYS::SQL_TYPE_FETCH_ALL);

        return $d;
    }

    /**
     * 读取单行单列数据。
     *
     * @param string $sql    指定 SQL 指令。
     * @param array  $params 预编译参数列表。
     * @param int    $column 指定读取第一行中的第几列。
     * @return int|string
     * @throws \Exception
     */
    function scalar($sql, $params = NULL, $column = 0) {
        $d = $this->execute($sql, $params, SYS::SQL_TYPE_SCALAR);

        return $d;
    }

    /**
     * 执行一条或多条 SQL 更新指令并返回影响行数。(例如: DDL/INSERT/UPDATE/DELETE/CREATE/ALTER/DROP ...)
     *
     * @param string|array $sql 指定一条(组) SQL 指令。
     * @return int              更新影响的行数。
     * @throws SQLException
     */
    function exec($sql) {
        if (!$this->connected)
            $this->connect();

        $affected = 0;

        if (is_array($sql)) {
            foreach ($sql as $v) {
                if (!empty($v))
                    $affected += $this->_exec($v);
            }
        } else {
            $affected = $this->_exec($sql);
        }

        return $affected;
    }

    /**
     * 内部执行方法。
     *
     * @param string $sql
     * @return int
     * @throws SQLException
     */
    private function _exec($sql) {
        if (!$this->connected)
            $this->connect();

        // 日志追踪 ...
        if ($this->bootstrap->cfg->isDebug())
            $this->bootstrap->logger->trace('[SQL] ' . $sql);

        try {
            $affected = $this->dbo->exec($sql);

            return $affected;
        } catch (\PDOException $ex) {
            // 检测是否支持自动重连机制？
            if ($this->auto_reconnect) {
                if (Util::contains($ex->getMessage(), ['server has gone away', 'no connection to the server', 'Lost connection', 'is dead or not enabled', 'Error while sending'])) {
                    $this->bootstrap->logger->warn('检测到 PDO 连接已断开, 已启用自动重连!');

                    $this->reconnect_times++;

                    $this->close();

                    return $this->_exec($sql);
                }
            } else {
                throw new SQLException($ex->getMessage() . ' ([SQL]: ' . $sql . ')', $ex->getCode());
            }
        }

        return false;
    }

    /**
     * 执行 SQL 查询并返回生成器。
     *
     * @param string     $sql    指定 SQL 指令。
     * @param array|NULL $params 指定参数列表。
     * @return \Generator
     */
    function each(string $sql, array $params = NULL): \Generator {
        if (!$this->connected)
            $this->connect();

        if (!$params) {
            $sth = $this->dbo->query($sql);
        } else {
            $sth = $this->dbo->prepare($sql);

            foreach ($params as $k => $v) {
                if (is_array($v))
                    $sth->bindValue(1 + $k, $v[0], $v[1]);
                else
                    $sth->bindValue(1 + $k, $v);
            }

            $sth->execute();
        }

        while ($row = $sth->fetch(\PDO::FETCH_ASSOC)) {
            yield $row;
        }

        $sth->closeCursor();
    }

    /**
     * 执行预编译 SQL 指令。
     *
     * @param string $sql      指定需要执行的 SQL 指令。
     * @param array  $params   指定参数列表。
     * @param int    $sql_type 指定 SQL 类型。(注: 务必使用 SYS::SQL_TYPE_* 常量定义)
     * @return mixed
     * @throws \Exception
     */
    function execute($sql, $params = NULL, $sql_type = SYS::SQL_TYPE_UPDATE) {
        if (!$this->connected)
            $this->connect();

        $sth = NULL;
        $r   = false;

        try {
            $sth = $this->dbo->prepare($sql);

            if (!empty($params)) {
                foreach ($params as $k => $v) {
                    if (is_array($v))
                        $sth->bindValue(1 + $k, $v[0], $v[1]);
                    else
                        $sth->bindValue(1 + $k, $v);
                }
            }

            // 日志追踪 ...
            if ($this->bootstrap->cfg->isDebug())
                $this->bootstrap->logger->trace('[SQL] ' . $sql . ' [Parameters] ' . json_encode($params));

            $sth->execute();

            switch ($sql_type) {
                case SYS::SQL_TYPE_INSERT:
                    $r = $this->dbo->lastInsertId();
                    break;
                case SYS::SQL_TYPE_UPDATE:
                    $r = $sth->rowCount();
                    break;
                case SYS::SQL_TYPE_DELETE:
                    $r = $sth->rowCount();
                    break;
                case SYS::SQL_TYPE_FETCH:
                    $r = $sth->fetch();
                    break;
                case SYS::SQL_TYPE_FETCH_ALL:
                    $r = $sth->fetchAll();
                    break;
                case SYS::SQL_TYPE_SCALAR:
                    $r = $sth->fetchColumn();
                    break;
                default:
                    throw new NonImplementedException('检测到无效的 SQL 类型。');
            }
        } catch (\Exception $ex) {
            // 检测是否支持自动重连机制？
            if (Util::contains($ex->getMessage(), ['server has gone away', 'no connection to the server', 'Lost connection', 'is dead or not enabled', 'Error while sending']) && $this->auto_reconnect) {
                $this->bootstrap->logger->warn('检测到 MySQL 连接断开, 已重新连接并执行上一条未成功的 SQL 查询! (' . $sql . ')');

                $this->reconnect_times++;

                $this->close();

                return $this->execute($sql, $params, $sql_type);
            } else {
                throw $ex;
            }
        } finally {
            if ($sth instanceof \PDOStatement)
                $sth->closeCursor();

            $sth = NULL;
        }

        return $r;
    }

    /**
     * 设置 DB 连接参数对象。
     *
     * @param DbParameter $dbParameter
     * @return IDb
     */
    function setDbParameter(DbParameter $dbParameter) {
        $this->dbParameter = $dbParameter;

        return $this;
    }

    /**
     * 设置 DB 自动重新连接开关。
     *
     * @param $auto_reconnect
     * @return IDb
     */
    function setAutoReconnect($auto_reconnect) {
        $this->auto_reconnect = $auto_reconnect;

        return $this;
    }

    /**
     * 获取事务计数器值。
     *
     * @return int
     */
    function getTransactionCount() {
        return $this->transaction_count;
    }

    /**
     * 获取自动重连的次数。
     *
     * @return int
     */
    function getReconnectTimes() {
        return $this->getReconnectTimes();
    }

    /**
     * 获取当前客户端连接 ID。
     *
     * @return int
     * @throws \Exception
     */
    function getConnectionId() {
        return ( int ) $this->scalar("SELECT CONNECTION_ID()");
    }

    /**
     * 获取状态信息。
     *
     * @return array
     */
    function getStatusInfos() {
        if (!$this->connected)
            $this->connect();

        $attrs = array(
            "AUTOCOMMIT",
            "ERRMODE",
            "CASE",
            "CLIENT_VERSION",
            "CONNECTION_STATUS",
            "ORACLE_NULLS",
            "PERSISTENT",
            "PREFETCH",
            "SERVER_INFO",
            "SERVER_VERSION",
            "TIMEOUT",
        );

        $d = [];

        foreach ($attrs as $v) {
            try {
                $d[$v] = $this->dbo->getAttribute(constant("PDO::ATTR_$v"));
            } catch (\Exception $ex) {
            }
        }

        $d['CONNECTION_ID'] = ( int ) $this->dbo->query("SELECT CONNECTION_ID()")->fetchColumn(0);

        return $d;
    }

    /**
     * 为 SQL 查询里的字符串添加引号。（注：不是所有的 PDO 驱动都实现了此功能（例如 PDO_ODBC）。 考虑使用 prepare 代替。）
     *
     * @param string $str            要添加引号的字符串。
     * @param int    $parameter_type 为驱动提示数据类型，以便选择引号风格。
     * @return string
     */
    function quote($str, $parameter_type = \PDO::PARAM_STR) {
        if (!$this->connected)
            $this->connect();

        return $this->dbo->quote($str, $parameter_type);
    }

    /**
     * 获取 PDO 实例。
     *
     * @return \PDO
     */
    function getDbo(): \PDO {
        if (!$this->connected)
            $this->connect();

        return $this->dbo;
    }
}