@extends('layouts.app')

@section('title', $title)

@section('content')

    <div>
                {{-- Table Card --}}
        <div class="card">
            <div class="card-body">
                <div class="data">

                    <div class="col-md-10">
                        <h5> {{ ucwords(__('crons Logs'))}} </h5>
                    </div>

                    <div class="table-responsive" >
                        <table class="table table-striped" id="Logs-list-table">
                            <thead>
                                <tr>
                                    <th align="center">#</th>
                                    <th align="center">{{ ucwords(__('context')) }} </th>
                                    <th align="center">{{ ucwords(__('message')) }} </th>
                                    <th align="center">{{ ucwords(__('level')) }} </th>
                                    <th align="center">{{ ucwords(__('created at')) }} </th>
                                </tr>
                            </thead>
                    
                            <tbody>
                                @foreach ($crons_log as $key => $crons_logs)
                                    <tr>
                                        <td align="center"> {{ $key+1 }} </td>
                                        <td align="center"> <span>{{ ucwords(@$crons_logs->context) }}</span></td>
                                        <td align="center"> <span>{{ ucwords(@$crons_logs->message) }}</span></td>

                                        @switch(@$crons_logs->level)
                                            @case('error')
                                                    <td align="center"> <span class="badge bg-cancelled">{{ ucwords(@$crons_logs->level) }}</span></td>
                                                @break
                                            @case('success')
                                                    <td align="center"> <span class="badge bg-completed">{{ ucwords(@$crons_logs->level) }}</span></td>
                                                @break
                                            @default
                                                <td align="center"> <span class="badge bg-processing">{{ ucwords(@$crons_logs->level) }}</span></td>
                                        @endswitch

                                        <td align="center"> <span>{{ ucwords(@$crons_logs->created_at_format) }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>               
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        
         .badge.bg-completed {background-color: #28a745 !important;}

        .badge.bg-processing {background-color: #0275d8;}

        .badge.bg-shipped {background-color: #8051d7;}

        .badge.bg-cancelled {background-color: #dc3545;}

        .badge.bg-pending {background-color: #abab32;}

        .badge.bg-refunded {background-color: #A52A2A;}

        .badge.bg-Packed {background-color: #2471a3;}
    </style>
@endpush

@push('scripts')

    <script>
            // Datatables
        $(document).ready( function () {

            $('#Logs-list-table').DataTable({
                columnDefs: [                   // Columns
                    { targets: [1, 2], className: 'dt-body-center' },  
                    { targets: [0, 3], className: 'dt-body-center' } 
                ],

                headerCallback: function(thead, data, start, end, display) { // Head
                    $(thead).find('th').addClass('dt-head-center');
                },
            });
            
        });
    </script>
@endpush
