<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Exception;

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
            $tables =  DB::table('all_table')->orderBy('table_name', 'desc')->get();

            //根据条件查询对应数据
            $interval = $request->get('interval');
            $expName = $request->get('exp_name');
            $tableName = $request->get('table');
            $list = [];
            if ($interval && $expName && $tableName) {
                $list = DB::table($tableName)->where([
                    'exp_name' => $expName,
                    'interval' => intervalTurnString($interval)
                ])->whereIn('pair', ['BTC_USDT', 'ETH_USDT'])->get();
            }

            return view('contract/index', compact('interval', 'tables', 'list', 'expName', 'tableName'));
        } catch (Exception $e) {
            return back()->withErrors([$e->getMessage()]);
        }
    }

    /**
     * 计算top30数据并预览
     * @param Request $request
     */
    public function preview(Request $request)
    {
        $tableName = $request->get('tableName');
        $interval = $request->get('interval');
        $expName = $request->get('expName');

        //查询比特币数据
        $btcArr = DB::table($tableName)->where([
            'exp_name' => $expName,
            'interval' => intervalTurnString($interval),
            'pair' => 'BTC_USDT'
        ])->get();
        //查询以太坊数据
        $ethArr = DB::table($tableName)->where([
            'exp_name' => $expName,
            'interval' => intervalTurnString($interval),
            'pair' => 'ETH_USDT'
        ])->get();
    }
}
