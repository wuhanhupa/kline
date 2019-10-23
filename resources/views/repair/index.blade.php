@extends('layout/main')

@section('content')

    <section class="content">

        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Redis搜索条件</h3>
                    </div>
                    <form action="{{url('repair/index')}}" method="get">
                        <div class="box-body">
                            <div class="form-group">
                                <div class="input-group date">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-default">起始时间戳</button>
                                    </div>
                                    <!-- /btn-group -->
                                    <input type="text" class="form-control" name="start" id="datepicker"
                                           @if($start) value="{{$start}}" @endif required autocomplete="off">
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-info">截止时间戳</button>
                                    </div>
                                    <input type="text" class="form-control" name="end" id="reservation"
                                           @if($end) value="{{$end}}" @endif required autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-danger">交&nbsp;易&nbsp;对</button>
                                    </div>
                                    <select class="form-control" name="pair">
                                        <option value="btc_usdt" @if($pair=='btc_usdt') selected @endif>BTC_USDT
                                        </option>
                                        <option value="eth_usdt" @if($pair=='eth_usdt') selected @endif>ETH_USDT
                                        </option>
                                        <option value="eos_usdt" @if($pair=='eos_usdt') selected @endif>EOS_USDT
                                        </option>
                                        <option value="ltc_usdt" @if($pair=='ltc_usdt') selected @endif>LTC_USDT
                                        </option>
                                        <option value="xrp_usdt" @if($pair=='xrp_usdt') selected @endif>XRP_USDT
                                        </option>
                                    </select>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->

                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-success">时间类型</button>
                                    </div>
                                    <select class="form-control col-md-3" name="interval">
                                        <option value="1" @if($interval=='1') selected @endif>1min</option>
                                        <option value="5" @if($interval=='5') selected @endif>5min</option>
                                        <option value="15" @if($interval=='15') selected @endif>15min</option>
                                        <option value="30" @if($interval=='30') selected @endif>30min</option>
                                        <option value="60" @if($interval=='60') selected @endif>1hour</option>
                                        <option value="360" @if($interval=='360') selected @endif>6hour</option>
                                        <option value="1440" @if($interval=='1440') selected @endif>1day</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">查询当前redis数据</button>
                        </div>
                    </form>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">数据源选择</h3>
                    </div>
                    <form action="{{url('repair/search')}}" method="get">
                        <div class="box-body">
                            <input type="hidden" name="_method" value="{{csrf_token()}}"/>
                            <input type="hidden" name="pair" value="{{$pair}}"/>
                            <input type="hidden" name="start" value="{{$start}}"/>
                            <input type="hidden" name="end" value="{{$end}}"/>
                            <input type="hidden" name="interval" value="{{$interval}}"/>
                            <!-- Date -->
                            <div class="form-group">
                                <div class="input-group date">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-danger">数据源</button>
                                    </div>
                                    <select class="form-control" name="yuan">
                                        <option value="bitfinex">bitfinex</option>
                                        <option value="binance">binance</option>
                                        <option value="okexspot">okexspot</option>
                                        <option value="okexswap">okexswap</option>
                                        <option value="zb">zb</option>
                                    </select>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <div class="form-group">
                                {{--<label>注意：</label>--}}
                                <textarea class="form-control" rows="6" placeholder="Enter ..." disabled>
1.选择一个数据源（即交易所）的数据，对现有redis数据进行覆盖。

2.左边的搜索条件搜索出来的数据就是要执行覆盖操作的数据。

3.如果数据过多会出现504超时错误，建议一次提交数据不超过200条。
                                </textarea>
                            </div>
                            <!-- /.form group -->
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-danger">提交覆盖REDIS</button>
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
                        <h3 class="box-title">搜索结果</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>键名</th>
                                <th>数据</th>
                            </tr>
                            </thead>

                            <tbody id="redis_body">
                            @if(count($list) > 0)
                                @foreach($list as $val)
                                    <tr>
                                        <td>{{$key}}</td>
                                        <td>{{$val}}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div>
        </div>


    </section><!-- /.content -->
@endsection

@section("script")
    <!-- Select2 -->
    <script src="{{asset('bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- InputMask -->
    <script src="{{asset('plugins/input-mask/jquery.inputmask.js') }}"></script>
    <script src="{{asset('plugins/input-mask/jquery.inputmask.date.extensions.js') }}"></script>
    <script src="{{asset('plugins/input-mask/jquery.inputmask.extensions.js') }}"></script>

    <!-- bootstrap datepicker -->
    <script src="{{asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- bootstrap color picker -->
    <script src="{{asset('bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
    <!-- bootstrap time picker -->
    <script src="{{asset('plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>

    <!-- iCheck 1.0.1 -->
    <script src="{{asset('plugins/iCheck/icheck.min.js') }}"></script>

    <!-- DataTables -->
    <script src="{{asset('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <!-- Page script -->
    <script>
        $(function () {
            $('#example2').dataTable({
                "bLengthChange": true, //开关，是否显示每页显示多少条数据的下拉框
                "aLengthMenu": [[10, 15, 20, -1], [10, 15, 20, "所有"]],//设置每页显示数据条数的下拉选项
                'iDisplayLength': 10, //每页初始显示5条记录
                'bFilter': false,  //是否使用内置的过滤功能（是否去掉搜索框）
                "bInfo": true, //开关，是否显示表格的一些信息(当前显示XX-XX条数据，共XX条)
                "bPaginate": true, //开关，是否显示分页器
                "bSort": false, //是否可排序 
                "oLanguage": {  //语言转换
                    "sInfo": "显示第 _START_ 至 _END_ 项结果，共_TOTAL_ 项",
                    "sLengthMenu": "每页显示 _MENU_ 项结果",
                    "oPaginate": {
                        "sFirst": "首页",
                        "sPrevious": "前一页",
                        "sNext": "后一页",
                        "sLast": "尾页"
                    }
                }
            });

            //Date picker
            /*$('#datepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                /!*汉化*!/
                language: "zh_CN",
                /!*日期格式*!/
                format:"yyyy-mm-dd hh:mm:ss",
            })*/
        })
    </script>
@endsection
