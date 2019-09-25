<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

/**
 * redis 键名列表
 * Class RedisController
 * @package App\Http\Controllers
 */
class RedisController extends Controller
{
    public function index()
    {
        //读取缓存
        if(Cache::get('redisKeys')) {
            $list = Cache::get('redisKeys');
            //dd('读取缓存成功');
        } else {
            $redis = Redis::connection();

            $redis->select(5);

            $keys = $redis->keys("kline:*");
            //dd($keys);
            $list = [];
            foreach ($keys as $k => $key) {
                $list[$k]['keyName'] = $key;
                $list[$k]['size'] = $redis->zcard($key);
            }

            Cache::store('redis')->put('redisKeys', $list);
            //dd('写入缓存成功');
        }


        return view("redis/index", compact('list'));
    }
}
