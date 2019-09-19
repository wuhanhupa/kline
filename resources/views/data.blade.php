@extends('layout/main')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">redie组装数据预览</h3>
                        <h4>
                            <button class="btn btn-danger" id="allSubmit">全部写入redis</button>
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
                                <th>操作</th>
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
                                    <td><a href="#">详情</a></td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div>
        </div>

    </section><!-- /.content -->
    <script src="{{asset("/js/jquery.min.js")}}" type="text/javascript"></script>
    <script>
        $("#allSubmit").click(function () {
            $.ajax({
                type: "post",
                url: "/writeRedis",
                data: {
                    tableName: "{{$tableName}}",
                    pair: "{{$pair}}",
                    interval: "{{$interval}}",
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
    </script>
@endsection


