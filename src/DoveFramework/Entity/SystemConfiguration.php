<?php
namespace DoveFramework\Entity;

use DoveFramework\Core\ObjectSerializable;
use DoveFramework\Exceptions\ConfigurationException;
use DoveFramework\Exceptions\FileNotFoundException;

/**
 * 系统配置对象。
 *
 * @package       DoveFramework\Entity
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
final class SystemConfiguration extends ObjectSerializable {
    /**
     * 应用程序名称。
     *
     * @var string
     */
    private $app_name = 'Dove';

    /**
     * 系统版本号。
     *
     * @var string
     */
    private $version = '1.0.0';

    /**
     * 系统时区。
     *
     * @var string
     */
    private $timezone = 'Asia/Shanghai';

    /**
     * 日志存储目录。
     *
     * @var string
     */
    private $log_directory = '';

    /**
     * 日志输出级别。
     *
     * @var int
     */
    private $log_level = 400;

    /**
     * 日志模块名称。
     *
     * @var string
     */
    private $log_name = 'dove';

    /**
     * 是否调试模式？
     *
     * @var bool
     */
    private $debug = false;

    /**
     * 全局配置参数列表。
     *
     * @var array
     */
    private $configs = [];

    /**
     * 构造函数。
     *
     * @param string $config_ini_file 指定全局 INI 配置文件。
     * @throws ConfigurationException
     * @throws FileNotFoundException
     */
    public function __construct(string $config_ini_file) {
        if (!is_file($config_ini_file))
            throw new FileNotFoundException('配置文件不存在。(' . $config_ini_file . ')', 1001);

        $this->configs = parse_ini_file($config_ini_file, true, INI_SCANNER_TYPED);

        if (!$this->configs)
            throw new ConfigurationException('文件配置异常。(' . $config_ini_file . ')', 1002);

        // APP
        if (isset($this->configs['app']['name']))
            $this->app_name = $this->configs['app']['name'];
        if (isset($this->configs['app']['version']))
            $this->app_name = $this->configs['app']['version'];
        if (isset($this->configs['app']['timezone']))
            $this->app_name = $this->configs['app']['timezone'];

        // 日志
        if (isset($this->configs['log']['directory']))
            $this->log_directory = $this->configs['log']['directory'];
        if (isset($this->configs['log']['level']))
            $this->log_level = $this->configs['log']['level'];
        if (isset($this->configs['log']['name']))
            $this->log_name = $this->configs['log']['name'];

        // DEBUG
        if (isset($this->configs['debug']['enable']))
            $this->debug = $this->configs['debug']['enable'];
    }

    /**
     * 获取应用程序名称。
     *
     * @return string
     */
    public function getAppName(): string {
        return $this->app_name;
    }

    /**
     * 获取系统版本号。
     *
     * @return string
     */
    public function getVersion(): string {
        return $this->version;
    }

    /**
     * 获取系统时区。
     *
     * @return string
     */
    public function getTimezone(): string {
        return $this->timezone;
    }

    /**
     * 获取日志存储目录。
     *
     * @return string
     */
    public function getLogDirectory(): string {
        return $this->log_directory;
    }

    /**
     * 获取日志级别。
     *
     * @return int
     */
    public function getLogLevel(): int {
        return $this->log_level;
    }

    /**
     * 获取缺省日志模块名称。
     *
     * @return string
     */
    public function getLogName(): string {
        return $this->log_name;
    }

    /**
     * 是否调试模式？
     *
     * @return bool
     */
    public function isDebug(): bool {
        return $this->debug;
    }

    /**
     * 获取全局配置参数列表。
     *
     * @return array
     */
    public function getConfigs(): array {
        return $this->configs;
    }
}