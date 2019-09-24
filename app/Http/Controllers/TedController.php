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
        $list = DB::table('all_table')->orderBy('table_name', 'desc')->get();

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
        try {
            $tableName = $request->get('tableName');

            $expName = 'bitfinex';
            $pair = 'BTC_USDT';

            if ($request->has('expName')) {
                $expName = $request->get('expName');
            }
            if ($request->has('pair')) {
                $pair = $request->get('pair');
            }

            $sql = "SELECT pair,`interval`,COUNT(*) as nums FROM " . $tableName . " WHERE exp_name='" . $expName . "' and pair='" . $pair . "'  GROUP BY pair,`interval`";

            $list = DB::select($sql);

            //$list = DB::table($tableName)->where($where)->get();

            return view("ted/info", compact("list", "tableName", "expName", "pair"));
        } catch (\Exception $e) {
            return back()->withErrors([$e->getMessage()]);
        }

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

        $sql = "SELECT * FROM " . $tableName . " WHERE  pair='" . $pair . "' AND `interval`='" . $interval . "' AND exp_name='" . $expName . "'";
        $list = DB::select($sql);

        $step = getStepByPair($pair);

        $data = [];
        foreach ($list as $k => $val) {
            $data[$k]['exp_name'] = $val->exp_name;
            $data[$k]['pair'] = $val->pair;
            $data[$k]['interval'] = $val->interval;
            $data[$k]['score'] = $val->open_time / 1000;

            $data[$k]['data'] = [
                $val->open_time / 1000,
                round($val->volume, 2),
                round($val->open, $step),
                round($val->high, $step),
                round($val->low, $step),
                round($val->close, $step)
            ];

            $data[$k]['data_json'] = json_encode($data[$k]['data']);
            $data[$k]['date'] = date("Y-m-d H:i:s", $val->open_time / 1000);
        }

        //dd($data);

        return view("ted/preview", compact("data", "tableName", "pair", "interval"));
    }
}
