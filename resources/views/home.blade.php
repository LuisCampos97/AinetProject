@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    You are logged in!<br><br>
                    <!-- Colocar aqui a foto do utilizador -->
                    <strong>User Name: </strong>{{ Auth::user()->name }}<br>
                    <strong>Email: </strong>{{ Auth::user()->email }} <br>
                    <strong>Phone Number: </strong> {{ Auth::user()->phone }}

                </div>
            </div>
        </div>
    </div>
@endsection
