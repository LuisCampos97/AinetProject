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
            var options = {'title':'2017 Monthly Expenses/Revenues',
                        'width':700,
                        'height':600};
 
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
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
                    <label for="date" class="col-md-4 col-form-label text-md-Left">{{ __('Date') }}</label>
                    <div class="col-md-6">
                        <input id="date" type="date" class="form-control{{ $errors->has('date') ? ' is-invalid' : '' }}" name="date" optional>
                        @if ($errors->has('date'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('date') }}</strong>
                            </span>
                        @endif
                    <br>
                    <button type="submit" class="searchButton">
                        <i class="fa fa-search"></i> Search
                    </button>
 
                    </div>
 
                <div id="chart_div"></div>
               
                <!-- end charts -->
                @endif
                </div>
            </div>
        </div>
    </div>
    @endsection