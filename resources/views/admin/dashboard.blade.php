    @extends('admin.layouts.app')

    @section('content')

    <div class="content">
                <!-- Animated -->
                <div class="animated fadeIn">
                    <!-- Widgets  -->
                    <div class="row">
                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="stat-widget-five">
                                        <div class="stat-icon dib flat-color-1">
                                            <i class="pe-7s-cash"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="text-left dib">
                                                <div class="stat-text"><span class="count">{{ number_format($revenue, 0, ',', '.') }} đ</span></div>
                                                <div class="stat-heading">Revenue</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="stat-widget-five">
                                        <div class="stat-icon dib flat-color-2">
                                            <i class="pe-7s-cart"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="text-left dib">
                                                <div class="stat-text"><span class="count">{{ $orderCount }}</span></div>
                                                <div class="stat-heading">Sales</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="stat-widget-five">
                                        <div class="stat-icon dib flat-color-3">
                                            <i class="pe-7s-browser"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="text-left dib">
                                                <div class="stat-text"><span class="count">{{ $productCount }}</span></div>
                                                <div class="stat-heading">Templates</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="stat-widget-five">
                                        <div class="stat-icon dib flat-color-4">
                                            <i class="pe-7s-users"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="text-left dib">
                                                <div class="stat-text"><span class="count">{{ $userCount }}</span></div>
                                                <div class="stat-heading">Clients</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Widgets -->
                    <!--  Traffic  -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="box-title">Traffic </h4>
                                </div>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="card-body">
                                            <!-- <canvas id="TrafficChart"></canvas>   -->
                                            <div id="traffic-chart" class="ct-chart traffic-chart" style="height: 335px;"></div>


                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="card-body">
                                            <div class="progress-box progress-1">
                                                <h4 class="por-title">Visits</h4>
                                                <div class="por-txt">96,930 Users (40%)</div>
                                                <div class="progress mb-2" style="height: 5px;">
                                                    <div class="progress-bar bg-flat-color-1" role="progressbar" style="width: 40%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="progress-box progress-2">
                                                <h4 class="por-title">Bounce Rate</h4>
                                                <div class="por-txt">3,220 Users (24%)</div>
                                                <div class="progress mb-2" style="height: 5px;">
                                                    <div class="progress-bar bg-flat-color-2" role="progressbar" style="width: 24%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="progress-box progress-2">
                                                <h4 class="por-title">Unique Visitors</h4>
                                                <div class="por-txt">29,658 Users (60%)</div>
                                                <div class="progress mb-2" style="height: 5px;">
                                                    <div class="progress-bar bg-flat-color-3" role="progressbar" style="width: 60%;" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                            <div class="progress-box progress-2">
                                                <h4 class="por-title">Targeted  Visitors</h4>
                                                <div class="por-txt">99,658 Users (90%)</div>
                                                <div class="progress mb-2" style="height: 5px;">
                                                    <div class="progress-bar bg-flat-color-4" role="progressbar" style="width: 90%;" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div> <!-- /.card-body -->
                                    </div>
                                </div> <!-- /.row -->
                                <div class="card-body"></div>
                            </div>
                        </div><!-- /# column -->
                    </div>
                    <!--  /Traffic -->
                </div>
                <!-- .animated -->
            </div>
            <!-- /.content -->
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (document.querySelector('#traffic-chart')) {
        new Chartist.Line('#traffic-chart', {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            series: [
                [13000, 18000, 35000, 18000, 25000, 26000, 22000, 20000, 18000, 35000, 18000, 25000],
                [15000, 23000, 15000, 30000, 20000, 31000, 15000, 15000, 23000, 15000, 30000, 20000],
                [25000, 15000, 38000, 25500, 15000, 22500, 30000, 25000, 15000, 38000, 25500, 15000]
            ]
        }, {
            low: 0,
            showArea: true,
            showLine: false,
            showPoint: false,
            fullWidth: true,
            axisX: {
                showGrid: true
            }
        });
    }
});
</script>
@endpush


 @endsection
