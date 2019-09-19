@extends('layout/main')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">TED数据库</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>交易对</th>
                                <th>时间类型</th>
                                <th>记录条数</th>
                                <th>操作</th>
                            </tr>
                            </thead>

                            <tbody>
                                @foreach($list as $k => $val)
                                    <tr>
                                        <td>{{ $val->pair }}</td>
                                        <td>{{ $val->interval }}</td>
                                        <td>{{ $val->nums }}</td>
                                        <td> <a href="/handleRedisData?tableName={{$tableName}}&&pair={{$val->pair}}&&interval={{$val->interval}}"> 组装redis数据并预览 </a> </td>
                                    </tr>
                                @endforeach
                            </tbody>

                            <tfoot>
                            <tr>
                                <th>Rendering engine</th>
                                <th>Browser</th>
                                <th>Platform(s)</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->

            </div>
        </div>

    </section><!-- /.content -->

@endsection
