<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laravel Stats Tracker</title>

    <script type="text/javascript" src="{{ asset("{$stats_template_path}/jquery/dist/jquery.min.js") }}"></script>

    @yield('required-scripts-top')

    <link href="{{ asset("{$stats_template_path}/sb-admin-2/font-awesome-4.1.0/css/font-awesome.min.css") }}" rel="stylesheet" />
    <link href="{{ asset("{$stats_template_path}/sb-admin-2/css/plugins/dataTables.bootstrap.css") }}" rel="stylesheet" />
    <link href="{{ asset("{$stats_template_path}/sb-admin-2/css/plugins/metisMenu/metisMenu.min.css") }}" rel="stylesheet" />
    <link href="{{ asset("{$stats_template_path}/sb-admin-2/css/plugins/morris.css") }}" rel="stylesheet" />
    <link href="{{ asset("{$stats_template_path}/sb-admin-2/css/plugins/timeline.css") }}" rel="stylesheet" />
    <link href="{{ asset("{$stats_template_path}/sb-admin-2/css/bootstrap.min.css") }}" rel="stylesheet" />
    <link href="{{ asset("{$stats_template_path}/sb-admin-2/css/sb-admin-2.css") }}" rel="stylesheet" />
    <link href="{{ asset("{$stats_template_path}/sb-admin-2/css/sb-admin-2.css") }}" rel="stylesheet" />
    <link href="{{ asset("{$stats_template_path}/world-flags-sprite/stylesheets/flags16.css") }}" rel="stylesheet" />

    <link href="{{ asset("{$stats_template_path}/datatables/media/css/jquery.dataTables.min.css") }}" rel="stylesheet" />
</head>

<body>
<div id="wrapper">
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{route('tracker.stats.index')}}">Laravel Stats Tracker</a>
        </div>
        <!-- /.navbar-header -->

        <ul class="nav navbar-top-links navbar-right navbar-nav">
            <li {{ Session::get('tracker.stats.days') == '0' ? 'class="active"' : '' }}>
                <a href="{{route('tracker.stats.index')}}?days=0">Today</a>
            </li>

            <li {{ Session::get('tracker.stats.days') == '1' ? 'class="active"' : '' }}>
                <a href="{{route('tracker.stats.index')}}?days=1">1 day</a>
            </li>

            <li {{ Session::get('tracker.stats.days') == '7' ? 'class="active"' : '' }}>
                <a href="{{route('tracker.stats.index')}}?days=7">7 days</a>
            </li>

            <li {{ Session::get('tracker.stats.days') == '30' ? 'class="active"' : '' }}>
                <a href="{{route('tracker.stats.index')}}?days=30">30 days</a>
            </li>

            <li {{ Session::get('tracker.stats.days') == '365' ? 'class="active"' : '' }}>
                <a href="{{route('tracker.stats.index')}}?days=365">1 year</a>
            </li>
        </ul>
        <!-- /.navbar-top-links -->

        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                    <li>
                        <a href="{{route('tracker.stats.index')}}?page=visits" class="{{ Session::get('tracker.stats.page') =='visits' ? 'active' : '' }}" ><i class="fa fa-dashboard fa-fw"></i> Visits <span class="{{ Session::get('tracker.stats.page') =='visits' ? 'fa arrow' : '' }}"></span></a>
                    </li>
                    <li>
                        <a href="{{route('tracker.stats.index')}}?page=summary" class="{{ Session::get('tracker.stats.page') =='summary' ? 'active' : '' }}"><i class="fa fa-bar-chart-o fa-fw"></i> Summary <span class="{{ Session::get('tracker.stats.page') =='summary' ? 'fa arrow' : '' }}"></span></a>
                    </li>
                    <li>
                        <a href="{{route('tracker.stats.index')}}?page=users" class="{{ Session::get('tracker.stats.page') =='users' ? 'active' : '' }}"><i class="fa fa-user fa-fw"></i> Users <span class="{{ Session::get('tracker.stats.page') =='users' ? 'fa arrow' : '' }}"></span></a>
                    </li>
                    <li>
                        <a href="{{route('tracker.stats.index')}}?page=events" class="{{ Session::get('tracker.stats.page') =='events' ? 'active' : '' }}"><i class="fa fa-bolt fa-fw"></i> Events <span class="{{ Session::get('tracker.stats.page') =='events' ? 'fa arrow' : '' }}"></span></a>
                    </li>
                    <li>
                        <a href="{{route('tracker.stats.index')}}?page=errors" class="{{ Session::get('tracker.stats.page') =='errors' ? 'active' : '' }}"><i class="fa fa-exclamation fa-fw"></i>Errors <span class="{{ Session::get('tracker.stats.page') =='errors' ? 'fa arrow' : '' }}"></span></a>
                    </li>
                </ul>
                <!-- /#side-menu -->
            </div>
            <!-- /.sidebar-collapse -->
        </div>
        <!-- /.navbar-static-side -->
    </nav>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">{{$title}}</h2>
            </div>

            <div class="col-lg-12">
                @yield('page-contents')
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                @yield('page-secondary-contents')
            </div>
        </div>

        <!-- /.row -->
    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<!-- Core Scripts - Include with every page -->
<script src="{{ asset("{$stats_template_path}/sb-admin-2/js/bootstrap.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("{$stats_template_path}/sb-admin-2/js/plugins/metisMenu/metisMenu.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("{$stats_template_path}/sb-admin-2/js/plugins/morris/raphael.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("{$stats_template_path}/sb-admin-2/js/plugins/morris/morris.min.js") }}" type="text/javascript"></script>

<!-- SB Admin Scripts - Include with every page -->
<script src="{{ asset("{$stats_template_path}/sb-admin-2/js/sb-admin-2.js") }}" type="text/javascript"></script>
<script src="{{ asset("{$stats_template_path}/moment/min/moment.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("{$stats_template_path}/datatables.net/js/jquery.dataTables.min.js") }}" type="text/javascript"></script>

@yield('required-scripts-bottom')

<script>
    @yield('inline-javascript')
</script>
</body>

</html>
