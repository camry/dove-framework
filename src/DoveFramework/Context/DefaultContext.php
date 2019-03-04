<?php
namespace DoveFramework\Context;

use DoveFramework\DB\DbParameter;
use DoveFramework\DB\DbPdo;
use DoveFramework\Interfaces\IContext;
use DoveFramework\Interfaces\IDb;

/**
 * 默认上下文管理器。
 *
 * @package       DoveFramework\Context
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
class DefaultContext extends AbstractBase implements IContext {
    /**
     * PDO 数据库管理对象。
     *
     * @var IDb
     */
    public $dbo = NULL;

    /**
     * 上下文管理器初始化。
     */
    function initialize(): void {
        $cfgs = $this->bootstrap->cfg->getConfigs();

        // 数据库
        if ($this->bootstrap->cli)
            $idle_timeout = 7776000;
        else
            $idle_timeout = 28800;

        $this->dbo = new DbPdo($this->bootstrap, new DbParameter($cfgs['db']['host'], $cfgs['db']['port'], $cfgs['db']['user'], $cfgs['db']['pass'], $cfgs['db']['name'], $cfgs['db']['charset'], ( string ) $cfgs['db']['socket'], $idle_timeout));
    }

    /**
     * 释放资源。
     */
    function dispose(): void {
        if ($this->dbo)
            $this->dbo->close();

        $this->dbo = NULL;
    }
}