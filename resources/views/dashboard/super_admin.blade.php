@extends('layouts.app')
@section('page-title')
    {{__('Dashboard')}}
@endsection
@push('script-page')
    <script>
        var SalesChart = {
            series: [
                {
                    name: "{{__('Subscriber')}}",
                    data: {!! json_encode($chartData['data']) !!}
                }
            ],
            colors: ['#36B37E', '#FF5630'],
            chart: {
                type: 'bar',
                height: 430
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    dataLabels: {
                        position: 'top',
                    },
                }
            },
            dataLabels: {
                enabled: true,
                offsetX: -6,
                style: {
                    fontSize: '12px',
                    colors: ['#fff']
                }
            },
            stroke: {
                show: true,
                width: 1,
                colors: ['#fff']
            },
            xaxis: {
                categories:{!! json_encode($chartData['label']) !!},
            },
        };
        var sales = new ApexCharts(document.querySelector("#subscriber-chart"), SalesChart);
        sales.render();

    </script>
@endpush
@section('content')
    <div class="row">
        <div class="col">
            <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-muted mb-1">{{__('Total Users')}}</h6>
                            <span class="h5 font-weight-bold mb-0 ">{{$user->total_user}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-muted mb-1">{{__('Total Subscriber')}}</h6>
                            <span class="h5 font-weight-bold mb-0 ">{{$user->total_subscriber}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-muted mb-1">{{__('Total Subscriber Amount')}}</h6>
                            <span class="h5 font-weight-bold mb-0 ">{{\Auth::user()->priceFormat($user['total_subscriber_price'])}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <h4 class="h4 font-weight-400">{{__('Subscriber')}}</h4>
            <div class="card bg-none">
                <div class="chart">
                    <div id="subscriber-chart" data-color="primary"  class="p-3"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
