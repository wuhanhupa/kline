@extends('layout/main')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Redis(K线数据列表)</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>redis键名</th>
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
