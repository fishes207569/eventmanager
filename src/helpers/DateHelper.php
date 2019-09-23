<?php
namespace ccheng\eventmanager\helpers;

class DateHelper
{
    public static function getNowWeeks($time = '', $format = 'd')
    {
        $time = $time != '' ? $time : time();
        //获取当前周几
        $week = date('w', $time);
        $date = [];
        for ($i = 1; $i <= 7; $i++) {
            $date[$i] = date($format, strtotime('+' . $i - $week . ' days', $time));
        }

        return $date;
    }
}
