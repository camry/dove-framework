<?php
namespace DoveFramework\DB;

use DoveFramework\Entity\SchemaColumn;
use DoveFramework\Entity\SchemaDatabase;
use DoveFramework\Entity\SchemaTable;
use DoveFramework\Interfaces\IDb;

/**
 * DbSchemaManager for MySQL.
 *
 * @package       DoveFramework\DB
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
class DbSchemaManager {
    /**
     * IDb 接口。
     *
     * @var IDb
     */
    protected $dbo = NULL;

    /**
     * 构造函数。
     *
     * @param IDb $dbo
     */
    function __construct(IDb $dbo) {
        $this->dbo = $dbo;
    }

    /**
     * 析构函数。
     */
    function __destruct() {
        unset($this->dbo);
    }

    /**
     * 静态创建 DbSchemaManager 实例。
     *
     * @param IDb $dbo
     * @return DbSchemaManager
     */
    static function create(IDb $dbo) {
        return new self($dbo);
    }

    /**
     * 获取数据库对象 <SchemaDatabase[]> 列表。
     *
     * @return SchemaDatabase[]
     */
    function getSchemaDatabases() {
        $dr = [];

        $ignore_databases = ['information_schema', 'mysql', 'performance_schema', 'sys'];

        $d = $this->dbo->fetchAll("SELECT * FROM `information_schema`.`SCHEMATA`");

        if ($d) {
            foreach ($d as $v) {
                if (!in_array($v['SCHEMA_NAME'], $ignore_databases, true)) {
                    $dr[] = new SchemaDatabase($v['SCHEMA_NAME'], $v['DEFAULT_CHARACTER_SET_NAME'], $v['DEFAULT_COLLATION_NAME']);
                }
            }
        }

        return $dr;
    }

    /**
     * 获取数据表对象 <SchemaTable[]> 列表。
     *
     * @param string $database 数据库名称。
     * @param array  $options  参数选项。
     * @return SchemaTable[]
     */
    function getSchemaTables($database, $options = NULL) {
        $dr   = [];
        $opts = array(
            'ignore_first_underline' => false, // 生成 Pascal 名称时是否忽略第一个下划线分隔的字符串？
        );
        if (is_array($options))
            $opts = array_merge($opts, $options);

        $d1 = $this->dbo->fetchAll("SELECT * FROM `information_schema`.`TABLES` WHERE TABLE_SCHEMA = '" . $database . "' ORDER BY `TABLE_NAME` ASC");

        if ($d1) {
            foreach ($d1 as $v) {
                $k = $v['TABLE_NAME'];

                $dr[$k] = new SchemaTable($v['TABLE_NAME'], $v['ENGINE'], $v['ROW_FORMAT'], $v['TABLE_ROWS'], $v['AUTO_INCREMENT'], $v['TABLE_COLLATION'], $v['TABLE_COMMENT'], $options);
            }
        }

        $d2 = $this->dbo->fetchAll("SELECT * FROM `information_schema`.`COLUMNS` WHERE TABLE_SCHEMA = '" . $database . "' ORDER BY `TABLE_NAME` ASC, `ORDINAL_POSITION` ASC");

        if ($d2) {
            foreach ($d2 as $v) {
                $k = $v['TABLE_NAME'];

                if (isset($dr[$k])) {
                    $obc = new SchemaColumn();
                    $obc->setColumnName($v['COLUMN_NAME'])
                        ->setOrdinalPosition($v['ORDINAL_POSITION'])
                        ->setColumnDefault($v['COLUMN_DEFAULT'])
                        ->setNullable($v['IS_NULLABLE'])
                        ->setDataType($v['DATA_TYPE'])
                        ->setCharacterSetName($v['CHARACTER_SET_NAME'])
                        ->setCollationName($v['COLLATION_NAME'])
                        ->setColumnType($v['COLUMN_TYPE'])
                        ->setColumnKey($v['COLUMN_KEY'])
                        ->setExtra($v['EXTRA'])
                        ->setComment($v['COLUMN_COMMENT']);

                    if (0 === strcmp('PRI', $v['COLUMN_KEY'])) {
                        $obc->setPriamy(true);
                        $dr[$k]->setPriamy($v['COLUMN_NAME']);
                    }

                    $dr[$k]->addColumn($obc);
                }
            }
        }

        return $dr;
    }
}