<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class IndexController extends Controller
{
    //
    public function index()
    {

        return view("index");
    }


    public function ted()
    {
        //查询上海内网ted数据库数据表
        $sql = "select table_name,table_rows from information_schema.tables where table_schema='ted' and table_name like '%kline_data%'";

        $list = DB::select($sql);

        return view("ted", compact("list"));
    }

    public function tedInfo(Request $request)
    {

        $tableName = $request->get('tableName');

        $sql = "SELECT pair,`interval`,COUNT(*) as nums FROM " . $tableName . " WHERE  pair in ('BTC_USDT','ETH_USDT','EOS_USDT','LTC_USDT','XRP_USDT')  GROUP BY pair,`interval`";

        $list = DB::select($sql);

        //dd($list);

        return view("ted_info", compact("list", "tableName"));
    }

    /**
     * 组装redis数据并预览
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function handleRedisData(Request $request)
    {

        $tableName = $request->get("tableName");
        $pair = $request->get("pair");
        $interval = $request->get("interval");

        $sql = "SELECT * FROM " . $tableName . " WHERE  pair='" . $pair . "' AND `interval`='" . $interval . "'";

        $list = DB::select($sql);

        //
        $data = [];
        foreach ($list as $k => $val) {
            $data[$k]['exp_name'] = $val->exp_name;
            $data[$k]['pair'] = $val->pair;
            $data[$k]['interval'] = $val->interval;
            $data[$k]['score'] = $val->open_time / 1000;

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

            $data[$k]['data_json'] = json_encode($data[$k]['data']);
            $data[$k]['date'] = date("Y-m-d H:i:s", $val->open_time / 1000);
        }

        //dd($data);

        return view("data", compact("data", "tableName", "pair", "interval"));
    }

    public function writeRedis(Request $request)
    {

        $tableName = $request->get("tableName");
        $pair = strtolower($request->get("pair"));
        $interval = $request->get("interval");
        $sql = "SELECT * FROM " . $tableName . " WHERE  pair='" . $pair . "' AND `interval`='" . $interval."'";

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


    public function redis_list() {
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
