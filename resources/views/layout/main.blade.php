<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>K线数据中心</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

        @include("layout/head")

    </head>
    <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        @include("layout/header")

        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            @include("layout/left")

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Dashboard
                        <small>Control panel</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Dashboard</li>
                    </ol>
                </section>

                <!-- Main content -->
                @yield("content")

            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

        @include("layout/script")

    </body>
</html>
