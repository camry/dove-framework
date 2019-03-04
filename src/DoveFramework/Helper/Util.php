<?php
namespace DoveFramework\Helper;

/**
 * 常用工具类。
 *
 * @package       DoveFramework\Helper
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
class Util {
    /**
     * 合并名称空间、类名并返回完全限定名。
     *
     * @param array ...$args
     * @return string
     */
    static function ns(...$args) {
        if (empty($args))
            return '';

        return implode('\\', $args);
    }

    /**
     * 字符串变量替换。(支持可变参数)
     *
     * @param array ...$args
     * @return string
     */
    static function substitute(...$args) {
        $size = sizeof($args);
        $str  = $args[0];

        for ($i = 1; $i < $size; $i++) {
            $str = str_replace('{' . ($i - 1) . '}', $args[$i], $str);
        }

        return $str;
    }

    /**
     * 生成指定长度的随机标识符。
     *
     * @param int $len
     * @return string
     */
    static function shuffle(int $len = 8): string {
        $s        = '1234567890qwertyuiopasdfghjklzxcvbnmABCDEFGHIJKLMNPQRSTUVWXYZ';
        $shuffled = str_shuffle($s);

        return substr($shuffled, 0, $len);
    }

    /**
     * 生成 36 位 UUID 全局唯一代码。
     *
     * @return string
     */
    static function uuid(): string {
        return \uuid_create();
    }

    /**
     * 生成 GUID 全球唯一标识。
     *
     * @return string
     */
    static function guid(): string {
        $charid = strtoupper(md5(uniqid(mt_rand(), true)));
        $hyphen = '-';
        $uuid   = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);

        return $uuid;
    }

    /**
     * 抽奖计算。
     *
     * @param array $ratios 指定概率列表。
     * @param int   $start  指定随机数起始值。
     * @return int|string 返回中奖的元素 key 值。
     */
    static function lottery(array $ratios, int $start = 1) {
        $result = false;

        // 概率数组的总概率精度
        $proSum = array_sum($ratios);

        // 概率数组循环
        foreach ($ratios as $key => $proCur) {
            $randNum = mt_rand($start, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }

        unset($ratios);

        return $result;
    }

    /**
     * 概率计算函数。检测传入的概率值是否命中？
     *
     * @param float $rate 指定概率值。(此值必须是 0~1 之间的浮点数。包含0,1两个整数.)
     * @param int   $base 浮点运算最大基数范围值。(默认值: 100 | 即: 以百分位计算概率)
     * @return bool 返回 True 时，表示已命中。
     */
    static function hit(float $rate, int $base = 100) {
        if ($rate > 1)
            $rate = 1.00;
        // throw new ArgumentException('传入的概率值 $rate 必须是 0~1 之间的浮点数或整数(0|1)。', -1);

        $r = $base * $rate;
        $v = mt_rand(1, $base);

        if ($v <= $r)
            return true;

        return false;
    }

    /**
     * IP 地址转换为整数。
     *
     * @param string $ip_addr 指定 IPv4 地址。
     * @return int
     */
    static function ip(string $ip_addr): int {
        $ips = explode('.', $ip_addr);

        $v = ( int ) $ips[0] * 16777216;
        $v += ( int ) $ips[1] * 65536;
        $v += ( int ) $ips[2] * 256;
        $v += ( int ) $ips[3];

        return $v;
    }

    /**
     * 检查 $num 数值是否为素数？
     *
     * @param int $num
     * @return boolean
     */
    static function isPrime(int $num): bool {
        if ($num == 1)
            return false;

        if ($num == 2)
            return true;

        if ($num % 2 == 0) {
            return false;
        }

        for ($i = 3; $i <= ceil(sqrt($num)); $i = $i + 2) {
            if ($num % $i == 0)
                return false;
        }

        return true;
    }

    /**
     * Determine if a given string contains a given substring.
     *
     * @param  string       $haystack
     * @param  string|array $needles
     * @return bool
     */
    static function contains(string $haystack, array $needles): bool {
        foreach ($needles as $needle) {
            if ($needle != '' && strpos($haystack, $needle) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * 替换字符串变量。(注: 此方法支持语言包特性!)
     *
     * @param string|int $message 指定消息文本内容或消息 ID。
     * @param array      $vars    指定 HashMap 键值对列表。
     * @param string     $prefix  指定变量命名规范前缀字符。(可选 | 默认: % 百分号)
     * @return string
     */
    static function translate($message, array $vars, string $prefix = '%'): string {
        $s = [];
        $r = [];

        foreach ($vars as $key => $value) {
            $s[] = $prefix . '{' . $key . '}';
            $r[] = $value;
        }

        if (is_int($message))
            $message = _(strval($message));

        return str_replace($s, $r, $message);
    }

    /**
     * 获取字符串真实长度。(注: 中文以 2 个字节计算)
     *
     * @param string $str      指定测试字符串。
     * @param string $encoding 指定字符串编码。(默认值: UTF-8)
     * @return int
     */
    static function length(string $str, string $encoding = 'UTF-8') {
        return (strlen($str) + mb_strlen($str, $encoding)) / 2;
    }

    /**
     * 生成 alpha ID 编码。
     *
     * @param int|string  $in       String or long input to translate
     * @param boolean     $to_num   Reverses translation when true
     * @param int|boolean $pad_up   Number or boolean padds the result up to a specified length
     * @param string      $pass_key Supplying a password makes it harder to calculate the original ID
     * @return string|int
     */
    static function alphaId($in, bool $to_num = false, $pad_up = false, $pass_key = NULL) {
        $index = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $base  = strlen($index);
        $i     = [];

        if ($pass_key !== NULL) {
            for ($n = 0; $n < strlen($index); $n++) {
                $i[] = substr($index, $n, 1);
            }

            $pass_hash = hash('sha256', $pass_key);
            $pass_hash = (strlen($pass_hash) < strlen($index) ? hash('sha512', $pass_key) : $pass_hash);

            for ($n = 0; $n < strlen($index); $n++) {
                $p[] = substr($pass_hash, $n, 1);
            }

            array_multisort($p, SORT_DESC, $i);
            $index = implode($i);
        }

        if ($to_num) {
            $out = 0;
            $len = strlen($in) - 1;

            for ($t = $len; $t >= 0; $t--) {
                $bcp = bcpow($base, $len - $t);
                $out = $out + strpos($index, substr($in, $t, 1)) * $bcp;
            }

            if (is_numeric($pad_up)) {
                $pad_up--;

                if ($pad_up > 0) {
                    $out -= pow($base, $pad_up);
                }
            }
        } else {
            $out = '';

            if (is_numeric($pad_up)) {
                $pad_up--;

                if ($pad_up > 0) {
                    $in += pow($base, $pad_up);
                }
            }

            for ($t = ($in != 0 ? floor(log($in, $base)) : 0); $t >= 0; $t--) {
                $bcp = bcpow($base, $t);
                $a   = floor($in / $bcp) % $base;
                $out = $out . substr($index, $a, 1);
                $in  = $in - ($a * $bcp);
            }
        }

        return $out;
    }

    /**
     * Convert strings with underscores into CamelCase
     *
     * @param string $string          The string to convert
     * @param bool   $first_char_caps camelCase or CamelCase
     * @return string    The converted string
     *
     */
    static function toCamelCase(string $string, bool $first_char_caps = false): string {
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
     * 生成 Redis 缓存键名。
     *
     * @param array ...$args
     * @return string
     */
    static function genCacheKey(...$args): string {
        $key = implode(':', $args);
        $key = strtolower(str_replace('\\', ':', $key));

        return $key;
    }

    /**
     * 检查数组是 List 还是 Map 类型？
     *
     * @param array $arr
     * @return bool
     */
    static function isAssoc(array $arr): bool {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * 创建 GUID/GRID 规则字符串。(注: 此方法目前仅适用于创建角色时调用.)
     *
     * @param int  $pf      平台编号。
     * @param int  $dist_id 区服编号。
     * @param int  $id      玩家编号或角色唯一编号。
     * @param bool $is_guid 是否 GUID 规则模式？
     * @return string
     */
    static function toGID(int $pf, int $dist_id, int $id, bool $is_guid = true): string {
        return sprintf('G%sID-%03d-%06d-%010d', $is_guid ? 'U' : 'R', $pf, $dist_id, $id);
    }

    /**
     * 编码 PHP 数组为 Base64 规则字符串。(注: 包含固定安全密钥.)
     *
     * @param array $data 指定要加密编码的 PHP 数组。
     * @return string
     */
    static function encodeArray(array $data): string {
        $secret_key = '1u%$z^IW&O&Ny*@a';
        ksort($data);
        $qss = http_build_query($data);
        $sig = rawurlencode(base64_encode(hash('sha1', $secret_key . '||' . $qss, true)));
        $s   = base64_encode($qss . '&sig=' . $sig);

        return $s;
    }

    /**
     * Generate a "random" alpha-numeric string.
     *
     * Should not be considered sufficient for cryptography, etc.
     *
     * @param  int $length
     * @return string
     */
    static function quickRandom(int $length = 16): string {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, $length)), 0, $length);
    }

    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string       $haystack
     * @param  string|array $needles
     * @return bool
     */
    static function startsWith(string $haystack, array $needles): bool {
        foreach ((array) $needles as $needle) {
            if ($needle != '' && strpos($haystack, $needle) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if a given string ends with a given substring.
     *
     * @param  string       $haystack
     * @param  string|array $needles
     * @return bool
     */
    static function endsWith(string $haystack, array $needles): bool {
        foreach ((array) $needles as $needle) {
            if ((string) $needle === substr($haystack, -strlen($needle))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Convert a pathname and a project identifier to a System V IPC key.
     *
     * @param string $filePath
     * @param int    $projectId
     * @return int|string
     */
    static function ftok(string $filePath, int $projectId) {
        $fileStats = stat($filePath);
        if (!$fileStats) {
            return -1;
        }

        return sprintf('%u',
            ($fileStats['ino'] & 0xffff) | (($fileStats['dev'] & 0xff) << 16) | ((ord($projectId) & 0xff) << 24)
        );
    }

    /**
     * 转换秒数到指定的字符串格式。
     *
     * @param int    $seconds 秒数。
     * @param string $format  显示格式。(默认值: 时:分:秒 | 具体参见 DateInterval::format 文档.)
     * @return string
     */
    static function formatSeconds(int $seconds, string $format = '%H:%I:%S'): string {
        $dtF = new \DateTime("@0");
        $dtT = new \DateTime("@" . $seconds);

        return $dtF->diff($dtT)->format($format);
    }

    /**
     * 检查两个数组的值是否完全相等？
     *
     * @param array $arr1   指定 PHP 数组。
     * @param array $arr2   指定 PHP 数组。
     * @param bool  $strict 是否严格比较模式？(默认值: False)
     * @return bool
     */
    static function isSameArray(array $arr1, array $arr2, bool $strict = false): bool {
        if (count($arr1) != count($arr2))
            return false;

        foreach ($arr1 as $key => $value) {
            if (!isset($arr2[$key]))
                return false;

            if ($strict) {
                if ($value !== $arr2[$key])
                    return false;
            } else {
                if ($value != $arr2[$key])
                    return false;
            }
        }

        return true;
    }
}