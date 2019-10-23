@extends('layout/main')

@section('content')
    <section class="content">
        <div class="box">
            <div class="box-body">
                <a class="btn btn-app">
                    <i class="fa fa-edit"></i> Edit
                </a>
                <a class="btn btn-app">
                    <i class="fa fa-play"></i> Play
                </a>
                <a class="btn btn-app">
                    <i class="fa fa-repeat"></i> Repeat
                </a>
                <a class="btn btn-app">
                    <i class="fa fa-pause"></i> Pause
                </a>
                <a class="btn btn-app">
                    <i class="fa fa-save"></i> Save
                </a>
                <a class="btn btn-app">
                    <span class="badge bg-yellow">3</span>
                    <i class="fa fa-bullhorn"></i> Notifications
                </a>
                <a class="btn btn-app">
                    <span class="badge bg-green">300</span>
                    <i class="fa fa-barcode"></i> Products
                </a>
                <a class="btn btn-app">
                    <span class="badge bg-purple">891</span>
                    <i class="fa fa-users"></i> Users
                </a>
                <a class="btn btn-app">
                    <span class="badge bg-teal">67</span>
                    <i class="fa fa-inbox"></i> Orders
                </a>
                <a class="btn btn-app">
                    <span class="badge bg-aqua">12</span>
                    <i class="fa fa-envelope"></i> Inbox
                </a>
                <a class="btn btn-app">
                    <span class="badge bg-red">531</span>
                    <i class="fa fa-heart-o"></i> Likes
                </a>
            </div>
            <!-- /.box-body -->
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Redis</h3>
                    </div><!-- /.box-header -->

                    <div class="box-body table-responsive">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>键名</th>
                                <th>记录条数</th>

                            </tr>
                            </thead>

                            <tbody>
                            @foreach($list as $k => $val)
                                <tr>
                                    <td>{{ $val['keyName'] }}</td>
                                    <td>{{ $val['size'] }}</td>

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
        $(function () {
            $('#example2').dataTable({
                "bLengthChange": true, //开关，是否显示每页显示多少条数据的下拉框
                "aLengthMenu": [[20, 15, 10, -1], [20, 15, 10, "所有"]],//设置每页显示数据条数的下拉选项
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
