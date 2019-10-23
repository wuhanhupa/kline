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
     */
    public function index()
    {
        return view("index");
    }

    /**
     * 直接写入redis
     * TODO 直接写入有问题，需要改造方法
     */
    public function writeRedis(Request $request)
    {
        $tableName = $request->get("tableName");
        $pair = strtolower($request->get("pair"));
        $interval = $request->get("interval");
        //$sql = "SELECT * FROM " . $tableName . " WHERE  pair='" . $pair . "' AND `interval`='" . $interval . "'";
        //$list = DB::select($sql);
        $list = DB::table($tableName)->where([
            'pair' => $pair,
            'interval' => $interval
        ])->get();

        $redis = Redis::connection();
        //获取交易对的步长设置
        $step = getStepByPair($pair);

        foreach ($list as $k => $val) {
            $score = $val->open_time / 1000;

            $data[$k]['data'] = [
                $val->open_time / 1000,
                round($val->volume, 2),
                round($val->open, $step),
                round($val->high, $step),
                round($val->low, $step),
                round($val->close, $step)
            ];

            //直接写入redis
            $key = "kline:" . $pair . ":" . intervalTurnString($interval);
            $check = $redis->zrangebyscore($key, $score, $score);
            if ($check) {
                $redis->zremrangebyscore($key, $score, $score);
            }
            $redis->zadd($key, $score, json_encode($data));
            //$redis->zadd($key, [json_encode($data), $score]);
        }

        return response()->json([
            "code" => 0,
            "msg" => "success"
        ]);
    }
}
