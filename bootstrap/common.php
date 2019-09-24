<?php

/**
 * 公共函数
 */

/**
 * 时间类型，数字和字符串互转
 * @param $var void
 * @return mixed|null
 */
function intervalTurnString($var)
{
    $intArr = [1, 5, 15, 30, 60, 360, 720, 1440, 10080, 20160];
    $stringArr = ['1min', '5min', '15min', '30min', '1hour', '6hour', '12hour', '1day', '7day', '14day'];
    if (in_array($var, $intArr)) {
        return $stringArr[array_search($var, $intArr)];
    }
    if (in_array($var, $stringArr)) {
        return $intArr[array_search($var, $stringArr)];
    }
    return null;
}

/**
 * 获取交易对的步长设置即小数位数
 * @param $pair string
 * @return int 步长
 */
function getStepByPair($pair)
{
    $pair = strtolower($pair);
    if ($pair == 'ltc_usdt' || $pair == 'eth_usdt') {
        return 2;
    }
    if ($pair == 'eos_usdt') {
        return 3;
    }
    if ($pair == 'xrp_usdt') {
        return 4;
    }
    return 1;
}
