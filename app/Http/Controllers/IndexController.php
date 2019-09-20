<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class IndexController extends Controller
{
    /**
     * 首页
     * TODO 后续完善展示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view("index");
    }

    /**
     * 直接写入redis
     * TODO 直接写入有问题，需要改造方法
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function writeRedis(Request $request)
    {

        $tableName = $request->get("tableName");
        $pair = strtolower($request->get("pair"));
        $interval = $request->get("interval");
        $sql = "SELECT * FROM " . $tableName . " WHERE  pair='" . $pair . "' AND `interval`='" . $interval . "'";

        $list = DB::select($sql);

        $redis = Redis::connection();


        $step = $this->getStepByPair($pair);

        foreach ($list as $k => $val) {
            $score = $val->open_time / 1000;

            //如果是bitfinex使用不同的价格组合
            if ($val->exp_name == "bitfinex") {
                $data[$k]['data'] = [
                    $val->open_time / 1000,
                    round($val->volume, 2),
                    round($val->open, 2),
                    round($val->low, 2),
                    round($val->close, 2),
                    round($val->high, 2)
                ];
            } else {
                $data[$k]['data'] = [
                    $val->open_time / 1000,
                    round($val->volume, 2),
                    round($val->open, 2),
                    round($val->high, 2),
                    round($val->low, 2),
                    round($val->close, 2)
                ];
            }
            //直接写入redis
            $key = $this->getKeyByPairAndInterval($pair, $interval);
            $check = $redis->zrangebyscore($key, $score, $score);
            if ($check) {
                $redis->zremrangebyscore($key, $score, $score);
            }
            $redis->zadd($key, $score, json_encode($data));
        }

        return response()->json([
            "code" => 0,
            "msg" => "success"
        ]);
    }

    /**
     * redis K线 键名列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function redis_list()
    {
        $redis = Redis::connection();

        $redis->select(5);

        $keys = $redis->keys("kline:*");
        //dd($keys);
        $list = [];
        foreach ($keys as $k => $key) {
            $list[$k]['keyName'] = $key;
            $list[$k]['size'] = $redis->zcard($key);
        }

        return view("redis_list", compact('list'));
    }

    /**
     * 根据交易对和时间类型组装键名
     */
    protected function getKeyByPairAndInterval($pair, $interval)
    {
        switch ($interval) {
            case "12hour":
                $key = 720;
                break;
            case "14day":
                $key = 14 * 24 * 60;
                break;
            case "15min":
                $key = 15;
                break;
            case "1day":
                $key = 1440;
                break;
            case "1hour":
                $key = 60;
                break;
            case "1min":
                $key = 1;
                break;
            case "30min":
                $key = 30;
                break;
            case "5min":
                $key = 5;
                break;
            case "6hour":
                $key = 360;
                break;
            case "7day":
                $key = 7 * 24 * 60;
                break;
            default:
                $key = 0;
                break;
        }
        return "kline:" . $pair . ":" . $key;
    }

    /**
     * 获取交易对的步长设置即小数位数
     * @param $pair 交易对
     * @return int 步长
     */
    protected function getStepByPair($pair)
    {
        switch ($pair) {
            case "ltc_usdt":
            case "eth_usdt":
                return 2;
                break;
            case "eos_usdt":
                return 3;
                break;
            case "xrp_usdt":
                return 4;
                break;
            default:
                return 1;
                break;
        }
    }
}
