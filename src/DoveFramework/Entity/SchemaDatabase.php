<?php
namespace DoveFramework\Entity;

use DoveFramework\Core\ObjectSerializable;

/**
 * SchemaDatabase for MySQL.
 *
 * @package       DoveFramework\Entity
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
final class SchemaDatabase extends ObjectSerializable {
    protected $name = '';

    protected $character_set_name = '';

    protected $collation_name = '';

    /**
     * 构造函数。
     *
     * @param string $name               数据库名称。
     * @param string $character_set_name 字符集名称。
     * @param string $collation_name     排序规则名称。
     */
    function __construct(string $name, string $character_set_name, string $collation_name) {
        $this->name               = $name;
        $this->character_set_name = $character_set_name;
        $this->collation_name     = $collation_name;
    }

    /**
     * 获取数据库名称。
     *
     * @return string
     */
    function getName(): string {
        return $this->name;
    }

    /**
     * 获取字符集名称。
     *
     * @return string
     */
    function getCharacterSetName(): string {
        return $this->character_set_name;
    }

    /**
     * 获取排序规则名称。
     *
     * @return string
     */
    function getCollationName(): string {
        return $this->collation_name;
    }
}