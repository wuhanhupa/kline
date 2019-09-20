@extends('layout/main')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">redie组装数据预览</h3>
                        <h4>
                            {{--<button class="btn btn-danger" id="allSubmit">全部写入redis</button>--}}
                        </h4>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>数据源</th>
                                <th>交易对</th>
                                <th>interval</th>
                                <th>组装后的数据</th>
                                <th>分值</th>
                                <th>分值转日期</th>
                                {{--<th>操作</th>--}}
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($data as $k => $val)
                                <tr>
                                    <td>{{ $val['exp_name'] }}</td>
                                    <td>{{ $val['pair'] }}</td>
                                    <td>{{ $val['interval'] }}</td>
                                    <td>{{ $val['data_json'] }}</td>
                                    <td>{{ $val['score'] }}</td>
                                    <td>{{ $val['date'] }}</td>
                                    {{--<td><a href="#">详情</a></td>--}}
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

@section("script")
    <!-- DataTables -->
    <script src="{{asset('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <!-- Page script -->
    <script>

        $("#allSubmit").click(function () {
            $.ajax({
                type: "post",
                url: "/writeRedis",
                data: {
                    tableName: "{{$tableName}}",
                    pair: "{{$pair}}",
                    interval: "{{$interval}}",
                    expName: "{expName}",
                    '_token': "{{csrf_token()}}"
                },
                success: function (res) {
                    if(res.code == 0) {
                        alert("写入成功");
                    } else {
                        alert("error");
                    }
                }
            });
        });

        $(function () {
            $('#example2').dataTable({
                "bLengthChange": true, //开关，是否显示每页显示多少条数据的下拉框
                "aLengthMenu": [[20, 15, 5, -1], [20, 15, 10, "所有"]],//设置每页显示数据条数的下拉选项
                'iDisplayLength': 20, //每页初始显示5条记录
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
        })
    </script>
@endsection
