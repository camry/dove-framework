<?php
namespace DoveFramework\Helper;

use DoveFramework\Exceptions\FileNotFoundException;
use DoveFramework\Exceptions\IOException;

/**
 * 文件系统工具类。
 *
 * @package       DoveFramework\Helper
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2020-2021, Camry.Chen
 */
class FS {
    /**
     * 创建文件文件。
     *
     * @param string $filename        指定文件路径。
     * @param string $content         文本文件内容。
     * @param bool   $auto_create_dir 是否自动创建目录？(默认值: True)
     * @param int    $mask            目录权限掩码。(默认值: 0755)
     * @throws IOException
     */
    static function createTextFile(string $filename, string $content, bool $auto_create_dir = true, int $mask = 0755): void {
        if ($auto_create_dir) {
            $dirname = dirname($filename);

            if (!is_dir($dirname))
                mkdir($dirname, $mask, true);
        }

        $fp = fopen($filename, 'w');

        if ($fp) {
            fwrite($fp, $content);
            fclose($fp);
        } else {
            throw new IOException('创建文本文件失败。(' . $filename . ')');
        }
    }

    /**
     * 拷贝文件。
     *
     * @param string $src  指定源文件路径。
     * @param string $dst  指定目标文件路径。(注: 系统会自动检测并创建目录!)
     * @param int    $mask 目录权限掩码。(默认值: 0755)
     * @return bool
     * @throws FileNotFoundException
     */
    static function copy(string $src, string $dst, int $mask = 0755): bool {
        if (!is_file($src))
            throw new FileNotFoundException('源文件不存在。(' . $src . ')');

        $dir = dirname($dst);

        if (!is_dir($dir))
            mkdir($dir, true, $mask);

        return copy($src, $dst);
    }
}