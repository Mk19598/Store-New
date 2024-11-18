@push('scripts')
    <script src="{{ url('asset\plugins\charts.min.js') }}"></script>
@endpush

<div class="row g-4 mb-4">
    <div class="col-12 col-lg-6">
        <div class="app-card app-card-chart h-100 shadow-sm">
            <div class="app-card-header p-3">
                <div class="row justify-content-between align-items-center">
                    <div class="col-auto">
                        <h4 class="app-card-title">Orders Chart </h4>
                    </div>
                </div>
            </div>

            <div class="app-card-body p-3 p-lg-4">
                <div class="chart-container">
                    <canvas id="canvas-linechart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="app-card app-card-chart h-100 shadow-sm">
            <div class="app-card-header p-3">
                <div class="row justify-content-between align-items-center">
                    <div class="col-auto">
                        <h4 class="app-card-title">Status Chart</h4>
                    </div>
                </div>
            </div>
            <div class="app-card-body p-3 p-lg-4">
                {{-- <div class="mb-3 d-flex">
                    <select class="form-select form-select-sm ms-auto d-inline-flex w-auto">
                        <option value="1" selected>This week</option>
                        <option value="2">Today</option>
                        <option value="3">This Month</option>
                        <option value="3">This Year</option>
                    </select>
                </div> --}}
                <div class="chart-container">
                    <canvas id="canvas-barchart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    window.chartColors = {
        orange: '#F28C28',
        gray: '#a9b5c9',
        green: '#9FE2BF',
        text: '#252930',
        border: '#e7e9ed'
    };


    var randomDataPoint = function(){ return Math.round(Math.random()*10000)};

    //Chart.js Line Chart Example 

    var OrdersChartConfig = {
        type: 'line',

        data: {
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            
            datasets: [{
                label: 'Dukkan ',
                fill: false,
                backgroundColor: window.chartColors.orange,
                borderColor: window.chartColors.orange,
                data: [
                    "{{ $daysOfWeekCounts['Monday']['Dukkan_order_count'] }}",
                    "{{ $daysOfWeekCounts['Tuesday']['Dukkan_order_count'] }}",
                    "{{ $daysOfWeekCounts['Wednesday']['Dukkan_order_count'] }}",
                    "{{ $daysOfWeekCounts['Thursday']['Dukkan_order_count'] }}",
                    "{{ $daysOfWeekCounts['Friday']['Dukkan_order_count'] }}",
                    "{{ $daysOfWeekCounts['Saturday']['Dukkan_order_count'] }}",
                    "{{ $daysOfWeekCounts['Sunday']['Dukkan_order_count'] }}",
                ],
            }, 
            {
                label: 'Woocommerce ',
                borderDash: [3, 5],
                backgroundColor: window.chartColors.gray,
                borderColor: window.chartColors.gray,
                
                data: [
                    "{{ $daysOfWeekCounts['Monday']['woocommerce_order_count'] }}",
                    "{{ $daysOfWeekCounts['Tuesday']['woocommerce_order_count'] }}",
                    "{{ $daysOfWeekCounts['Wednesday']['woocommerce_order_count'] }}",
                    "{{ $daysOfWeekCounts['Thursday']['woocommerce_order_count'] }}",
                    "{{ $daysOfWeekCounts['Friday']['woocommerce_order_count'] }}",
                    "{{ $daysOfWeekCounts['Saturday']['woocommerce_order_count'] }}",
                    "{{ $daysOfWeekCounts['Sunday']['woocommerce_order_count'] }}",
                ],
                fill: false,
            },
            {
                label: 'Total ',
                type: 'line',
                backgroundColor: window.chartColors.green,
                borderColor: window.chartColors.green,
                
                data: [
                    "{{ $daysOfWeekCounts['Monday']['order_count'] }}",
                    "{{ $daysOfWeekCounts['Tuesday']['order_count'] }}",
                    "{{ $daysOfWeekCounts['Wednesday']['order_count'] }}",
                    "{{ $daysOfWeekCounts['Thursday']['order_count'] }}",
                    "{{ $daysOfWeekCounts['Friday']['order_count'] }}",
                    "{{ $daysOfWeekCounts['Saturday']['order_count'] }}",
                    "{{ $daysOfWeekCounts['Sunday']['order_count'] }}",
                ],                   
                fill: false,
            },
        ]
        },
        options: {
            responsive: true,	
            aspectRatio: 1.5,
            
            legend: {
                display: true,
                position: 'bottom',
                align: 'end',
            },
            
            title: {
                display: true,
                // text: 'Chart.js Line Chart Example', 
            }, 
            tooltips: {
                mode: 'index',
                intersect: false,
                titleMarginBottom: 10,
                bodySpacing: 10,
                xPadding: 16,
                yPadding: 16,
                borderColor: window.chartColors.border,
                borderWidth: 1,
                backgroundColor: '#fff',
                bodyFontColor: window.chartColors.text,
                titleFontColor: window.chartColors.text,

                callbacks: {
                    label: function(tooltipItem, data) {
                        if (parseInt(tooltipItem.value) >= 1000) {
                            return  tooltipItem.value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        } else {
                            return  tooltipItem.value;
                        }
                    }
                },

            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    display: true,
                    gridLines: {
                        drawBorder: false,
                        color: window.chartColors.border,
                    },
                    scaleLabel: {
                        display: false,
                    
                    }
                }],
                yAxes: [{
                    display: true,
                    gridLines: {
                        drawBorder: false,
                        color: window.chartColors.border,
                    },
                    scaleLabel: {
                        display: false,
                    },
                    ticks: {
                        beginAtZero: true,
                        userCallback: function(value, index, values) {
                            return value.toLocaleString();   //Ref: https://stackoverflow.com/questions/38800226/chart-js-add-commas-to-tooltip-and-y-axis
                        }
                    },
                }]
            }
        }
    };

    // Chart.js Bar Chart Example 

    var barChartConfig = {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Processing', 'Completed', 'Refunded', 'Cancelled', 'Failed','shipped','default'],
            datasets: [{
                label: 'Orders',
                backgroundColor: [
                    '#d5dbdb',  
                    '#aed6f1',  
                    '#a3e4d7',  
                    '#f9e79f', 
                    '#ec7063',  
                    'rgba(255, 99, 71, 0.6)',
                    '#d7bde2',    
                    '#85929e',    
                ],
                data: [
                    "{{ $statusCounts['pending'] }}",
                    "{{ $statusCounts['processing'] }}",
                    "{{ $statusCounts['completed'] }}",
                    "{{ $statusCounts['refunded'] }}",
                    "{{ $statusCounts['cancelled'] }}",
                    "{{ $statusCounts['failed'] }}",
                    "{{ $statusCounts['shipped'] }}",
                    "{{ $statusCounts['default'] }}",
                ]
            }]
        },
        options: {
            responsive: true,
            aspectRatio: 1.5,
            legend: { position: 'bottom' },
            tooltips: { mode: 'index', intersect: false },
            scales: {
                xAxes: [{ display: false }],
                yAxes: [{ display: false }]
            }
        }
    };

    // Generate charts on load
    window.addEventListener('load', function(){
        
        var lineChart = document.getElementById('canvas-linechart').getContext('2d');
        window.myLine = new Chart(lineChart, OrdersChartConfig);
        
        var barChart = document.getElementById('canvas-barchart').getContext('2d');
        window.myBar = new Chart(barChart, barChartConfig);
        
    });	
</script>