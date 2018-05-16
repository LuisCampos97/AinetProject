@extends('layouts.app') @section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-success alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Success!</strong> You are logged in.
            </div>
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif
                    <img src="/storage/app/{{ Auth::user()->profile_photo }}" class="rounded float-left" alt="Imagem" style="padding-right: 10px">
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
                <div class="card-header">Accounts</div>
                <div class="card-body">
                    <a class="btn btn-xs btn-success" >Create Account</a> <strong style="font-size: 20px"></strong>

                </div>
            </div>
        </div>
    </div>
    @endsection