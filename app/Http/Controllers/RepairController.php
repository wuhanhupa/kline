<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

/**
 * 修复k线
 * Class RepairController
 * @package App\Http\Controllers
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

        $list = [];
        if ($start && $end) {
            $redis = Redis::connection();
            //组装键名
            $key = "kline:" . $pair . ":" . $interval;
            //根据分值查询redis数据
            $list = $redis->zrangebyscore($key, $start, $end);
        }

        return view("repair/index", compact("list", "start", "end", "pair", "interval", "key"));
    }

    /**
     * 执行redis覆盖操作
     * TODO 需要进一步完善
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
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
        $inte = $this->getNameByInterval($interval);

        //根据时间戳判断年和月
        $hou = date("Ym", $start);

        $tableName = "kline_data_" . $hou;

        $sql = "select * from " . $tableName . " where exp_name='" . $yuan . "' and `interval`='" . $inte . "' and open_time>=" . ($start . "000") . " and open_time<=" . ($end . "000") . " and pair='" . strtoupper($pair) . "'";

        $list = DB::select($sql);


        $redis = Redis::connection();
        //组装键名
        $key = "kline:" . $pair . ":" . $interval;

        //清除区间内所有的redis记录
        $redis->zremrangebyscore($key, $start, $end);


        $step = $this->getStepByPair($pair);

        foreach ($list as $k => $val) {
            $score = $val->open_time / 1000;

            $data = [
                $val->open_time / 1000,
                round($val->volume, 2),
                round($val->open, 2),
                round($val->high, 2),
                round($val->low, 2),
                round($val->close, 2)
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

    protected function getNameByInterval($interval)
    {
        switch ($interval) {
            case 720:
                $key = "12hour";
                break;
            case 14 * 24 * 60:
                $key = "14day";
                break;
            case 15:
                $key = "15min";
                break;
            case 1440:
                $key = "1day";
                break;
            case 60:
                $key = "1hour";
                break;
            case 1:
                $key = "1min";
                break;
            case 30:
                $key = "30min";
                break;
            case 5:
                $key = "5min";
                break;
            case 360:
                $key = "6hour";
                break;
            case 7 * 24 * 60:
                $key = "7day";
                break;
            default:
                $key = 0;
                break;
        }
        return $key;
    }
}
