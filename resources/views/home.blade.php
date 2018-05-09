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
<<<<<<< HEAD
                    @endif                    You are logged in!
=======
                    @endif
                    You are logged in!
>>>>>>> b663ecb1834b2ac31387f898ea7c0d9625af6f7a
                </div>
            </div>
<<<<<<< HEAD
            <div class="card">
                <div class="card-body">
                    <a class="card-body" href="http://ainetproject.ainet/users">List of registered users</a>
                <a href="{{ url('/users') }}">User's List</a>
=======
>>>>>>> b663ecb1834b2ac31387f898ea7c0d9625af6f7a
        </div>
    </div>
