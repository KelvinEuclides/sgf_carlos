@extends('layouts.app')
@section('page-title')
    {{__('Dashboard')}}
@endsection

@push('script-page')
    <script>
        var options = {
            series: [
                {
                    name: "{{__('Unpaid')}}",
                    data: {!! json_encode($billChartData['data']['unpaid']) !!}
                }, {
                    name: "{{__('Paid')}}",
                    data: {!! json_encode($billChartData['data']['paid']) !!}
                }, {
                    name: "{{__('Partial Paid')}}",
                    data: {!! json_encode($billChartData['data']['partial']) !!}
                }, {
                    name: "{{__('Due')}}",
                    data: {!! json_encode($billChartData['data']['due']) !!}
                },

            ],
            chart: {
                height: 350,
                type: 'line',
                dropShadow: {
                    enabled: true,
                    color: '#000',
                    top: 18,
                    left: 7,
                    blur: 10,
                    opacity: 0.2
                },
                toolbar: {
                    show: false
                }
            },
            colors: ['#FF5630', '#36B37E', '#00B8D9', '#FFAB00'],
            dataLabels: {
                enabled: true,
            },
            stroke: {
                curve: 'smooth'
            },
            title: {
                text: '',
                align: 'left'
            },
            grid: {
                borderColor: '#e7e7e7',
                row: {
                    colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            markers: {
                size: 1
            },
            xaxis: {
                categories: {!! json_encode($billChartData['month']) !!},
                title: {
                    text: 'Month'
                }
            },
            yaxis: {
                title: {
                    text: '{{__('Amount')}}'
                },

            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                floating: true,
                offsetY: -25,
                offsetX: -5
            }
        };
        var chart = new ApexCharts(document.querySelector("#chart-sales"), options);
        chart.render();
    </script>
@endpush
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h6>{{__('Current year').' - '.date('Y')}}</h6>
                    <div class="">
                        <div id="chart-sales"  class="chart-scroll" height="250" height="300"></div>
                    </div>
                </div>
            </div>
        </div>
@endsection


