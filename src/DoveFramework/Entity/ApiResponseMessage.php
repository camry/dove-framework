<?php
namespace DoveFramework\Entity;

use DoveFramework\Interfaces\ISerializable;

/**
 * API 接口标准输出对象实体类。(注: 请勿在应用层再次实现输出标准!)
 *
 * @package       DoveFramework\Entity
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
class ApiResponseMessage implements ISerializable {
    /**
     * 错误码。
     *
     * @var int
     */
    protected $error_code = 0;

    /**
     * 错误描述。
     *
     * @var string
     */
    protected $error_message = NULL;

    /**
     * 自定义响应头信息。
     *
     * @var array
     */
    protected $headers = [];

    /**
     * 数据对象。
     *
     * @var mixed
     */
    protected $data = NULL;

    /**
     * 构造函数。
     *
     * @param int    $error_code    错误码。(注: 成功时, 此值为零.)
     * @param string $error_message 错误描述信息。
     * @param mixed  $data          数据对象。
     */
    function __construct($data, $error_code = 0, $error_message = NULL) {
        $this->data          = $data;
        $this->error_code    = $error_code;
        $this->error_message = $error_message;
    }

    /**
     * 获取错误码。
     *
     * @return int
     */
    function getErrorCode(): int {
        return $this->error_code;
    }

    /**
     * 获取错误描述信息。
     *
     * @return string
     */
    function getErrorMessage(): string {
        return $this->error_message;
    }

    /**
     * 获取数据对象。
     *
     * @return mixed
     */
    function getData() {
        return $this->data;
    }

    /**
     * 添加自定义响应头信息。
     *
     * @param string $key
     * @param string $value
     * @return ApiResponseMessage
     */
    function addHeader(string $key, string $value): ApiResponseMessage {
        $this->headers[$key] = $value;

        return $this;
    }

    /**
     * 获取自定义响应头集合。
     *
     * @return array
     */
    function getHeaders(): array {
        return $this->headers;
    }

    /**
     * 是否包含自定义响应头信息？
     *
     * @return bool
     */
    function hasHeaders(): bool {
        return !empty($this->headers);
    }

    /**
     * 对象转换为数组输出。
     *
     * @param array $options 指定选项参数。
     * @return array
     */
    function toArray(array $options = []): array {
        $d = array(
            'r' => $this->error_code,
            'e' => $this->error_message,
            'd' => $this->data,
        );

        return $d;
    }

    /**
     * 对象转换为 JSON 字符串输出。
     *
     * @param array $options      指定选项参数。
     * @param int   $json_options 指定 JSON 编码选项参数。(默认值: 320)
     * @return string
     */
    function toJSONString(array $options = [], int $json_options = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE): string {
        return json_encode($this->toArray(), $json_options);
    }

    /**
     * 对象转换为字符串。
     *
     * @return string
     */
    function __toString(): string {
        return $this->toJSONString();
    }
}