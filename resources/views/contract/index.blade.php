@extends('layout/main')

@section('content')

    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">数据表搜索条件</h3>
                    </div>
                    <form action="{{url('contract/index')}}" method="get">
                        <div class="box-body">

                            <div class="form-group col-md-6">
                                <div class="input-group">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-danger">数据表</button>
                                    </div>
                                    <select class="form-control" name="table">
                                        @if(count($tables) > 0)
                                            @foreach($tables as $table)
                                                <option value="{{$table->table_name}}">{{$table->table_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <!-- /.input group -->
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <div class="input-group">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-success">时间类型</button>
                                    </div>
                                    <select class="form-control col-md-3" name="interval">
                                        <option value="1440" @if($interval=='1440') selected @endif>1day</option>
                                        <option value="360" @if($interval=='360') selected @endif>6hour</option>
                                        <option value="60" @if($interval=='60') selected @endif>1hour</option>
                                        <option value="30" @if($interval=='30') selected @endif>30min</option>
                                        <option value="15" @if($interval=='15') selected @endif>15min</option>
                                        <option value="5" @if($interval=='5') selected @endif>5min</option>
                                        <option value="1" @if($interval=='1') selected @endif>1min</option>
                                    </select>
                                </div>
                            </div>

                            <!-- /.form group -->
                            <!-- Date -->
                            <div class="form-group col-md-6">
                                <div class="input-group date">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-danger">数据源</button>
                                    </div>
                                    <select class="form-control" name="exp_name">
                                        <option value="bitfinex">bitfinex</option>
                                        <option value="binance">binance</option>
                                        <option value="okexspot">okexspot</option>
                                        <option value="okexswap">okexswap</option>
                                        <option value="zb">zb</option>
                                    </select>
                                </div>
                                <!-- /.input group -->
                            </div>
                            {{--<div class="form-group col-md-6">
                                <div class="input-group">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-success">时间类型</button>
                                    </div>
                                    <select class="form-control col-md-3" name="interval">
                                        <option value="1440" @if($interval=='1440') selected @endif>1day</option>
                                        <option value="360" @if($interval=='360') selected @endif>6hour</option>
                                        <option value="60" @if($interval=='60') selected @endif>1hour</option>
                                        <option value="30" @if($interval=='30') selected @endif>30min</option>
                                        <option value="15" @if($interval=='15') selected @endif>15min</option>
                                        <option value="5" @if($interval=='5') selected @endif>5min</option>
                                        <option value="1" @if($interval=='1') selected @endif>1min</option>
                                    </select>
                                </div>
                            </div>--}}
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">查询数据并预览</button>
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
                                <th>数据源</th>
                                <th>交易对</th>
                                <th>成交量</th>
                                <th>开盘价</th>
                                <th>最高价</th>
                                <th>最低价</th>
                                <th>收盘价</th>
                                <th>开盘时间</th>
                            </tr>
                            </thead>

                            <tbody id="redis_body">
                            @if(count($list) > 0)
                                @foreach($list as $val)
                                    <tr>
                                        <td>{{ $val->exp_name }}</td>
                                        <td>{{ $val->pair }}</td>
                                        <td>{{ $val->volume }}</td>
                                        <td>{{ $val->open }}</td>
                                        <td>{{ $val->high }}</td>
                                        <td>{{ $val->low }}</td>
                                        <td>{{ $val->close }}</td>
                                        <td>{{ $val->open_time }}</td>
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
