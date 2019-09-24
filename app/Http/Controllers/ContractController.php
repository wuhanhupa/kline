<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * 合约交易对 修复
 * xtop交易所对应合约交易对-top30
 *
 * Class ContractController
 * @package App\Http\Controllers
 */
class ContractController extends Controller
{
    //
    public function index(Request $request)
    {
        try {
            //查询上海内网ted数据库数据表
            $sql = "select table_name from information_schema.tables where table_schema='ted' and table_name like '%kline_data%' ORDER BY table_name DESC";
            $tables = DB::select($sql);

            //根据条件查询对应数据
            $interval = $request->get('interval');
            $exp = $request->get('exp_name');
            $table = $request->get('table');
            $list = [];
            if ($interval && $exp && $table) {
                $list = DB::table($table)->where([
                    'exp_name' => $exp,
                    'interval' => $interval
                ])->get();
            }

            return view('contract/index', compact('interval', 'tables', 'list', 'exp', 'table'));
        } catch (\Exception $e) {
            return back()->withErrors([$e->getMessage()]);
        }

    }
}
