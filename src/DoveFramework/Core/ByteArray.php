<?php
namespace DYGame\Core;

/**
 * ByteArray 二进制操作类。
 *
 * @package       DYGame\Core
 * @author        Camry Chen <camry.chen@foxmail.com>
 * @version       1.0.0
 * @copyright (c) 2018-2019, Camry Chen
 */
class ByteArray {
    /**
     * 缓冲区。
     *
     * @var string
     */
    private $buffer = '';

    /**
     * Writes a Boolean value. A single byte is written according to the value parameter, either 1 if true or 0 if false.
     *
     * @param bool $v
     */
    function writeBoolean($v) {
        $this->buffer .= pack('c', $v);
    }

    /**
     * Writes a 16-bit integer to the byte stream. The low 16 bits of the parameter are used. The high 16 bits are ignored.
     *
     * @param int $v
     */
    function writeShort($v) {
        $this->buffer .= pack('n', $v);
    }

    /**
     * Writes a 32-bit signed integer to the byte stream.
     *
     * @param int $v
     */
    function writeInt($v) {
        $this->buffer .= pack('N', $v);
    }

    /**
     * Writes a 32-bit unsigned integer to the byte stream.
     *
     * @param int $v
     */
    function writeUnsignedInt($v) {
        $this->buffer .= pack('N', $v);
    }

    /**
     * Writes a unsigned long long (always 64 bit, big endian byte order)
     *
     * @param int $v
     */
    function writeInt64($v) {
        $this->buffer .= pack('J', $v);
    }

    /**
     * Writes a unsigned long long (always 64 bit, big endian byte order)
     *
     * @param int $v
     */
    function writeUnsignedInt64($v) {
        $this->buffer .= pack('J', $v);
    }

    /**
     * Writes an IEEE 754 single-precision (32-bit) floating-point number to the byte stream.
     *
     * @param float $v
     */
    function writeFloat($v) {
        $this->buffer .= strrev(pack('f', $v));

        // $a = unpack("I", pack("f", $v));
        // $this->buffer .= pack('N', $a[1]);
    }

    /**
     * Writes an IEEE 754 double-precision (64-bit) floating-point number to the byte stream.
     *
     * @param double $v
     */
    function writeDouble($v) {
        $this->buffer .= strrev(pack('d', $v));
    }

    /**
     * Writes a UTF-8 string to the byte stream. The length of the UTF-8 string in bytes is written first, as a 16-bit integer, followed by the bytes representing the characters of the string.
     *
     * @param string $v
     */
    function writeUTF($v) {
        $this->buffer .= pack('n', strlen($v));
        $this->buffer .= $v;
    }

    /**
     * The length of the ByteArray object, in bytes.
     *
     * @return int
     */
    function available() {
        return strlen($this->buffer);
    }

    /**
     * Clears the contents of the byte array and resets the length and position properties to 0. Calling this method explicitly frees up the memory used by the ByteArray instance.
     */
    function clear() {
        $this->buffer = '';
    }

    /**
     * Converts the byte array to a string. If the data in the array begins with a Unicode byte order mark, the application will honor that mark when converting to a string. If System.useCodePage is set to true, the application will treat the data in the array as being in the current system code page when converting.
     *
     * @return string
     */
    function toString() {
        return $this->buffer;
    }
}