<?php
namespace DoveFramework\Entity;

use DoveFramework\Core\ObjectSerializable;

/**
 * SchemaColumn for MySQL.
 *
 * @package       DoveFramework\Entity
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
final class SchemaColumn extends ObjectSerializable implements \ArrayAccess {
    protected $columnName = '';

    protected $ordinalPosition = 0;

    protected $columnDefault = '';

    protected $nullable = true;

    protected $dataType = '';

    protected $characterSetName = '';

    protected $collationName = '';

    protected $columnType = '';

    protected $columnKey = '';

    protected $extra = '';

    protected $comment = '';

    protected $priamy = false;

    /**
     * 获取列名。
     *
     * @return string
     */
    function getColumnName() {
        return $this->columnName;
    }

    /**
     * 设置列名。
     *
     * @param string $columnName
     * @return SchemaColumn
     */
    function setColumnName($columnName) {
        $this->columnName = $columnName;

        return $this;
    }

    /**
     * 获取列位置。
     *
     * @return int
     */
    function getOrdinalPosition() {
        return $this->ordinalPosition;
    }

    /**
     * 设置列位置。
     *
     * @param int $ordinalPosition
     * @return SchemaColumn
     */
    function setOrdinalPosition($ordinalPosition) {
        $this->ordinalPosition = $ordinalPosition;

        return $this;
    }

    /**
     * 获取列缺省值。
     *
     * @return string
     */
    function getColumnDefault() {
        return $this->columnDefault;
    }

    /**
     * 设置列缺省值。
     *
     * @param string $columnDefault
     * @return SchemaColumn
     */
    function setColumnDefault($columnDefault) {
        $this->columnDefault = $columnDefault;

        return $this;
    }

    /**
     * 指示是否允许空？
     *
     * @return boolean
     */
    function isNullable() {
        return $this->nullable;
    }

    /**
     * 指示是否允许空？
     *
     * @param boolean $nullable
     * @return SchemaColumn
     */
    function setNullable($nullable) {
        $this->nullable = $nullable;

        return $this;
    }

    /**
     * 获取列数据类型。
     *
     * @return string
     */
    function getDataType() {
        return $this->dataType;
    }

    /**
     * 设置列数据类型。
     *
     * @param string $dataType
     * @return SchemaColumn
     */
    function setDataType($dataType) {
        $this->dataType = $dataType;

        return $this;
    }

    /**
     * 获取列使用的字符集名称。
     *
     * @return string
     */
    function getCharacterSetName() {
        return $this->characterSetName;
    }

    /**
     * 设置列使用的字符集名称。
     *
     * @param string $characterSetName
     * @return SchemaColumn
     */
    function setCharacterSetName($characterSetName) {
        $this->characterSetName = $characterSetName;

        return $this;
    }

    /**
     * 获取列排序规则名称。
     *
     * @return string
     */
    function getCollationName() {
        return $this->collationName;
    }

    /**
     * 设置列排序规则名称。
     *
     * @param string $collationName
     * @return SchemaColumn
     */
    function setCollationName($collationName) {
        $this->collationName = $collationName;

        return $this;
    }

    /**
     * 获取列类型、长度描述。
     *
     * @return string
     */
    function getColumnType() {
        return $this->columnType;
    }

    /**
     * 设置列类型、长度描述。
     *
     * @param string $columnType
     * @return SchemaColumn
     */
    function setColumnType($columnType) {
        $this->columnType = $columnType;

        return $this;
    }

    /**
     * 获取列键名称。
     *
     * @return string
     */
    function getColumnKey() {
        return $this->columnKey;
    }

    /**
     * 设置列键名称。
     *
     * @param string $columnKey
     * @return SchemaColumn
     */
    function setColumnKey($columnKey) {
        $this->columnKey = $columnKey;

        return $this;
    }

    /**
     * 获取扩展信息。
     *
     * @return string
     */
    function getExtra() {
        return $this->extra;
    }

    /**
     * 设置扩展信息。
     *
     * @param string $extra
     * @return SchemaColumn
     */
    function setExtra($extra) {
        $this->extra = $extra;

        return $this;
    }

    /**
     * 获取列注释。
     *
     * @return string
     */
    function getComment() {
        return $this->comment;
    }

    /**
     * 设置列注释。
     *
     * @param string $comment
     * @return SchemaColumn
     */
    function setComment($comment) {
        $this->comment = $comment;

        return $this;
    }

    /**
     * 指示是否主键字段？
     *
     * @return boolean
     */
    function isPriamy() {
        return $this->priamy;
    }

    /**
     * 设置主键标识。
     *
     * @param boolean $priamy
     * @return SchemaColumn
     */
    function setPriamy($priamy) {
        $this->priamy = $priamy;

        return $this;
    }

    /**
     * Whether a offset exists
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     * @return boolean true on success or false on failure.
     *                      </p>
     *                      <p>
     *                      The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    function offsetExists($offset) {
        return property_exists($this, $offset);
    }

    /**
     * Offset to retrieve
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    function offsetGet($offset) {
        return property_exists($this, $offset) ? $this->$offset : NULL;
    }

    /**
     * Offset to set
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     * @return void
     * @since 5.0.0
     */
    function offsetSet($offset, $value) {
        $this->$offset = $value;
    }

    /**
     * Offset to unset
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     * @return void
     * @since 5.0.0
     */
    function offsetUnset($offset) {
    }
}