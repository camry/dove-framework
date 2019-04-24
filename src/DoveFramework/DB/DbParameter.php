<?php
namespace DoveFramework\DB;

/**
 * DB 连接参数对象。
 *
 * @package       DoveFramework\DB
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
class DbParameter {
    /**
     * 事务隔离级别。
     */
    const ISOLATION_REPEATABLE_READ  = 'REPEATABLE READ';
    const ISOLATION_READ_COMMITTED   = 'READ COMMITTED';
    const ISOLATION_READ_UNCOMMITTED = 'READ UNCOMMITTED';
    const ISOLATION_SERIALIZABLE     = 'SERIALIZABLE';

    /**
     * 数据库驱动名。（例如：mysql,pgsql,oci）
     *
     * @var string
     */
    private $driver = 'mysql';

    /**
     * 主机地址。
     *
     * @var string
     */
    private $host = '127.0.0.1';

    /**
     * 端口。
     *
     * @var int
     */
    private $port = 3306;

    /**
     * 用户名。
     *
     * @var string
     */
    private $user = 'root';

    /**
     * 密码。
     *
     * @var string
     */
    private $pass = '';

    /**
     * 默认连接的数据库。
     *
     * @var string
     */
    private $dbname = 'test';

    /**
     * 字符集。
     *
     * @var string
     */
    private $charset = 'utf8';

    /**
     * UNIX 套接字文件路径。
     *
     * @var string
     */
    private $unix_socket = '';

    /**
     * 事务隔离级别。
     *
     * @var string
     */
    private $ioslation_level = '';

    /**
     * 连接空闲超时。（秒）
     *
     * @var int
     */
    private $timeout = 0;

    /**
     * 构造函数。
     *
     * @param string $driver          数据库驱动名。（例如：mysql,pgsql,oci）
     * @param string $host            主机地址。
     * @param int    $port            端口。
     * @param string $user            用户名。
     * @param string $pass            密码。
     * @param string $dbname          默认连接的数据库。
     * @param string $charset         字符集。
     * @param string $unix_socket     UNIX 套接字文件路径。
     * @param string $ioslation_level 事务隔离级别。（默认：READ COMMITTED）
     * @param int    $timeout         连接空闲超时。（秒）
     */
    public function __construct(string $driver, string $host, int $port, string $user, string $pass, string $dbname, string $charset, string $unix_socket, string $ioslation_level = self::ISOLATION_READ_COMMITTED, int $timeout = 0) {
        $this->driver          = $driver;
        $this->host            = $host;
        $this->port            = $port;
        $this->user            = $user;
        $this->pass            = $pass;
        $this->dbname          = $dbname;
        $this->charset         = $charset;
        $this->unix_socket     = $unix_socket;
        $this->ioslation_level = $ioslation_level;
        $this->timeout         = $timeout;
    }

    /**
     * 获取数据库驱动名。（例如：mysql,pgsql,oci）
     *
     * @return string
     */
    public function getDriver(): string {
        return $this->driver;
    }

    /**
     * 获取主机地址。
     *
     * @return string
     */
    public function getHost(): string {
        return $this->host;
    }

    /**
     * 获取端口。
     *
     * @return int
     */
    public function getPort(): int {
        return $this->port;
    }

    /**
     * 获取用户名。
     *
     * @return string
     */
    public function getUser(): string {
        return $this->user;
    }

    /**
     * 获取密码。
     *
     * @return string
     */
    public function getPass(): string {
        return $this->pass;
    }

    /**
     * 获取默认连接的数据库。
     *
     * @return string
     */
    public function getDbname(): string {
        return $this->dbname;
    }

    /**
     * 获取字符集。
     *
     * @return string
     */
    public function getCharset(): string {
        return $this->charset;
    }

    /**
     * 检查是否已设置 UNIX 套接字文件路径？
     *
     * @return bool
     */
    public function hasUnixSocket(): bool {
        return !empty($this->_unix_socket);
    }

    /**
     * 获取 UNIX 套接字文件路径。
     *
     * @return string
     */
    public function getUnixSocket(): string {
        return $this->unix_socket;
    }

    /**
     * 检查是否已设置事务隔离级别？
     *
     * @return bool
     */
    public function hasIoslationLevel(): bool {
        return !empty($this->ioslation_level);
    }

    /**
     * 获取事务隔离级别。
     *
     * @return string
     */
    public function getIoslationLevel(): string {
        return $this->ioslation_level;
    }

    /**
     * 获取连接空闲超时。（秒）
     *
     * @return int
     */
    public function getTimeout(): int {
        return $this->timeout;
    }
}