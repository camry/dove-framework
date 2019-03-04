<?php
namespace DoveFramework\Entity;

use DoveFramework\Core\ObjectSerializable;

/**
 * SchemaTable for MySQL.
 *
 * @package       DoveFramework\Entity
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
final class SchemaTable extends ObjectSerializable {
    protected $name = '';

    protected $engine = '';

    protected $rowFormat = '';

    protected $tableRows = 0;

    protected $autoIncrement = 0;

    protected $tableCollation = '';

    protected $tableComment = '';

    protected $priamy = '';

    protected $pascalName = '';

    protected $camelName = '';

    protected $columns = [];

    /**
     * 构造函数。
     *
     * @param string $name           数据表名。
     * @param string $engine         表引擎名称。
     * @param string $rowFormat      数据行格式。
     * @param int    $tableRows      数据行数。
     * @param int    $autoIncrement  自动增量。
     * @param string $tableCollation 排序规则名称。
     * @param string $tableComment   注释信息。
     * @param array  $options        参数选项。
     */
    function __construct($name, $engine, $rowFormat, $tableRows, $autoIncrement, $tableCollation, $tableComment, $options = []) {
        $this->name           = $name;
        $this->engine         = $engine;
        $this->rowFormat      = $rowFormat;
        $this->tableRows      = $tableRows;
        $this->autoIncrement  = $autoIncrement;
        $this->tableCollation = $tableCollation;
        $this->tableComment   = $tableComment;
        $this->camelName      = $this->toCamelCase($name);

        if (isset($options['ignore_first_underline']) && true === $options['ignore_first_underline']) {
            $this->pascalName = $this->toCamelCase(preg_replace('/^[a-z]+_/i', '', $name), true);
        } else {
            $this->pascalName = $this->toCamelCase($name, true);
        }
    }

    /**
     * 获取 <SchemaColumn[]> 数据列对象列表。
     *
     * @return SchemaColumn[]
     */
    function getColumns() {
        return $this->columns;
    }

    /**
     * 添加 SchemaColumn 对象。
     *
     * @param SchemaColumn $column
     * @return SchemaTable
     */
    function addColumn(SchemaColumn $column) {
        $this->columns[] = $column;

        return $this;
    }

    private function toCamelCase($string, $first_char_caps = false) {
        if ($first_char_caps == true) {
            $string[0] = strtoupper($string[0]);
        } else {
            $string[0] = strtolower($string[0]);
        }

        $func = function ($c) {
            return strtoupper($c[1]);
        };

        return preg_replace_callback('/[_-]([a-z])/', $func, $string);
    }

    /**
     * 获取数据表名称。
     *
     * @return string
     */
    function getName() {
        return $this->name;
    }

    /**
     * 获取数据表引擎名称。
     *
     * @return string
     */
    function getEngine() {
        return $this->engine;
    }

    /**
     * 获取行格式。
     *
     * @return string
     */
    function getRowFormat() {
        return $this->rowFormat;
    }

    /**
     * 获取数据行数。
     *
     * @return int
     */
    function getTableRows() {
        return $this->tableRows;
    }

    /**
     * 获取自动增量。
     *
     * @return int
     */
    function getAutoIncrement() {
        return $this->autoIncrement;
    }

    /**
     * 获取数据表排序规则名称。
     *
     * @return string
     */
    function getTableCollation() {
        return $this->tableCollation;
    }

    /**
     * 获取数据表描述。
     *
     * @return string
     */
    function getTableComment() {
        return $this->tableComment;
    }

    /**
     * 获取主键字段名称。
     *
     * @return string
     */
    function getPriamy() {
        return $this->priamy;
    }

    /**
     * 设置主键字段名称。
     *
     * @param string $priamy
     * @return SchemaTable
     */
    function setPriamy($priamy) {
        $this->priamy = $priamy;

        return $this;
    }

    /**
     * @return string
     */
    function getPascalName() {
        return $this->pascalName;
    }

    /**
     * @return string
     */
    function getCamelName() {
        return $this->camelName;
    }
}