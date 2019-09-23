<?php
namespace ccheng\eventmanager\helpers;

class StringHelper
{
    /**
     * 超出指定数量字符部分替换指定字符
     * @param        $str
     * @param        $len
     * @param string $suffix
     *
     * @return string
     */
    public static function cut_str($str, $len, $suffix = "...")
    {
        if (function_exists('mb_substr')) {
            if (strlen($str) > $len) {
                $str = mb_substr($str, 0, $len, 'utf-8') . $suffix;
            }

            return $str;
        } else {
            if (strlen($str) > $len) {
                $str = substr($str, 0, $len, 'utf-8') . $suffix;
            }

            return $str;
        }
    }
}
