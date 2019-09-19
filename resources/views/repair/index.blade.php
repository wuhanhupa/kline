@extends('layout/main')

@section('content')

    <section class="content">

        <div class="row">
            <div class="col-md-12">

                <div class="box box-danger">
                    <div class="box-header">
                        <h3 class="box-title">redis搜索条件</h3>
                    </div>
                    <form action="{{url('repair/index')}}" method="get">
                        <div class="box-body">
                            <input type="hidden" name="_method" value="{{csrf_token()}}"/>
                            <!-- Date dd/mm/yyyy -->
                            <div class="form-group">
                                <label>时间戳区间（分值）:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" name="start" class="form-control" @if($start) value="{{$start}}" @endif required/>
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->

                            <!-- Date mm/dd/yyyy -->
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" name="end" class="form-control" @if($end) value="{{$end}}" @endif required/>
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->

                            <!-- phone mask -->
                            <div class="form-group">
                                <label>交易对:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>

                                    <select class="form-control" name="pair">
                                        <option value="btc_usdt" @if($pair=='btc_usdt') selected @endif>BTC_USDT</option>
                                        <option value="eth_usdt" @if($pair=='eth_usdt') selected @endif>ETH_USDT</option>
                                        <option value="eos_usdt" @if($pair=='eos_usdt') selected @endif>EOS_USDT</option>
                                        <option value="ltc_usdt" @if($pair=='ltc_usdt') selected @endif>LTC_USDT</option>
                                        <option value="xrp_usdt" @if($pair=='xrp_usdt') selected @endif>XRP_USDT</option>
                                    </select>
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->

                            <!-- phone mask -->
                            <div class="form-group">
                                <label>时间类型:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <select class="form-control" name="interval">
                                        <option value="1" @if($interval=='1') selected @endif>1min</option>
                                        <option value="5" @if($interval=='5') selected @endif>5min</option>
                                        <option value="15" @if($interval=='15') selected @endif>15min</option>
                                        <option value="30" @if($interval=='30') selected @endif>30min</option>
                                        <option value="60" @if($interval=='60') selected @endif>1hour</option>
                                        <option value="360" @if($interval=='360') selected @endif>6hour</option>
                                        <option value="1440" @if($interval=='1440') selected @endif>1day</option>
                                    </select>
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                            <!-- IP mask -->
                            <div class="form-group">
                                <div class="input-group">
                                    <button type='submit' class="btn btn-primary" id="mySearch">查询当前redis数据</button>
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                        </div><!-- /.box-body -->
                    </form>
                </div><!-- /.box -->
            </div><!-- /.col (left) -->
        </div><!-- /.row -->


        <div class="row">
            <div class="col-md-12">

                <div class="box box-danger">
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
                            <!-- phone mask -->
                            <div class="form-group">
                                <label>选择数据源:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <select class="form-control" name="yuan">
                                        <option value="bitfinex">bitfinex</option>
                                        <option value="binance">binance</option>
                                        <option value="okexspot">okexspot</option>
                                        <option value="okexswap">okexswap</option>
                                        <option value="zb">zb</option>
                                    </select>
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->

                            <!-- IP mask -->
                            <div class="form-group">
                                <div class="input-group">
                                    <button type='submit' class="btn btn-primary" id="mySearch">提交覆盖REDIS</button>
                                </div><!-- /.input group -->
                            </div><!-- /.form group -->
                        </div><!-- /.box-body -->
                    </form>
                </div><!-- /.box -->
            </div><!-- /.col (left) -->
        </div><!-- /.row -->

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
    <script src="{{asset("/js/jquery.min.js")}}" type="text/javascript"></script>
    <!-- InputMask -->
    <script src="{{asset("/js/plugins/input-mask/jquery.inputmask.js")}}" type="text/javascript"></script>
    <script src="{{asset("/js/plugins/input-mask/jquery.inputmask.date.extensions.js")}}"
            type="text/javascript"></script>
    <script src="{{asset("/js/plugins/input-mask/jquery.inputmask.extensions.js")}}" type="text/javascript"></script>
    <!-- date-range-picker -->
    <script src="{{asset("/js/plugins/daterangepicker/daterangepicker.js")}}" type="text/javascript"></script>
    <!-- bootstrap color picker -->
    <script src="{{asset("/js/plugins/colorpicker/bootstrap-colorpicker.min.js")}}" type="text/javascript"></script>
    <!-- bootstrap time picker -->
    <script src="{{asset("js/plugins/timepicker/bootstrap-timepicker.min.js")}}" type="text/javascript"></script>
    <script type="text/javascript">
        @if(session("msg"))
            alert({{session("msg")}});
        @endif

        $(function () {
            //Datemask dd/mm/yyyy
            $("#datemask").inputmask("dd/mm/yyyy", {"placeholder": "dd/mm/yyyy"});
            //Datemask2 mm/dd/yyyy
            $("#datemask2").inputmask("mm/dd/yyyy", {"placeholder": "mm/dd/yyyy"});
            //Money Euro
            $("[data-mask]").inputmask();

            //Date range picker
            $('#reservation').daterangepicker();
            //Date range picker with time picker
            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                format: 'MM/DD/YYYY h:mm A'
            });
            //Date range as a button
            $('#daterange-btn').daterangepicker(
                {
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                        'Last 7 Days': [moment().subtract('days', 6), moment()],
                        'Last 30 Days': [moment().subtract('days', 29), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                    },
                    startDate: moment().subtract('days', 29),
                    endDate: moment()
                },
                function (start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                }
            );

            //iCheck for checkbox and radio inputs
            $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
                checkboxClass: 'icheckbox_minimal',
                radioClass: 'iradio_minimal'
            });
            //Red color scheme for iCheck
            $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
                checkboxClass: 'icheckbox_minimal-red',
                radioClass: 'iradio_minimal-red'
            });
            //Flat red color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-red',
                radioClass: 'iradio_flat-red'
            });

            //Colorpicker
            $(".my-colorpicker1").colorpicker();
            //color picker with addon
            $(".my-colorpicker2").colorpicker();

            //Timepicker
            $(".timepicker").timepicker({
                showInputs: false
            });
        });
    </script>
@endsection
