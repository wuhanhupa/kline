@extends('layout/main')

@section('content')
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">数据库搜索条件</h3>
                    </div>
                    <form>
                        <div class="box-body">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="hidden" name="tableName" value="{{$tableName}}"/>
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-info">交&nbsp;易&nbsp;对</button>
                                    </div>
                                    <select class="form-control" name="pair">
                                        <option value="btc_usdt">BTC_USDT</option>
                                        <option value="eth_usdt">ETH_USDT</option>
                                        <option value="eos_usdt">EOS_USDT</option>
                                        <option value="ltc_usdt">LTC_USDT</option>
                                        <option value="xrp_usdt">XRP_USDT</option>
                                    </select>

                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-danger">数据源</button>
                                    </div>
                                    <select class="form-control" name="expName">
                                        <option value="bitfinex">bitfinex</option>
                                        <option value="binance">binance</option>
                                        <option value="okexspot">okexspot</option>
                                        <option value="okexswap">okexswap</option>
                                        <option value="zb">zb</option>
                                    </select>

                                    <span class="input-group-btn">
                                      <button type="submit" class="btn btn-info btn-flat">Go!</button>
                                    </span>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->
                        </div>
                    </form>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">表名： {{$tableName}}</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>数据源</th>
                                <th>交易对</th>
                                <th>时间类型</th>
                                <th>记录条数</th>
                                <th>操作</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($list as $k => $val)
                                <tr>
                                    <td>{{ $expName}}</td>
                                    <td>{{ $val->pair }}</td>
                                    <td>{{ $val->interval }}</td>
                                    <td>{{ $val->nums }}</td>
                                    <td> <a href="/ted/preview?tableName={{$tableName}}&pair={{$val->pair}}&interval={{$val->interval}}&expName={{$expName}}"> 组装redis数据并预览 </a> </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div>
        </div>

    </section><!-- /.content -->

@endsection

