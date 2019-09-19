<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{
    //

    public function index() {

        $redis = Redis::connection();

        $size = $redis->zcount('kline:top30_usdt:360', 1563778800, 1564502400);

        echo $size;

        $list = $redis->zrangebyscore('kline:top30_usdt:360', 1563778800, 1564502400);

        dd($list);

        //echo $redis->zremrangebyscore('kline:top30_usdt:360', 1563778800, 1564502400);
    }
}
