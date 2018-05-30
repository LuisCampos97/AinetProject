@extends('layouts.app') @section('content')

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
                <div class="card-header">Account</div>
                <div class="card-body">
                    <a class="btn btn-xs btn-info" href="{{ action('AccountController@accountsForUser', Auth::user()->id) }}">List of my Accounts</a><br><br>
                    <a class="btn btn-xs btn-success" href="{{ action('AccountController@createAccount') }}">Add Account</a> <br><br>
                    @if(count($accountsForUser))
                    <strong> TOTAL BALANCE OF ALL ACCOUNTS:</strong> <strong style="font-size: 20px"> {{ number_format($total, 2) }} € </strong><br><br>
                    @else
                    <strong> TOTAL BALANCE OF ALL ACCOUNTS:</strong> <strong style="font-size: 20px"> 0.00 € </strong><br><br>
                    @endif
                    <strong>SUMMARY INFO:</strong>
                    <br>
                    @foreach ($summary as $s)
                        <strong style="font-size: 20px"> {{ $s }} <br></strong>
                    @endforeach
                    <br>
                    <strong>PERCENTAGE OF EACH ACCOUNT IN TOTAL BALANCE:</strong>
                    <br>
                    @foreach ($percentage as $p)
                    @if($total != 0)
                        <strong style="font-size: 20px">{{ $p }} % <br></strong>
                    @endif
                    @endforeach
                </div> <br>
                   
                    
                </div>
            </div>
        </div>
    </div>
    @endsection