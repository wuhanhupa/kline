<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 上海内网数据库
 * 抓取数据存放库名TED
 *
 * Class TedController
 * @package App\Http\Controllers
 */
class TedController extends Controller
{
    /**
     * 查询ted数据库 k线标识开头的数据表
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //查询上海内网ted数据库数据表
        $sql = "select table_name,table_rows from information_schema.tables where table_schema='ted' and table_name like '%kline_data%' ORDER BY table_name DESC";

        $list = DB::select($sql);

        return view("ted/index", compact("list"));
    }

    /**
     * 获取单张数据表里的数据分组信息
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function info(Request $request)
    {
        $tableName = $request->get('tableName');

        //默认查询BTC以及bitfinex交易所数据源
        /*$where = [
            'pair' => 'BTC_USDT',
            'exp_name' => 'bitfinex'
        ];*/
        $expName = 'bitfinex';
        $pair = 'BTC_USDT';
        if ($request->has("expName")) {
            $expName = $request->get('expName');
        }
        if ($request->has('pair')) {
            $pair = $request->get('pair');
        }

        $sql = "SELECT pair,`interval`,COUNT(*) as nums FROM " . $tableName . " WHERE exp_name='".$expName."' and pair='".$pair."'  GROUP BY pair,`interval`";

        $list = DB::select($sql);
        /*$list = DB::table($tableName)
            ->select(DB::raw("count(*) as nums,exp_name,pair"))
            ->where($where)->get()->groupBy(['pair', 'exp_name']);

        dd($list);*/

        return view("ted/info", compact("list", "tableName", "expName"));
    }

    /**
     * 将数据组装成k线需要的数据形式，并预览
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function preview(Request $request)
    {
        $tableName = $request->get("tableName");
        $pair = $request->get("pair");
        $interval = $request->get("interval");
        $expName = $request->get('expName');

        $sql = "SELECT * FROM " . $tableName . " WHERE  pair='" . $pair . "' AND `interval`='" . $interval . "' AND exp_name='".$expName."'";
        $list = DB::select($sql);

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

        return view("ted/preview", compact("data", "tableName", "pair", "interval"));
    }
}
