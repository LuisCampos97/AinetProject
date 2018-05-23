@extends('layouts.app') @section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-success alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Success!</strong> You are logged in.
            </div>
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
                    <img src="{{ asset('/storage/profiles/' . Auth::user()->profile_photo) }}" class="rounded float-left" alt="Imagem" style="padding-right: 10px">
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
                    <a class="btn btn-xs btn-info" href="{{ action('UserController@accountsForUser', Auth::user()->id) }}">List of my Accounts</a><br><br>
                    <a class="btn btn-xs btn-success" href="{{ action('UserController@createAccount') }}">Add Account</a> <br><br>

                    <strong> TOTAL BALANCE OF ALL ACCOUNTS:</strong> <strong style="font-size: 20px"> {{ $total[0]->somatorio }} € </strong><br><br>
                    
                    <strong>PERCENTAGE OF EACH ACCOUNT IN TOTAL BALANCE:</strong>
                    <br>
                    @foreach ($accountsForUser as $account)
                        <strong style="font-size: 20px">Account ID: {{ $account->id }} = {{ round($account->current_balance * 100/$total[0]->somatorio, 2)}} % <br></strong>
                    @endforeach
                </div> <br>
                   
                    
                </div>
            </div>
        </div>
    </div>
    @endsection