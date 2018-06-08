@extends('layouts.app') @section('content')
 
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
 
    @if(count($accountsForUser))
    <script type="text/javascript">
 
        google.charts.load('current', {'packages':['corechart']});
 
        google.charts.setOnLoadCallback(drawChart);
 
        function drawChart() {
            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Expenses');
            data.addColumn('number', '€');
            data.addRows([
                @foreach($movement_categories as $c)
                    ['{{ $c->name }}', {{ number_format($total_by_category[$c->id - 1], 2, '.', '') }}],
                @endforeach
            ]);
 
            // Set chart options
            var options = {'title':'Monthly Expenses/Revenues',
                        'width':700,
                        'height':600};
 
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
    </script>

    <script type="text/javascript">
 
        google.charts.load('current', {'packages':['corechart']});
 
        google.charts.setOnLoadCallback(drawChart); 
        function drawChart() {
            // Create the data table.
            var data = google.visualization.arrayToDataTable([
                ['Genre',@foreach($movement_categories as $c) '{{ $c->name }}', @endforeach { role: 'annotation' } ],
                ['January', @foreach($movement_categories as $c) {{ number_format($total_by_category[$c->id - 1], 2, '.', '') }}, @endforeach ''],
                ['February',@foreach($movement_categories as $c) {{ number_format($total_by_category[$c->id - 1], 2, '.', '') }}, @endforeach ''],
                ['March', @foreach($movement_categories as $c) {{ number_format($total_by_category[$c->id - 1], 2, '.', '') }}, @endforeach ''],
                ['April', @foreach($movement_categories as $c) {{ number_format($total_by_category[$c->id - 1], 2, '.', '') }}, @endforeach ''],
                ['May', @foreach($movement_categories as $c) {{ number_format($total_by_category[$c->id - 1], 2, '.', '') }}, @endforeach ''],
                ['June', @foreach($movement_categories as $c) {{ number_format($total_by_category[$c->id - 1], 2, '.', '') }}, @endforeach ''],
                ['July', @foreach($movement_categories as $c) {{ number_format($total_by_category[$c->id - 1], 2, '.', '') }}, @endforeach ''],
                ['August', @foreach($movement_categories as $c) {{ number_format($total_by_category[$c->id - 1], 2, '.', '') }}, @endforeach ''],
                ['September', @foreach($movement_categories as $c) {{ number_format($total_by_category[$c->id - 1], 2, '.', '') }}, @endforeach ''],
                ['October', @foreach($movement_categories as $c) {{ number_format($total_by_category[$c->id - 1], 2, '.', '') }}, @endforeach ''],
                ['November', @foreach($movement_categories as $c) {{ number_format($total_by_category[$c->id - 1], 2, '.', '') }}, @endforeach ''],
                ['December', @foreach($movement_categories as $c) {{ number_format($total_by_category[$c->id - 1], 2, '.', '') }}, @endforeach ''],
            ]);

            var options = {
                width: 600,
                height: 400,
                legend: { position: 'top', maxLines: 3 },
                bar: { groupWidth: '75%' },
                isStacked: true,
            };
 
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.ColumnChart(document.getElementById('chart_div2'));
            chart.draw(data, options);
        }
    </script>
    @endif
 
 
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
 
            @if(session('msgglobal'))
    <div class ="alert alert-success">
        {{ session('msgglobal') }}
    </div>
@endif
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif
                    <img src="{{ asset('/storage/profiles/' . Auth::user()->profile_photo) }}" class="rounded float-left" alt="Imagem" style="border-radius: 3px; width: 200px; padding-right: 10px">
                    <div style="font-size: 20px">
                        <strong>User Name: </strong>{{ Auth::user()->name }}
                        <br>
                        <strong>Email: </strong>{{ Auth::user()->email }}
                        <br>
                        <strong>Phone Number: </strong> {{ Auth::user()->phone }}
                    </div>
                </div>
            </div>
        <br>
            <div class="card">
                <div class="card-header">Summary of My Financial Situation</div>
                <div class="card-body">
                    @if(count($accountsForUser))
                    <strong> TOTAL BALANCE OF ALL ACCOUNTS:</strong> <strong style="font-size: 20px"> {{ number_format($total, 2) }} € </strong><br><br>
                                       
                    <strong>SUMMARY INFO:</strong>
                    <br>
                    @foreach ($summary as $s)
                        <strong style="font-size: 20px"> {{ $s }} € <br></strong>
                    @endforeach
                    <br>
                    <strong>PERCENTAGE OF EACH ACCOUNT IN TOTAL BALANCE:</strong>
                    <br>
                    @foreach ($percentage as $p)
                    @if($total != 0)
                        <strong style="font-size: 20px">{{ $p }} % <br></strong>
                    @endif
                    @endforeach
                    <br>
                   
                    @else
                   
                    <strong> TOTAL BALANCE OF ALL ACCOUNTS:</strong> <strong style="font-size: 20px"> 0.00 € </strong><br><br>
 
                    <strong>SUMMARY INFO:</strong> <strong style="font-size: 20px"> 0.00 € </strong> <br><br>
 
                    <strong>PERCENTAGE OF EACH ACCOUNT IN TOTAL BALANCE:</strong> <strong style="font-size: 20px"> 0% </strong> <br><br>
 
                    <strong> TOTAL REVENUES AND EXPENSES BY CATEGORY:</strong> <strong style="font-size: 20px"> 0.00 € </strong><br>
 
                    @endif
                   
                </div>
 
 
                @if(count($accountsForUser))
                <!-- Charts -->
                    <label for="date1" class="col-md-4 col-form-label text-md-Left">{{ __('Data Inicio') }}</label>
                    <div class="col-md-6">
                        <input id="date1" type="date" class="form-control{{ $errors->has('date1') ? ' is-invalid' : '' }}" name="date1" optional>
                        @if ($errors->has('date1'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('date1') }}</strong>
                            </span>
                        @endif
                    <br>

                    <label for="date2" class="col-md-4 col-form-label text-md-Right">{{ __('Data Fim') }}</label>
                        <input id="date2" type="date" class="form-control{{ $errors->has('date2') ? ' is-invalid' : '' }}" name="date2" optional>
                        @if ($errors->has('date2'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('date2') }}</strong>
                            </span>
                        @endif
                    <br>

                    <a class="btn btn-xs btn-success" href="{{ action('HomeController@index', Auth::user()->id ) }}"> Search</a>
 
 
                <div id="chart_div"></div>
                <div id="chart_div2"></div>
               
                <!-- end charts -->
                @endif
                </div>
            </div>
        </div>
    </div>
    @endsection