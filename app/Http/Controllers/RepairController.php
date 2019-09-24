<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

/**
 * 修复k线
 */
class RepairController extends Controller
{
    //
    public function index(Request $request)
    {
        $start = $request->get("start");
        $end = $request->get("end");
        $pair = $request->get("pair");
        $interval = $request->get("interval");

        //组装键名
        $key = "kline:" . $pair . ":" . $interval;
        $list = [];
        if ($start && $end) {
            $redis = Redis::connection();
            //根据分值查询redis数据
            $list = $redis->zrangebyscore($key, $start, $end);
        }

        return view("repair/index", compact("list", "start", "end", "pair", "interval", "key"));
    }

    /**
     * 执行redis覆盖操作
     * TODO 需要进一步完善
     */
    public function search(Request $request)
    {
        set_time_limit(0);
        //起始时间戳
        $start = $request->get("start");
        //结束时间戳
        $end = $request->get("end");
        //交易对
        $pair = $request->get("pair");
        //时间类型
        $interval = $request->get("interval");
        //数据源--交易所名称
        $yuan = $request->get("yuan");

        //tep的时间类型转换
        $int = intervalTurnString($interval);

        //根据时间戳判断年和月
        $hou = date("Ym", $start);

        $tableName = "kline_data_" . $hou;

        //$sql = "select * from " . $tableName . " where exp_name='" . $yuan . "' and `interval`='" . $int . "' and open_time>=" . ($start . "000") . " and open_time<=" . ($end . "000") . " and pair='" . strtoupper($pair) . "'";

        //$list = DB::select($sql);

        $list = DB::table($tableName)->where([
            'exp_name' => $yuan,
            'interval' => $int,
            'pair' => strtoupper($pair)
        ])->whereBetween('open_time', [$start."000", $end."000"])->get();

        dd($list);

        $redis = Redis::connection();
        //组装键名
        $key = "kline:" . $pair . ":" . $interval;

        //清除区间内所有的redis记录
        $redis->zremrangebyscore($key, $start, $end);

        $step = getStepByPair($pair);

        foreach ($list as $k => $val) {
            $score = $val->open_time / 1000;

            $data = [
                $val->open_time / 1000,
                round($val->volume, 2),
                round($val->open, $step),
                round($val->high, $step),
                round($val->low, $step),
                round($val->close, $step)
            ];

            //直接写入redis
            $check = $redis->zrangebyscore($key, $score, $score);
            if ($check) {
                $redis->zremrangebyscore($key, $score, $score);
            }
            $redis->zadd($key, $score, json_encode($data));
        }

        return back()->with(["msg" => "覆盖成功"]);
    }
}
